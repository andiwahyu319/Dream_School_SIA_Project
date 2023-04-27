@extends("layouts.hasLogin")

@section("css")
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection

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
                    Create New Lesson
                </h6>
            </div>
            <form action="{{url('lesson')}}" method="post">
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
                        <label>Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" placeholder="Enter Title" required>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="body" class="form-control @error('body') is-invalid @enderror" id="body"></textarea>
                        @error('body')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-icon-split btn-block">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text btn-block">Save</span>
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