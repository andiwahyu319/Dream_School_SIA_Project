<?php

namespace App\Http\Controllers;

use App\Models\ClassMember;
use App\Models\ClassRoom;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    #===================================================================
    public function invitation($code)
    {
        $classroom = ClassRoom::where("invitation_code", $code)->first();
        $user = Auth::user();
        if ($user->role == "teacher") {
            return redirect("classroom")->with('fail', 'Only student can join this class');
        }
        if ($classroom == null) {
            return redirect("classroom")->with('fail', 'Nothing classroom have this code');
        }
        if ($classroom->private == 1) {
            return redirect("classroom")->with('fail', 'This classroom is private, ask your teacher to change');
        }
        if (ClassMember::where("class_id", $classroom->id)->where("user_id", $user->id)->first() != null) {
            return redirect("classroom" . "/" . $classroom->id);
        }
        $class_member = new ClassMember;
        $class_member->user_id = $user->id;
        $class_member->class_id = $classroom->id;
        $class_member->save();
        return redirect("classroom" . "/" . $classroom->id);
    }
    #======
    public function member(ClassRoom $classroom)
    {
        if (ClassMember::with("user_id", Auth::user()->id)->with("class_id", $classroom->id)->exists()) {
            $room = $classroom->with("member")->first();
            return view("hasLogin.classRoom.member", compact("room"));
        } else {
            return abort("403");
        }
    }
    #======
    public function out(ClassRoom $classroom)
    {
        $user = Auth::user();
        if ($user->id == $classroom->teacher) {
            return redirect()->back();
        }
        ClassMember::where("user_id", $user->id)->where("class_id", $classroom->id)->delete();
        return redirect("home");
    }
    #====================================================================
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classrooms = Auth::user()->classrooms;
        return view("hasLogin.classRoom.index", compact("classrooms"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
                "description" => "required|max:255",
            ]);
            $classroom = new ClassRoom;
            $classroom->name = $request->name;
            $classroom->description = $request->description;
            $classroom->invitation_code = substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 15);
            $classroom->teacher = Auth::user()->id;
            $classroom->save();

            $classmember = new ClassMember;
            $classmember->user_id = Auth::user()->id;
            $classmember->class_id = $classroom->id;
            $classmember->save();
            return redirect("classroom" . "/" . $classroom->id);
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassRoom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function show(ClassRoom $classroom)
    {
        if (ClassMember::with("user_id", Auth::user()->id)->with("class_id", $classroom->id)->exists()) {
            $room = $classroom->with("teacherData", "member")->first();
            $posts = Post::where("class_id", $classroom->id)->with("user", "classroom", "comment.user")->latest()->paginate(5);
            return view("hasLogin.classRoom.view", compact("room", "posts"));
        } else {
            return abort("403");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassRoom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassRoom $classroom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClassRoom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClassRoom $classroom)
    {
        if (Auth::user()->id == $classroom->teacher) {
            $this->validate($request, [
                "name" => "required|string|max:255",
                "description" => "required|max:255",
                "private" => "required|boolean",
            ]);
            $classroom->update([
                "name" => $request->name,
                "description" => $request->description,
                "private" => $request->private,
            ]);
            return redirect()->back();
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassRoom  $classroom
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassRoom $classroom)
    {
        if (Auth::user()->id == $classroom->teacher) {
            ClassMember::where("class_id", $classroom->id)->delete();
            $classroom->delete();
            return redirect("classroom");
        } else {
            return abort("403");
        }
    }
}
