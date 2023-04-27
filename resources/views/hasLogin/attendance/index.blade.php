@extends("layouts.hasLogin")

@section("css")
<link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Attendance Check in {{$classroom->name}}</h1>
    @if(Auth::user()->role == "teacher")
    <button class="btn btn-primary" onclick="add()">Make New</button>
    @endif
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-10 mb-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="lesson" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($attendances as $key => $attendance)
                            <tr>
                                <td>{{$key + 1}}.</td>
                                <td>{{$attendance->name}}</td>
                                <td>
                                    @if (strtotime(date("Y-m-d H:i:s")) < strtotime($attendance->start))
                                        <span class="text-warning" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="Start : {{$attendance->start}} <br>Late : {{$attendance->end}}">Upcoming</span>
                                    @elseif((strtotime(date("Y-m-d H:i:s")) > strtotime($attendance->start)) and (strtotime(date("Y-m-d H:i:s")) < strtotime($attendance->end)))
                                        <span class="text-success" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="Start : {{$attendance->start}} <br>Late : {{$attendance->end}}">Now</span>
                                    @elseif((strtotime(date("Y-m-d H:i:s")) > strtotime($attendance->end)) and (strtotime(date("Y-m-d H:i:s")) < (strtotime($attendance->end) + 10800)))
                                        <span class="text-danger" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="Start : {{$attendance->start}} <br>Late : {{$attendance->end}}">Late</span>
                                    @else
                                        <span class="text-secondary" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="Start : {{$attendance->start}} <br>Late : {{$attendance->end}}">Expired</span>
                                    @endif
                                </td>
                                <td>{{($attendance->method == 1) ? 'Teacher scan QR in student generated Id card' : 'Student Scan QR from teacher';}}</td>
                                <td>
                                    {{-- Scan Button --}}
                                    @if((strtotime(date("Y-m-d H:i:s")) < strtotime($attendance->start)) or (strtotime(date("Y-m-d H:i:s")) > (strtotime($attendance->end) + 10800)))
                                        <div class="btn p-0" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Scan Qr (Inactive)">
                                            <button class="btn btn-primary btn-circle btn-sm" disabled>
                                                <i class="fas fa-qrcode"></i>
                                            </button>
                                        </div>
                                        
                                    @elseif(($attendance->method == 1) and (Auth::user()->id == $attendance->teacher))
                                        <a href="{{ url('attendance') . '/' . $attendance->id . '/scan' }}" class="btn btn-primary btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Scan Qr">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @elseif(($attendance->method == 1) and (Auth::user()->id != $attendance->teacher))
                                        <a href="{{ url('account') }}" class="btn btn-primary btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Get Generated Id Card">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @elseif(($attendance->method == 2) and (Auth::user()->id == $attendance->teacher))
                                        <a href="{{ url('attendance') . '/' . $attendance->id . '/scan' }}" class="btn btn-primary btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Show Qr">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @elseif(($attendance->method == 2) and (Auth::user()->id != $attendance->teacher))
                                        <a href="{{ url('attendance') . '/' . $attendance->id . '/scan' }}" class="btn btn-primary btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Scan Qr">
                                            <i class="fas fa-qrcode"></i>
                                        </a>
                                    @endif
                                    {{-- Edit Delete ? Permit Button --}}
                                    @if (Auth::user()->id == $attendance->teacher)
                                        <button class="btn btn-info btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Edit" onclick="edit({{$attendance->id}}, '{{$attendance->name}}', {{$attendance->method}}, '{{$attendance->start}}', '{{$attendance->end}}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Delete" onclick="del({{$attendance->id}})">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-info btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Absence Permit" onclick="permit({{$attendance->id}})">
                                            <i class="fas fa-virus"></i>
                                        </button>
                                    @endif
                                    {{-- Data Button --}}
                                    <a href="{{ url('attendance') . '/' . $attendance->id}}" class="btn btn-success btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Get Data">
                                        <i class="fas fa-database"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach()
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@if (Auth::user()->role == "teacher")
    <!-- Add Modal-->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalTitle">Make New Attendance Check</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{url('attendance')}}" method="post">
                    <div class="modal-body">
                        @csrf
                        <input type="hidden" name="class_id" value="{{$classroom->id}}">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter Name" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Method</label>
                            <select name="method" class="form-control @error('method') is-invalid @enderror">
                                <option value="1">Teacher scan QR in student generated Id card</option>
                                <option value="2">Student Scan QR from teacher</option>
                            </select>
                            @error('method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <input type="datetime-local" name="start" class="form-control @error('start') is-invalid @enderror" placeholder="Enter Start Time" required>
                            @error('start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>End Time</label>
                            <input type="datetime-local" name="end" class="form-control @error('end') is-invalid @enderror" placeholder="Enter End Time" required>
                            @error('end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-primary" value="Add New">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Modal-->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalTitle">Edit Attendance Check</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="edit" action="" method="post">
                    <div class="modal-body">
                        @csrf @method("put")
                        <input type="hidden" name="class_id" value="{{$classroom->id}}">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Name" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Method</label>
                            <select name="method" id="method" class="form-control @error('method') is-invalid @enderror">
                                <option value="1">Teacher scan QR in student generated Id card</option>
                                <option value="2">Student Scan QR from teacher</option>
                            </select>
                            @error('method')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Start Time</label>
                            <input type="datetime-local" name="start" class="form-control @error('start') is-invalid @enderror" id="start" placeholder="Enter Start Time" required>
                            @error('start')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>End Time</label>
                            <input type="datetime-local" name="end" class="form-control @error('end') is-invalid @enderror" id="end" placeholder="Enter End Time" required>
                            @error('end')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-primary" value="Save Changes">
                    </div>
                </form>
            </div>
        </div>
    </div>  
    
    <!-- Delete Modal-->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalTitle">Confirm Delete ?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="delete" action="" method="post">
                    <div class="modal-body">
                        @csrf @method("delete")
                        <span>Are You Sure To Delete ?</span>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-danger" value="Delete">
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
    <!-- Permit Modal-->
    <div class="modal fade" id="permitModal" tabindex="-1" role="dialog" aria-labelledby="permitModalTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="permitModalTitle">Absence Permit</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form id="permit" action="" method="post">
                    <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label>Name</label>
                            <div class="form-control">{{Auth::user()->name}}</div>
                        </div>
                        <div class="form-group">
                            <label>Reason</label>
                            <input type="text" name="reason" class="form-control @error('reason') is-invalid @enderror" placeholder="Enter Reason" required>
                            @error('reason')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <input type="submit" class="btn btn-primary" value="Submit">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif
@endsection

@section("js")
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $("#lesson").DataTable();
    $("[data-toggle='popover']").popover();
</script>

@if (Auth::user()->role == "teacher")
<script>
    function add() {
        $("#addModal").modal();
    }
    function edit(id, name, method, start, end) {
        $("#edit").attr("action", "{{ url('attendance') }}" + "/" + id);
        $("#name").val(name);
        $("#method").val(method);
        $("#start").val(start);
        $("#end").val(end);
        $("#editModal").modal();
    }
    function del(id) {
        $("#delete").attr("action", "{{ url('attendance') }}" + "/" + id);
        $("#deleteModal").modal();
    }
</script>
@else
<script>
    function permit(id) {
        $("#permit").attr("action", "{{ url('attendance') }}" + "/" + id + "/permit");
        $("#permitModal").modal();
    }
</script>
@endif
@endsection