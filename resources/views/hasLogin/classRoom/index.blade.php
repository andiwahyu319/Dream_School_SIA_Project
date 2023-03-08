@extends("layouts.hasLogin")

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">My Class</h1>
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col-md-4 mb-4">
        <div class="card shadow mb-4 h-100">
            @if(Auth::user()->role == "teacher")
            <form action="{{url('classroom')}}" method="post">
                <div class="card-body">
                    <h3 class="h4text-gray-800">Create New Class</h3>
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Enter Classroom Name" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" id="description"
                            placeholder="Enter Classroom Description" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-icon-split btn-block">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text btn-block">Make New Classroom</span>
                    </button>
                </div>
            </form>
            @else
            <div class="card-body">
                <h3 class="h4text-gray-800">Join New Class</h3>
                <p>join new class using invitation code</p>
                <input type="text" id="invitation" class="form-control @if(Session::has('fail')) is-invalid @enderror" placeholder="Invitation Code" onchange="invitation()">
                @if (Session::has('fail'))
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ Session::get('fail') }}</strong>
                    </span>
                @endif
            </div>
            <div class="card-footer">
                <a id="go_invitation" class="btn btn-info btn-icon-split btn-block">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                    <span class="text btn-block">Go To Class</span>
                </a>
            </div>
            @endif
        </div>
    </div>
    @foreach($classrooms as $key => $classroom)
    <div class="col-md-4 mb-4">
        <div class="card shadow mb-4 h-100">
            <div class="card-body">
                <h3 class="h4text-gray-800">{{$classroom->name}}</h3>
                <p>{{$classroom->description}}</p>
            </div>
            <div class="card-footer">
                <a href="{{ url('classroom') . '/' . $classroom->id}}" class="btn btn-primary btn-icon-split btn-block">
                    <span class="icon text-white-50">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                    <span class="text btn-block">Go To Class</span>
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

@if(Auth::user()->role != "teacher")
@section("js")
<script>
    function invitation() {
        $("#go_invitation").attr("href", "{{url('/invitation')}}" + "/" + $("#invitation").val());
    }
</script>
@endsection
@endif