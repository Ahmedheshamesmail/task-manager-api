<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskDependencyRequest;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class TaskDependencyController extends Controller
{
    use AuthorizesRequests;

    public function store(StoreTaskDependencyRequest $request, Task $task)
    {
        $this->authorize('update', $task);

        $dependencyId = $request->dependency_id;

        // ❌ منع self-dependency
        if ($task->id == $dependencyId) {
            return response()->json([
                'success' => false,
                'message' => 'A task cannot depend on itself.',
                'data' => null,
            ], 422);
        }

        // ❌ منع الدائرة (Circular dependency)
        $isCircular = DB::table('task_dependencies')
            ->where('task_id', $dependencyId)
            ->where('dependency_id', $task->id)
            ->exists();

        if ($isCircular) {
            return response()->json([
                'success' => false,
                'message' => 'Circular dependency not allowed.',
                'data' => null,
            ], 422);
        }

        // ✅ إضافة التبعية
        $task->dependencies()->syncWithoutDetaching([$dependencyId]);

        return response()->json([
            'success' => true,
            'message' => 'Dependency added successfully',
            'data' => [
                'task_id' => $task->id,
                'dependency_id' => $dependencyId
            ],
        ], 201);
    }

    public function destroy(Task $task, $dependencyId)
    {
        $this->authorize('update', $task);

        $exists = $task->dependencies()->where('dependency_id', $dependencyId)->exists();
        if (!$exists) {
            return response()->json([
                'success' => false,
                'message' => 'Dependency not found for this task.',
                'data' => null,
            ], 404);
        }

        $task->dependencies()->detach($dependencyId);

        return response()->json([
            'success' => true,
            'message' => 'Dependency removed successfully',
            'data' => [
                'task_id' => $task->id,
                'dependency_id' => $dependencyId
            ],
        ], 200);
    }
}
