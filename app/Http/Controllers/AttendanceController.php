<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\AttendanceReport;
use App\Models\ClassMember;
use App\Models\ClassRoom;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        date_default_timezone_set("Asia/Makassar");
    }
    # ===================================================================
    public function scan(Attendance $attendance)
    {
        $user = Auth::user();
        if ($attendance->method == 1) {
            if ($user->id == $attendance->teacher) {
                return view("hasLogin.attendance.scan", compact("attendance"));
            } else {
                return redirect("account");
            }
        } else {
            if ($user->id == $attendance->teacher) {
                return view("hasLogin.attendance.show", compact("attendance"));
            } else {
                return view("hasLogin.attendance.scan", compact("attendance"));
            }
        }
    }
    # ============
    public function scandata(Attendance $attendance, Request $request)
    {
        $this->validate($request, ["data" => "required|string"]);
        $user = (Auth::user()->id == $attendance->teacher) ? User::find(str_replace("user", "", $request->data)) : Auth::user();
        $dat = array(
            "name" => $user->name,
            "status" => "-",
            "time" => "-",
            "late" => "-"
        );
        $check = AttendanceReport::where("attendance_id", $attendance->id)->where("user_id", $user->id)->first();
        if ($check != null) {
            $dat["status"] = "Already recorded";
            $dat["time"] = date("H:i:s d M Y", strtotime($check->created_at));
            $dat["late"] = "";
            if (strtotime($attendance->end) < strtotime($check->created_at)) {
                $diff = date_diff(date_create($attendance->end), date_create($check->created_at));
                $dat["late"] = $diff->format("%h Hour %i Minute %s Second");
            } else {
                $dat["late"] = "On Time";
            }
            return $dat;
        }
        if (Auth::user()->id != $attendance->teacher && $request->data != $attendance->key) {
            $dat["status"]= "Wrong key";
            return $dat;
        }
        $attendancereport = new AttendanceReport;
        $attendancereport->user_id = $user->id;
        $attendancereport->attendance_id = $attendance->id;
        $attendancereport->save();
        $dat["status"]= "Success";
        $dat["time"] = date("H:i:s d M Y", strtotime($attendancereport->created_at));
        $dat["late"] = "";
        if (strtotime($attendance->end) < strtotime($attendancereport->created_at)) {
            $diff = date_diff(date_create($attendance->end), date_create($attendancereport->created_at));
            $dat["late"] = $diff->format("%h Hour %i Minute %s Second");
        } else {
            $dat["late"] = "On Time";
        }
        return $dat;
    }
    # ===================
    public function permit(Attendance $attendance)
    {
        return view("hasLogin.attendance.permit", compact("attendance"));
    }
    # ===================
    public function permitdata(Attendance $attendance, Request $request)
    {
        $this->validate($request, ["reason" => "required|string"]);
        $user =Auth::user();
        $check = AttendanceReport::where("attendance_id", $attendance->id)->where("user_id", $user->id)->first();
        if ($check != null) {
            return redirect("attendance" . "/" . $attendance->id);
        }
        $attendancereport = new AttendanceReport;
        $attendancereport->status = false;
        $attendancereport->reason = $request->reason;
        $attendancereport->user_id = $user->id;
        $attendancereport->attendance_id = $attendance->id;
        $attendancereport->save();
        return redirect("attendance" . "/" . $attendance->id);

    }
    # ===================
    public function viewdata(Attendance $attendance)
    {
        $mid = array();
        foreach (ClassMember::where("class_id", $attendance->class_id)->get() as $key => $member) {
            array_push($mid, $member->user_id);
        }
        $user = User::whereIn("id", $mid)->get();
        $record = AttendanceReport::where("attendance_id", $attendance->id)->get();
        $data = array();
        $count = array(
            "total" => 0,
            "attend" => 0,
            "absence" => 0,
            "not recorded" =>0,
        );
        foreach ($user as $key => $people) {
            if ($people->id != $attendance->teacher ) {
                $dat = array(
                    "name" => $people->name,
                    "status" => 0,
                    "reason" => "-",
                    "time" => "-",
                    "late" => "-"
                );
                $count["total"]++;
                foreach ($record as $key => $report) {
                    if ($report->user_id == $people->id) {
                        ($report->status) ? $count["attend"]++ : $count["absence"]++ ;
                        $dat["status"] = $report->status;
                        $dat["reason"] = $report->reason;
                        $dat["time"] = date("H:i:s D M Y", strtotime($report->created_at));
                        if ($report->status) {
                            if (strtotime($attendance->end) < strtotime($report->created_at)) {
                                $diff = date_diff(date_create($attendance->end), date_create($report->created_at));
                                $dat["late"] = $diff->format("%h Hour %i Minute %s Second");
                            } else {
                                $dat["late"] = "On Time";
                            }
                        }  
                    }
                }
                array_push($data, $dat);
            }   
        }
        $count["not recorded"] = $count["total"] - $count["attend"] - $count["absence"];
        $percentage = array(
            "total" => 100,
            "attend" => intval($count["attend"] / $count["total"] * 100),
            "absence" => intval($count["absence"] / $count["total"] * 100),
            "not recorded" => intval($count["not recorded"] / $count["total"] * 100),
        );
        $res = array(
            "count" => $count,
            "percentage" => $percentage,
            "data" => $data,
        );
        return $res;
    }
    # ===================================================================
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($classroom)
    {
        $attendances = Attendance::where("class_id", $classroom)->latest()->get();
        $classroom = ClassRoom::where("id", $classroom)->first();
        return view("hasLogin.attendance.index", compact("classroom", "attendances"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->role == "teacher") {
            $classrooms = Auth::user()->classrooms;
            return view("hasLogin.attendance.create", compact("classrooms"));
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
                "method" => "required|in:1,2",
                "class_id" => "required|integer|exists:class_rooms,id",
                "start" => "required",
                "end" => "required",
            ]);
            $attendance = new Attendance;
            $attendance->name = $request->name;
            $attendance->teacher = Auth::user()->id;
            $attendance->class_id = $request->class_id;
            $attendance->method = $request->method;
            $attendance->key = substr( str_shuffle( "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ" ), 0, 10);
            $attendance->start = $request->start;
            $attendance->end = $request->end;
            $attendance->save();

            $attendancename = $request->name;
            $gourl = url("attendance") . "/" . $attendance->id . "/scan";
            $permiturl = url("attendance") . "/" . $attendance->id . "/permit";
            $dataurl = url("attendance") . "/" . $attendance->id;
            $body = "<div class='card text-center' style='background-color: #cdd5ee;'>
                <div class='card-body'>
                    <p class='card-text'>Created New Attendance Check</p>
                    <h5 class='card-title text-dark'>$attendancename</h5>
                    <div class='row'>
                        <div class='col col-md-4'>
                            <a href='$gourl' class='btn btn-primary btn-icon-split btn-block'>
                                <span class='icon text-white-50'><i class='fas fa-qrcode'></i></span>
                                <span class='text btn-block'>Go</span>
                            </a>
                        </div>
                        <div class='col col-md-4'>
                            <a href='$permiturl' class='btn btn-info btn-icon-split btn-block'>
                                <span class='icon text-white-50'><i class='fas fa-virus'></i></span>
                                <span class='text btn-block'>Permit</span>
                            </a>
                        </div>
                        <div class='col col-md-4'>
                            <a href='$dataurl' class='btn btn-success btn-icon-split btn-block'>
                                <span class='icon text-white-50'><i class='fas fa-database'></i></span>
                                <span class='text btn-block'>Get Data</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>";
            $post = new Post;
            $post->body = $body;
            $post->user_id = Auth::user()->id;
            $post->class_id = $request->class_id;
            $post->save();
            return redirect("attendance" . "/" . $attendance->id . "/scan");
        } else {
            return abort("403");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        return view("hasLogin.attendance.data", compact("attendance"));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        if (Auth::user()->id == $attendance->teacher) {
            $classrooms = Auth::user()->classrooms;
            return view("hasLogin.attendance.edit", compact("attendance", "classrooms"));
        } else {
            return abort("403");
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Attendance $attendance)
    {
        if (Auth::user()->id == $attendance->teacher) {
            $this->validate($request, [
                "name" => "required|string|max:255",
                "method" => "required|in:1,2",
                "class_id" => "required|integer|exists:class_rooms,id",
                "start" => "required",
                "end" => "required",
            ]);
            $attendance->update([
                "name" => $request->name,
                "method" => $request->method,
                "start" => $request->start,
                "end" => $request->end,
            ]);
            return redirect("/classroom" . "/" . $request->class_id . "/attendance");
        } else {
            return abort("403");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        if (Auth::user()->id == $attendance->teacher) {
            AttendanceReport::where("attendance_id", $attendance->id)->delete();
            $classroom = $attendance->class_id;
            $attendance->delete();
            return redirect(url("/classroom" . "/" . $classroom . "/attendance"));
        } else {
            return abort("403");
        }
    }
}
