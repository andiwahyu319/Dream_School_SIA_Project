<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $classroom = array();
        foreach (Auth::user()->classrooms as $key => $room) {
            array_push($classroom, $room->id);
        }
        $posts = Post::whereIn("class_id", $classroom)->with("user", "classroom", "comment.user")->latest()->paginate(5);
        return view('hasLogin.dashboard', compact("posts"));
    }

    public function userclassroom()
    {
        return Auth::user()->classrooms;
    }

    public function test()
    {
        return ClassRoom::with("teacherData", "member")->get();
    }
}
