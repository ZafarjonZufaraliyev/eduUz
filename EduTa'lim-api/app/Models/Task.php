<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'course_id', 'lesson_id', 'teacher_id',
        'title', 'description', 'deadline',
    ];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function course(): BelongsTo   { return $this->belongsTo(Course::class); }
    public function lesson(): BelongsTo   { return $this->belongsTo(Lesson::class); }
    public function teacher(): BelongsTo  { return $this->belongsTo(User::class, 'teacher_id'); }
    public function submissions(): HasMany { return $this->hasMany(TaskSubmission::class); }
}
