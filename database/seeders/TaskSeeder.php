<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskDependency;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        // 🟡 1. استرجاع المدير
        $manager = User::where('email', 'manager@example.com')->first();

        // 🟢 2. استرجاع كل المستخدمين العاديين
        $users = User::where('role', 'user')->get();

        if (!$manager || $users->isEmpty()) {
            echo "❌ يرجى تشغيل UserSeeder أولاً.\n";
            return;
        }

        // 🟦 توزيع المهام على المستخدمين
        $userIndex = 0;

        // 📝 قائمة المهام
        $tasksData = [
            [
                'title' => 'تصميم قاعدة البيانات',
                'description' => 'إكمال مخطط ERD وجداول المايجريشن.',
                'due_date' => Carbon::tomorrow(),
                'status' => 'completed',
            ],
            [
                'title' => 'تنفيذ API تسجيل الدخول',
                'description' => 'تنفيذ منطق الدخول والخروج باستخدام Sanctum.',
                'due_date' => Carbon::now()->addDays(2),
                'status' => 'pending',
            ],
            [
                'title' => 'كتابة الوثائق الفنية',
                'description' => 'إعداد ملف الـ Readme وتوثيق الـ Endpoints.',
                'due_date' => Carbon::now()->addDays(5),
                'status' => 'in_progress',
            ],
            [
                'title' => 'مراجعة الكود النهائي',
                'description' => 'مراجعة كود الـ API والتحقق من الصلاحيات.',
                'due_date' => Carbon::now()->addDays(7),
                'status' => 'pending',
                'assign_to_manager' => true, // 🧑‍💼 دي للمدير
            ],
        ];

        // 🛠️ 3. إنشاء المهام
        $createdTasks = [];

        foreach ($tasksData as $taskData) {
            // لو المهمة مخصصة للمدير
            if (isset($taskData['assign_to_manager']) && $taskData['assign_to_manager']) {
                $assigneeId = $manager->id;
            } else {
                // توزيع بالتناوب على المستخدمين
                $assigneeId = $users[$userIndex % $users->count()]->id;
                $userIndex++;
            }

            $createdTasks[] = Task::create([
                'title' => $taskData['title'],
                'description' => $taskData['description'],
                'assignee_id' => $assigneeId,
                'created_by' => $manager->id,
                'due_date' => $taskData['due_date'],
                'status' => $taskData['status'],
            ]);
        }

        // 🧩 4. إنشاء التبعيات (مثال)
        $taskA = $createdTasks[0];
        $taskB = $createdTasks[1];
        $taskD = $createdTasks[3];

        TaskDependency::create([
            'task_id' => $taskB->id,
            'dependency_id' => $taskA->id,
        ]);

        TaskDependency::create([
            'task_id' => $taskD->id,
            'dependency_id' => $taskA->id,
        ]);

        echo "✅ تم إنشاء " . count($createdTasks) . " مهام و 2 تبعيات بنجاح.\n";
    }
}
