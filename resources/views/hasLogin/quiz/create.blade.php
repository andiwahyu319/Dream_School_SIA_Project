@extends("layouts.hasLogin")

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-8 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Create New Quiz
                </h6>
            </div>
            <form action="{{url('quiz')}}" method="post">
                <div class="card-body">
                    @csrf
                    <div class="form-group">
                        <label>Classroom</label>
                        <select name="class_id" id="class_id" class="form-control @error('class_id') is-invalid @enderror">
                            @foreach($classrooms as $key => $classroom)
                            <option value="{{$classroom->id}}" @if ($classroom->id == $class_id) selected @endif>{{$classroom->name}}</option>
                            @endforeach
                        </select>
                        @error('class_id')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
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
                        <label>Duration</label>
                        <div class="input-group">
                            <input type="number" name="duration" class="form-control @error('duration') is-invalid @enderror" id="duration" placeholder="Enter Duration" required>
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
                                <input type="datetime-local" name="start" class="form-control @error('start') is-invalid @enderror" id="start" required>
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
                                <input type="datetime-local" name="end" class="form-control @error('end') is-invalid @enderror" id="start" required>
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
                        <span class="text btn-block">Save & make question</span>
                    </button>
                </div> 
            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $('#body').summernote();
</script>
@endsection