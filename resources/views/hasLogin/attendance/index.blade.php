@extends("layouts.hasLogin")

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Attendance</h1>
    @if(Auth::user()->role == "teacher")
    <a href="{{ url('attendance') . '/create'}}" class="btn btn-primary">Make New</a>
    @endif
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    @foreach($attendances as $key => $attendance)
    <div class="col-md-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{$attendance->name}}
                </h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        Start : {{$attendance->start}}<br>
                        End : {{$attendance->end}}
                    </li>
                    @if($attendance->method == 1)
                    <li class="list-group-item">
                        Method : Teacher scan QR in student generated Id card
                    </li>
                    <li class="list-group-item">
                        @if(Auth::user()->id == $attendance->teacher)
                        <a href="{{ url('attendance') . '/' . $attendance->id . '/scan' }}" class="btn btn-primary btn-icon-split btn-block">
                            <span class="icon text-white-50">
                                <i class="fas fa-qrcode"></i>
                            </span>
                            <span class="text btn-block">Scan QR</span>
                        </a>
                        @else
                        <a href="{{ url('account') }}" class="btn btn-primary btn-icon-split btn-block">
                            <span class="icon text-white-50">
                                <i class="fas fa-user"></i>
                            </span>
                            <span class="text btn-block">Get Generated Id Card</span>
                        </a>
                        @endif
                    </li>
                    @else
                    <li class="list-group-item">
                        Method : Student Scan QR from teacher
                    </li>
                    <li class="list-group-item">
                        @if(Auth::user()->id == $attendance->teacher)
                        <a href="{{ url('attendance') . '/' . $attendance->id . '/scan' }}" class="btn btn-primary btn-icon-split btn-block">
                            <span class="icon text-white-50">
                                <i class="fas fa-qrcode"></i>
                            </span>
                            <span class="text btn-block">Show QR</span>
                        </a>
                        @else
                        <a href="{{ url('attendance') . '/' . $attendance->id . '/scan' }}" class="btn btn-primary btn-icon-split btn-block">
                            <span class="icon text-white-50">
                                <i class="fas fa-qrcode"></i>
                            </span>
                            <span class="text btn-block">Scan QR</span>
                        </a>
                        @endif
                    </li>
                    @endif
                    @if(Auth::user()->id == $attendance->teacher)
                    <li class="list-group-item">
                        <div class="btn-group btn-block" role="group">
                            <a href="{{ url('attendance') . '/' . $attendance->id . '/edit'}}" class="btn btn-info btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-edit"></i>
                                </span>
                                <span class="text btn-block">Edit</span>
                            </a>
                            <form action="{{ url('attendance') . '/' . $attendance->id }}" method="post">
                                @csrf @method("delete")
                                <button type="submit" class="btn btn-danger btn-icon-split">
                                    <span class="icon text-white-50">
                                        <i class="fas fa-trash"></i>
                                    </span>
                                    <span class="text">Delete</span>
                                </button>
                            </form>
                        </div>
                    </li>
                    @else
                    <li class="list-group-item">
                        <a href="{{ url('attendance') . '/' . $attendance->id . '/permit' }}" class="btn btn-info btn-icon-split btn-block">
                            <span class="icon text-white-50">
                                <i class="fas fa-virus"></i>
                            </span>
                            <span class="text btn-block">Permit</span>
                        </a>
                    </li>
                    @endif
                    <li class="list-group-item">
                        <a href="{{ url('attendance') . '/' . $attendance->id }}" class="btn btn-success btn-icon-split btn-block">
                            <span class="icon text-white-50">
                                <i class="fas fa-database"></i>
                            </span>
                            <span class="text btn-block">Get Data</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
