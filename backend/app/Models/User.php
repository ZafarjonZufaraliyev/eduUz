<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'full_name', 'username', 'password', 'role',
        'category_id', 'class_id', 'phone', 'parent_phone',
        'photo', 'qr_checkin', 'qr_checkout', 'is_active',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }

    public function generateQrCodes(): void
    {
        $token = Str::random(8);
        $this->qr_checkin  = "QR_IN_{$this->id}_{$token}";
        $this->qr_checkout = "QR_OUT_{$this->id}_{$token}";
        $this->save();
    }

    public function toArray(): array
    {
        $arr = parent::toArray();
        $arr['category'] = $this->category;
        $arr['class'] = $this->schoolClass;
        return $arr;
    }
}
