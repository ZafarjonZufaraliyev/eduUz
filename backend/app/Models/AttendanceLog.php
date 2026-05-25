<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    protected $fillable = ['user_id', 'type', 'scanned_at', 'device_id', 'is_late', 'note'];

    protected function casts(): array
    {
        return [
            'scanned_at' => 'datetime',
            'is_late' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class)->with(['category', 'schoolClass']);
    }
}
