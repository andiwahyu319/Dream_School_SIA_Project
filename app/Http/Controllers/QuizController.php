<?php

namespace App\Http\Controllers;

use App\Models\ClassMember;
use App\Models\ClassRoom;
use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use App\Models\User;
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

    // ===============================================================
    // ===============================================================
    // ===============================================================

    public function submit(Request $request, Quiz $quiz)
    {
        $this->validate($request, [
            "answer" => "required"
        ]);
        $answers = json_decode($request->answer);
        $questions = QuizQuestion::select("id", "true_answer")->where("quiz_id", $quiz->id)->get();
        $total_question = $questions->count();
        $answer_true = 0;
        $answer_false = 0;
        foreach ($questions as $key => $question) {
            foreach ($answers as $key => $answer) {
                if ($question->id == $answer->question_id) {
                    if ($answer->answer == "") {
                        // pass
                    } else if ($answer->answer == $question->true_answer) {
                        $answer_true++;
                    } else {
                        $answer_false++;
                    }
                }
            }
        }
        $not_answered = $total_question - $answer_true - $answer_false;
        $score = $answer_true / $total_question * 100;

        $result = new QuizResult;
        $result->quiz_id = $quiz->id;
        $result->student = Auth::user()->id;
        $result->answer_true = $answer_true;
        $result->answer_false = $answer_false;
        $result->not_answered = $not_answered;
        $result->score = $score;
        $result->answer = $request->answer;
        $result->save();

        return array(
            "status" => "ok",
            "student" => Auth::user()->name,
            "answer_true" => $answer_true,
            "answer_false" => $answer_false,
            "not_answered" => $not_answered,
            "score" => $score
        );
    }

    // ===============================================================
    // ===============================================================
    // ===============================================================

    public function score(Quiz $quiz)
    {
        $mid = array();
        foreach (ClassMember::where("class_id", $quiz->class_id)->get() as $key => $member) {
            array_push($mid, $member->user_id);
        }
        $users = User::whereIn("id", $mid)->get();
        $data = array();
        $results = QuizResult::where("quiz_id", $quiz->id)->get();
        foreach ($users as $key => $user) {
            if ($user->id != $quiz->teacher) {
                $dat = array(
                    "name" => $user->name,
                    "submit" => "-",
                    "answer_true" => "-",
                    "answer_false" => "-",
                    "score" => "-"
                );
                foreach ($results as $key => $result) {
                    if ($result->student == $user->id) {
                        $dat["submit"] = $result->created_at;
                        $dat["answer_true"] = $result->answer_true;
                        $dat["answer_false"] = $result->answer_false + $result->not_answered;
                        $dat["score"] = $result->score;
                    }
                }
                array_push($data, $dat);
            }
        }
        return view("hasLogin.quiz.score", compact("quiz", "data"));
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
        if (Auth::user()->id == $quiz->teacher) {
            return redirect(url("quiz" . "/" . $quiz->id . "/edit"));
        } elseif ((strtotime(date("Y-m-d H:i:s")) < strtotime($quiz->start)) or (strtotime(date("Y-m-d H:i:s")) > strtotime($quiz->end))) {
            return redirect(url("quiz" . "/" . $quiz->id . "/score"));
        } elseif (QuizResult::where("quiz_id", $quiz->id)->get()->contains("student", Auth::user()->id)) {
            return redirect(url("quiz" . "/" . $quiz->id . "/score"));
        } else {
            return view("hasLogin.quiz.view", compact("quiz"));
        }
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
        if (Auth::user()->id == $quiz->teacher) {
            QuizQuestion::where("quiz_id", $quiz->id)->delete();
            QuizResult::where("quiz_id", $quiz->id)->delete();
            $classroom = $quiz->class_id;
            $quiz->delete();
            return redirect(url("/classroom" . "/" . $classroom . "/quiz"));
        } else {
            return abort("403");
        }
    }
}
