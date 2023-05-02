<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
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
    public function index($classroom)
    {
        $quizzes =Quiz::where("class_id", $classroom)->latest()->get();
        $classname = ClassRoom::where("id", $classroom)->first()->name;
        return view("hasLogin.quiz.index", compact("quizzes", "classname"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->role == "teacher") {
            $last_url = url()->previous();
            $split = explode("/", $last_url);
            $class_id = 0;
            if (($split[count($split)-1] == "quiz") and ($split[count($split)-3] == "classroom")) {
                $class_id = $split[count($split)-2];
            }
            $classrooms = Auth::user()->classrooms;
            return view("hasLogin.quiz.create", compact("class_id", "classrooms"));
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
    public function store(Request $request)
    {
        if (Auth::user()->role == "teacher") {
            $this->validate($request, [
                "name" => "required|string|max:255",
                "class_id" => "required|integer|exists:class_rooms,id",
                "duration" => "required|integer",
                "start" => "required|date",
                "end" => "required|date|after:start"
            ]);
            $quiz = new Quiz;
            $quiz->name = $request->name;
            $quiz->class_id = $request->class_id;
            $quiz->duration = $request->duration * 60;
            $quiz->start = $request->start;
            $quiz->end = $request->end;
            $quiz->teacher = Auth::user()->id;
            $quiz->save();
            return redirect(url("quiz" . "/" . $quiz->id . "/q/edit"));
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function show(Quiz $quiz)
    {
        return view("hasLogin.quiz.view");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        if (Auth::user()->id == $quiz->teacher) {
            $questions = QuizQuestion::select("id")->where("quiz_id", $quiz->id)->get();
            $classname = ClassRoom::where("id", $quiz->class_id)->first()->name;
            return view("hasLogin.quiz.edit", compact("quiz", "classname", "questions"));
        } else {
            return abort("403");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quiz $quiz)
    {
        if (Auth::user()->id == $quiz->teacher) {
            $this->validate($request, [
                "name" => "required|string|max:255",
                "duration" => "required|integer",
                "start" => "required|date",
                "end" => "required|date|after:start",
            ]);
            $quiz->update([
                "name" => $request->name,
                "duration" => $request->duration * 60,
                "start" => $request->start,
                "end" => $request->end,
            ]);
            return redirect()->back()->with("success", "updates have been saved");
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quiz $quiz)
    {
        //
    }
}
