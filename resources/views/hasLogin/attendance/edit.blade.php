@extends("layouts.hasLogin")

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Update Attendance Check</h1>
</div>
<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-6">
        <div class="card shadow mb-4">
            <form action="{{ url('attendance') . '/' . $attendance->id }}" method="post">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Update Attendance Check
                    </h6>
                </div>
                <div class="card-body">
                    @csrf @method("put")
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{ $attendance->name }}" placeholder="Enter Name" required>
                    </div>
                    <div class="form-group">
                        <label>Method</label>
                        <select name="method" id="method" class="form-control">
                            <option value="1" {{ ($attendance->method == 1) ? "selected": "" }} >Teacher scan QR in student generated Id card</option>
                            <option value="2" {{ ($attendance->method == 2) ? "selected": "" }} >Student Scan QR from teacher</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Classroom</label>
                        <select name="class_id" id="class_id" class="form-control" readonly>
                            @foreach($classrooms as $key => $classroom)
                            <option value="{{$classroom->id}}" {{ ($attendance->class_id == $classroom->id) ? "selected": "" }} >{{$classroom->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Start Time</label>
                        <input type="datetime-local" name="start" class="form-control" id="start" value="{{ $attendance->start }}" placeholder="Enter Start Time" required>
                    </div>
                    <div class="form-group">
                        <label>End Time</label>
                        <input type="datetime-local" name="end" class="form-control" id="end" value="{{ $attendance->end }}" placeholder="Enter End Time" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-icon-split btn-block">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text btn-block">Update Attendance Check</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection