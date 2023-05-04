<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizQuestionController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Quiz $quiz)
    {
        if (Auth::user()->id == $quiz->teacher) {
            $questions = QuizQuestion::select("id")->where("quiz_id", $quiz->id)->get();
            $classname = ClassRoom::where("id", $quiz->class_id)->first()->name;
            return view("hasLogin.quiz.question-create", compact("quiz", "classname", "questions"));
        } else {
            return abort("403");
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Quiz $quiz, Request $request)
    {
        if (Auth::user()->id == $quiz->teacher) {
            $this->validate($request, [
                "question" => "required",
                "option_a" => "required|string|max:255",
                "option_b" => "required|string|max:255",
                "option_c" => "required|string|max:255",
                "option_d" => "required|string|max:255",
                "true_answer" => "required|in:a,b,c,d"
            ]);
            $question = new QuizQuestion;
            $question->quiz_id = $quiz->id;
            $question->question = $request->question;
            $question->option_a = $request->option_a;
            $question->option_b = $request->option_b;
            $question->option_c = $request->option_c;
            $question->option_d = $request->option_d;
            $question->true_answer = $request->true_answer;
            $question->save();
            return redirect()->back()->with("success", "new question has been saved");
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuizQuestion  $quizQuestion
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        $quiz->question = QuizQuestion::select("id", "question", "option_a", "option_b", "option_c", "option_d")
        ->where("quiz_id", $quiz->id)
        ->get()->shuffle();
        return $quiz;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuizQuestion  $quizQuestion
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz, QuizQuestion $quizQuestion)
    {
        $question = $quizQuestion;
        if (Auth::user()->id != $quiz->teacher) {
            return abort("403");
        } elseif ($question->quiz_id != $quiz->id) {
            return abort("404");
        } else {
            $questions = QuizQuestion::select("id")->where("quiz_id", $quiz->id)->get();
            $classname = ClassRoom::where("id", $quiz->class_id)->first()->name;
            return view("hasLogin.quiz.question-edit", compact("quiz", "classname", "questions", "question"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\QuizQuestion  $quizQuestion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quiz $quiz, QuizQuestion $quizQuestion)
    {
        $question = $quizQuestion;
        if (Auth::user()->id != $quiz->teacher) {
            return abort("403");
        } elseif ($question->quiz_id != $quiz->id) {
            return abort("404");
        } else {
            $this->validate($request, [
                "question" => "required",
                "option_a" => "required|string|max:255",
                "option_b" => "required|string|max:255",
                "option_c" => "required|string|max:255",
                "option_d" => "required|string|max:255",
                "true_answer" => "required|in:a,b,c,d"
            ]);
            $question->update([
                "question" => $request->question,
                "option_a" => $request->option_a,
                "option_b" => $request->option_b,
                "option_c" => $request->option_c,
                "option_d" => $request->option_d,
                "true_answer" => $request->true_answer,
            ]);
            return redirect()->back()->with("success", "updates have been saved");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuizQuestion  $quizQuestion
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuizQuestion $quizQuestion)
    {
        //
    }
}
