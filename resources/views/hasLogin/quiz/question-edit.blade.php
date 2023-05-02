@extends("layouts.hasLogin")

@section("css")
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection

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
                        <a class="nav-link" href="{{url('quiz') . '/' . $quiz->id . '/edit'}}"><i class="fas fa-edit"></i> Edit Quiz information</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{url('quiz') . '/' . $quiz->id . '/q/edit'}}"><i class="fas fa-edit"></i> Add Question</a>
                    </li>
                    @foreach ($questions as $key => $item)
                    <li class="nav-item">
                        <a class="nav-link @if($question->id == $item->id) active @endif" href="{{url('quiz') . '/' . $quiz->id . '/' . $item->id . '/edit'}}"><i class="fas fa-edit"></i> {{'Edit #Q' . $key + 1}}</a>
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
                    Edit Question
                </h6>
            </div>
            <form action="" method="post">
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
                        <label>Question</label>
                        <textarea name="question" class="form-control @error('question') is-invalid @enderror" id="question" placeholder="Enter Question" required>
                            {!! $question->question !!}
                        </textarea>
                        @error('question')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">A.</span>
                                    </div>
                                    <input type="text" name="option_a" class="form-control @error('option_a') is-invalid @enderror" id="option_a" placeholder="Enter Option A" value="{{$question->option_a}}" onchange="updateTrueOption('a', value)" required>
                                </div>
                                @error('option_a')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">B.</span>
                                    </div>
                                    <input type="text" name="option_b" class="form-control @error('option_b') is-invalid @enderror" id="option_b" placeholder="Enter Option B" value="{{$question->option_b}}" onchange="updateTrueOption('b', value)" required>
                                </div>
                                @error('option_b')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">C.</span>
                                    </div>
                                    <input type="text" name="option_c" class="form-control @error('option_c') is-invalid @enderror" id="option_c" placeholder="Enter Option C" value="{{$question->option_c}}" onchange="updateTrueOption('c', value)" required>
                                </div>
                                @error('option_c')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">D.</span>
                                    </div>
                                    <input type="text" name="option_d" class="form-control @error('option_d') is-invalid @enderror" id="option_d" placeholder="Enter Option D" value="{{$question->option_d}}" onchange="updateTrueOption('d', value)" required>
                                </div>
                                @error('option_d')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>True Answer</label>
                        <select name="true_answer" id="true_answer" class="form-control @error('true_answer') is-invalid @enderror" required>
                            <option value="a" @if($question->true_answer == 'a') selected @endif>A. {{$question->option_a}}</option>
                            <option value="b" @if($question->true_answer == 'b') selected @endif>B. {{$question->option_b}}</option>
                            <option value="c" @if($question->true_answer == 'c') selected @endif>C. {{$question->option_c}}</option>
                            <option value="d" @if($question->true_answer == 'd') selected @endif>D. {{$question->option_d}}</option>
                        </select>
                        @error('true_answer')
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
</div>
@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $('#question').summernote({
        inheritPlaceholder: true
    });
    function updateTrueOption(option, value) {
        $("#true_answer option[value='" + option.toLowerCase() + "']").text(option.toUpperCase() + ". " + value)
    }
</script>
@endsection