<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDependency extends Model
{
    use HasFactory;

    // اسم جدول الربط
    protected $table = 'task_dependencies';

    // الحقول المسموح بملئها
    protected $fillable = [
        'task_id',       // المهمة التابعة (Dependent Task)
        'dependency_id', // المهمة المعتمد عليها (Pre-requisite Task)
    ];

    // بما أن جدول الربط لا يتطلب عادةً حقول updated_at و created_at، يمكن تعطيلهما
    public $timestamps = false;

    // ملاحظة: إذا كانت الـ migrations الخاصة بك قد أضافت هذه الحقول، فيمكنك إبقاء هذا السطر محذوفاً.
    // وبناءً على ملف task_manager.sql المرفق سابقاً، الجدول لا يحتوي على timestamps، لذا سنبقيها false.
}