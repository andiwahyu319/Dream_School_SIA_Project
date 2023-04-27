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
                    Edit
                </h6>
            </div>
            <form action="{{url('lesson') . '/' . $lesson->id}}" method="post">
                <div class="card-body">
                    @csrf @method("put")
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ $lesson->title }}" placeholder="Enter Title" required>
                        @error('title')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Content</label>
                        <textarea name="body" class="form-control @error('body') is-invalid @enderror" id="body">
                            {!! $lesson->body !!}
                        </textarea>
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
                        <span class="text btn-block">Update</span>
                    </button>
                </div> 
            </form>
        </div>
    </div>
    <div class="col col-md-3 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <a href="{{url('classroom') . '/' . $lesson->class_id . '/lesson'}}"><i class="fas fa-fw fa-chalkboard"></i> {{$classname}}</a>
                </h6>
            </div>
            <div class="card-body">
                <ul class="nav nav-pills flex-column">
                    @foreach ($lessons as $item)
                    <li class="nav-item">
                        <a class="nav-link @if($lesson->id == $item->id) active @endif" href="{{url('lesson') . '/' . $item->id . '/edit'}}"><i class="fas fa-book"></i> {{$item->title}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
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