@extends("layouts.hasLogin")

@section("content")
<div aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('classroom') . '/' . $quiz->class_id}}"><i class="fas fa-fw fa-chalkboard"></i> {{$classname}}</a></li>
        <li class="breadcrumb-item"><a href="{{url('classroom') . '/' . $quiz->class_id . '/quiz'}}"> <i class="fas fa-fw fa-list"></i> Quiz</a></li>
        <li class="breadcrumb-item"><a href="#"><i class="fas fa-fw fa-question"></i> {{$quiz->name}}</a></li>
        <li class="breadcrumb-item active" aria-current="page"><i class="fas fa-fw fa-edit"></i> Edit</li>
    </ol>
</div>

<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-3 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-fw fa-question"></i> {{$quiz->name}}
                </h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{url('quiz') . '/' . $quiz->id . '/edit'}}"><i class="fas fa-edit"></i> Edit Quiz information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('quiz') . '/' . $quiz->id . '/q/edit'}}"><i class="fas fa-edit"></i> Add Question</a>
                    </li>
                    @foreach ($questions as $key => $item)
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('quiz') . '/' . $quiz->id . '/' . $item->id . '/edit'}}"><i class="fas fa-edit"></i> {{'Edit #Q' . $key + 1}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="col col-md-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Edit Quiz information
                </h6>
            </div>
            <form action="{{url('quiz') . '/' . $quiz->id}}" method="post">
                <div class="card-body">
                    @if (Session::has("success"))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ Session::get("success") }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                    @csrf @method("put")
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" placeholder="Enter Name" value="{{$quiz->name}}" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Duration</label>
                        <div class="input-group">
                            <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" id="duration" placeholder="Enter Duration" value="{{$quiz->duration / 60}}" required>
                            <div class="input-group-append">
                                <span class="input-group-text">Minutes</span>
                            </div>
                        </div>
                        @error('duration')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col col-md-6">
                            <div class="form-group">
                                <label>Quiz Opened</label>
                                <input type="datetime-local" name="start" class="form-control @error('start') is-invalid @enderror" id="start" value="{{$quiz->start}}" required>
                                @error('start')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="form-group">
                                <label>Quiz Closed</label>
                                <input type="datetime-local" name="end" class="form-control @error('end') is-invalid @enderror" id="end" value="{{$quiz->end}}" required>
                                @error('end')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-icon-split btn-block">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text btn-block">Update</span>
                    </button>
                </div> 
            </form>
        </div>
    </div>
</div>
@endsection