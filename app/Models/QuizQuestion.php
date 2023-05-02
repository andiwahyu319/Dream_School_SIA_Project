<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    use HasFactory;

    protected $fillable = ["quiz_id", "question", "option_a", "option_b", "option_c", "option_d", "true_answer"];
}
