<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = ["name", "teacher", "class_id", "method", "key", "start", "end"];

    public function teacherData()
    {
        return $this->belongsTo(User::class, "teacher");
    }
}
