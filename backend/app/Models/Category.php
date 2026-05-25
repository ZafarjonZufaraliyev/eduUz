<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'color'];

    public function classes()
    {
        return $this->hasMany(SchoolClass::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
