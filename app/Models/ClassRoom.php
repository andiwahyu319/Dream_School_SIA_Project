<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    use HasFactory;

    protected $fillable = ["name", "description", "invitation_code", "teacher", "private"];

    public function teacherData()
    {
        return $this->belongsTo(User::class, "teacher");
    }
    public function member()
    {
        return $this->belongsToMany(User::class, "class_members", "class_id", "user_id");
    }
}
