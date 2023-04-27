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
                    {{$lesson->title}}
                </h6>
            </div>
            <div class="card-body">
                {!! $lesson->body !!}
            </div>
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
                        <a class="nav-link @if($lesson->id == $item->id) active @endif" href="{{url('lesson') . '/' . $item->id}}"><i class="fas fa-book"></i> {{$item->title}}</a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection