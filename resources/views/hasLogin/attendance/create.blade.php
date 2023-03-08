@extends("layouts.hasLogin")

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Create Attendance Check</h1>
</div>
<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-6">
        <div class="card shadow mb-4">
            <form action="{{url('attendance')}}" method="post">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Create Attendance Check
                    </h6>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group">
                        <label>Method</label>
                        <select name="method" id="method" class="form-control">
                            <option value="1">Teacher scan QR in student generated Id card</option>
                            <option value="2">Student Scan QR from teacher</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Classroom</label>
                        <select name="class_id" id="class_id" class="form-control">
                            @foreach($classrooms as $key => $classroom)
                            <option value="{{$classroom->id}}">{{$classroom->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Start Time</label>
                        <input type="datetime-local" name="start" class="form-control" id="start" placeholder="Enter Start Time" required>
                    </div>
                    <div class="form-group">
                        <label>End Time</label>
                        <input type="datetime-local" name="end" class="form-control" id="end" placeholder="Enter End Time" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-icon-split btn-block">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text btn-block">Create Attendance Check</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection