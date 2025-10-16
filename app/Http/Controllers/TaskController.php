<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Task::query()->with(['assignee', 'creator', 'dependencies']);

        // 🧑‍💼 فلترة حسب الدور
        if ($user->role !== 'manager') {
            $query->where('assignee_id', $user->id);
        }

        // 📌 فلترة حسب الحالة
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 👤 فلترة حسب المستخدم المعيّن
        if ($request->filled('assignee_id') && $user->role === 'manager') {
            $query->where('assignee_id', $request->assignee_id);
        }

        // 📅 فلترة حسب التاريخ (from - to)
        if ($request->filled('due_date_from')) {
            $query->whereDate('due_date', '>=', $request->due_date_from);
        }
        if ($request->filled('due_date_to')) {
            $query->whereDate('due_date', '<=', $request->due_date_to);
        }

        $tasks = $query->paginate(15);

        return response()->json([
            'success' => true,
            'message' => 'Tasks retrieved successfully',
            'data' => $tasks,
        ], 200);
    }

    public function show(Task $task)
    {
        $this->authorize('view', $task);

        return response()->json([
            'success' => true,
            'message' => 'Task retrieved successfully',
            'data' => $task->load(['assignee', 'creator', 'dependencies']),
        ], 200);
    }

    public function store(StoreTaskRequest $request)
    {
        $this->authorize('create', Task::class);

        $data = $request->validated();
        $data['created_by'] = $request->user()->id;

        $task = Task::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => $task,
        ], 201);
    }

    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => $task,
        ], 200);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task)
    {
        $this->authorize('updateStatus', $task);

        $newStatus = $request->status;

        // ✅ التحقق من التبعيات قبل الاكتمال
        if ($newStatus === 'completed') {
            $hasIncompleteDeps = $task->dependencies()->where('status', '!=', 'completed')->exists();
            if ($hasIncompleteDeps) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot complete this task. Some dependencies are not completed yet.',
                    'data' => null,
                ], 422);
            }
        }

        $task->status = $newStatus;
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully',
            'data' => $task,
        ], 200);
    }

    public function destroy(Task $task)
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully',
            'data' => null,
        ], 200);
    }
}
