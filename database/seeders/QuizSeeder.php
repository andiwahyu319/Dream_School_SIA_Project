<?php

namespace Database\Seeders;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=QuizSeeder
     *
     * @return void
     */
    public function run()
    {
        function make_question()
        {
            $num1 = rand(0, 100);
            $num2 = rand(0, 100);
            $opr = ["+", "-", "*", "/"];
            $opr_key = array_rand($opr, 1);
            $option = ["a", "b", "c", "d"];
            $true_key = array_rand($option, 1);
            if ($opr[$opr_key] == "+") {
                $question = "<p>" . $num1 . " " . $opr[$opr_key] . " " . $num2 . " = ?</p>";
                $answer = $num1 + $num2;
            } else if ($opr[$opr_key] == "-") {
                $question = "<p>" . $num1 . " " . $opr[$opr_key] . " " . $num2 . " = ?</p>";
                $answer = $num1 - $num2;
            } else if ($opr[$opr_key] == "*") {
                $question = "<p>" . $num1 . " " . $opr[$opr_key] . " " . $num2 . " = ?</p>";
                $answer = $num1 * $num2;
            } else if ($opr[$opr_key] == "/") {
                $question = "<p>" . $num1 . " " . $opr[$opr_key] . " " . $num2 . " = ?</p>";
                $answer = $num1 / $num2;
            }
            $option_a = ($option[$true_key] == "a") ? $answer : rand($answer - 20, $answer + 20);
            $option_b = ($option[$true_key] == "b") ? $answer : rand($answer - 20, $answer + 20);
            $option_c = ($option[$true_key] == "c") ? $answer : rand($answer - 20, $answer + 20);
            $option_d = ($option[$true_key] == "d") ? $answer : rand($answer - 20, $answer + 20);
            return array(
                "question" => $question,
                "option_a" => $option_a,
                "option_b" => $option_b,
                "option_c" => $option_c,
                "option_d" => $option_d,
                "true_answer" => $option[$true_key]
            );
        }

        $faker = Faker::create();
        $quiz = new Quiz;
        $quiz->name = $faker->sentence(2);
        $quiz->class_id = 1;
        $quiz->duration = 30 * 60;
        $quiz->start = date("Y-m-d H:i:s");
        $quiz->end = date("Y-m-d H:i:s", time() + 36000);
        $quiz->teacher = 2;
        $quiz->save();

        for ($i=1; $i <= 10; $i++) { 
            $quest = make_question();
            $question = new QuizQuestion;
            $question->quiz_id = $quiz->id;
            $question->question = $quest["question"];
            $question->option_a = $quest["option_a"];
            $question->option_b = $quest["option_b"];
            $question->option_c = $quest["option_c"];
            $question->option_d = $quest["option_d"];
            $question->true_answer = $quest["true_answer"];
            $question->save();
        }
    }
}
