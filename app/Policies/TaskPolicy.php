<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        // Manager يشوف كل التاسكات، user يشوف التاسكات المخصصة له
        return true; // نفلتر في controller حسب الدور
    }

    public function view(User $user, Task $task)
    {
        if ($user->role === 'manager') return Response::allow();
        return $user->id === $task->assignee_id
            ? Response::allow()
            : Response::deny('You do not have permission to view this task.');
    }

    public function create(User $user)
    {
        return $user->role === 'manager'
            ? Response::allow()
            : Response::deny('Only managers can create tasks.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Task $task)
    {
        // Managers can update any field; assignee can NOT update title/description/assignee
        if ($user->role === 'manager') return Response::allow();
        return $user->id === $task->assignee_id
            ? Response::deny('Assignee cannot update task details.') // force using updateStatus for assignees
            : Response::deny('You do not have permission to update this task.');
    }


    public function updateStatus(User $user, Task $task)
    {
        // manager can update any status; assignee can update status of their assigned tasks
        if ($user->role === 'manager') return Response::allow();
        return $user->id === $task->assignee_id
            ? Response::allow()
            : Response::deny('Only assignee or manager can change status.');
    }

    public function assign(User $user, Task $task)
    {
        return $user->role === 'manager'
            ? Response::allow()
            : Response::deny('Only managers can assign tasks.');
    }

    public function delete(User $user, Task $task)
    {
        return $user->role === 'manager'
            ? Response::allow()
            : Response::deny('Only managers can delete tasks.');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Task $task): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return false;
    }
}
