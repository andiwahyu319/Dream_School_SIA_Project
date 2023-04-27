<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
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
        $lessons = Lesson::select("id", "title", "teacher")->where("class_id", $classroom)->get();
        $classname = ClassRoom::where("id", $classroom)->first()->name;
        return view("hasLogin.lesson.index", compact("lessons", "classname"));
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
            if (($split[count($split)-1] == "lesson") and ($split[count($split)-3] == "classroom")) {
                $class_id = $split[count($split)-2];
            }
            $classrooms = Auth::user()->classrooms;
            return view("hasLogin.lesson.create", compact("class_id", "classrooms"));
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
                "title" => "required|string|max:255",
                "body" => "required",
                "class_id" => "required|integer|exists:class_rooms,id"
            ]);
            $lesson = new Lesson;
            $lesson->title = $request->title;
            $lesson->body = $request->body;
            $lesson->class_id = $request->class_id;
            $lesson->teacher = Auth::user()->id;
            $lesson->save();
            return redirect(url("lesson" . "/" . $lesson->id));
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        $lessons = Lesson::select("id", "title")->where("class_id", $lesson->class_id)->get();
        $classname = ClassRoom::where("id", $lesson->class_id)->first()->name;
        return view("hasLogin.lesson.view", compact("lessons", "classname", "lesson"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        if (Auth::user()->id == $lesson->teacher) {
            $lessons = Lesson::select("id", "title")->where("class_id", $lesson->class_id)->get();
            $classname = ClassRoom::where("id", $lesson->class_id)->first()->name;
            return view("hasLogin.lesson.edit", compact("lessons", "classname", "lesson"));
        } else {
            return abort("403");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        if (Auth::user()->id == $lesson->teacher) {
            $this->validate($request, [
                "title" => "required|string|max:255",
                "body" => "required"
            ]);
            $lesson->update([
                "title" => $request->title,
                "body" => $request->body
            ]);
            return redirect(url("lesson" . "/" . $lesson->id));
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        if (Auth::user()->id == $lesson->teacher) {
            $classroom = $lesson->class_id;
            $lesson->delete();
            return redirect(url("/classroom" . "/" . $classroom . "/lesson"));
        } else {
            return abort("403");
        }
    }
}
