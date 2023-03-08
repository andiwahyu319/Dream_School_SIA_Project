@extends("layouts.hasLogin")

@section("css")
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
@endsection

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">{{$room->name}}</h1>
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Class Detail
                </h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <span class="text-gray-800">Class Name :</span><br>
                        {{$room->name}}
                    </li>
                    <li class="list-group-item">
                        <span class="text-gray-800">Class Description :</span><br>
                        {{$room->description}}
                    </li>
                    <li class="list-group-item">
                        <span class="text-gray-800">Teacher Name :</span><br>
                        {{$room->teacherData->name}}
                    </li>
                    @if(Auth::user()->role == "teacher")
                    <li class="list-group-item">
                        <span class="text-gray-800">Is Private :</span><br>
                        {{($room->private) ? "True" : "False"}}
                    </li>
                    <li class="list-group-item">
                        <span class="text-gray-800">Invitation Link :</span><br>
                        {{$room->invitation_code}}<br>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-info" onclick="invitecopy('{{$room->invitation_code}}', 'code')">Copy Code</button>
                            <button type="button" class="btn btn-primary" onclick="invitecopy('{{$room->invitation_code}}', 'link')">Copy Link</button>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="card shadow mb-4">
            <a href="#member" class="card-header py-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="member">
                <h6 class="m-0 font-weight-bold text-primary">
                    Class Members ( {{count($room->member)}} )
                </h6>
            </a>
            <div class="collapse" id="member">
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach($room->member as $key => $member)
                        <li class="list-group-item">
                            {{$member->name}}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="card-footer justify-content-end">
                <a href="{{url('classroom') . '/' . $room->id . '/member'}}" class="btn btn-success btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-users"></i>
                    </span>
                    <span class="text">View Member Detail</span>
                </a>
            </div>
        </div>
        @if(Auth::user()->role == "teacher")
        <div class="card shadow mb-4">
            <form action="{{url('classroom') . '/' . $room->id}}" method="post">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Edit Class 
                    </h6>
                </div>
                <div class="card-body">
                    <input type="hidden" name="_method" value="PUT">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control" id="name" value="{{$room->name}}"
                                placeholder="Enter Classroom Name" required>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <input type="text" name="description" class="form-control" id="description" value="{{$room->description}}"
                            placeholder="Enter Classroom Description" required>
                    </div>
                    <div class="form-group">
                        <label>private</label>
                        <select name="private" id="private"  class="form-control">
                            <option value="0">False</option>
                            <option value="1">True</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" value="Update Classroom">
                </div>
            </form>
        </div>
        @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Out This Class
                </h6>
            </div>
            <div class="card-body">
                <a href="{{url('classroom') . '/' . $room->id . '/out'}}" class="btn btn-danger btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-door-open"></i>
                    </span>
                    <span class="text">Out This Class</span>
                </a>
            </div>
        </div>
        @endif
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow mb-4">
            <form action="{{url('post')}}" method="post">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        New Post
                    </h6>
                </div>
                <div class="card-body p-0">
                    @csrf
                    <input type="hidden" name="class_id" value="{{$room->id}}">
                    <textarea name="body" id="body"></textarea>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" value="Send">
                </div>
            </form>
        </div>
        @foreach($posts as $key => $post)
        <div class="card shadow mb-4">
            <div class="card-header py-3 @if($post->user_id == Auth::user()->id) d-flex flex-row align-items-center justify-content-between @endif">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{$post->user->name}}
                    </h6>
                    <span class="text-gray-600 text-xs">{{$post->created_at->diffForHumans()}}</span>
                </div>
                @if($post->user_id == Auth::user()->id)
                <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in"
                        aria-labelledby="dropdownMenuLink">
                        <form action="{{url('post' . '/' . $post->id)}}" method="post">
                            @csrf
                            <input type="hidden" name="_method" value="delete">
                            <input type="submit" class="dropdown-item" value="Delete" onclick="return confirm('are you sure?')">
                        </form>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-body">
                {!! $post->body !!}
            </div>
            <div class="card-footer p-0">
                <ul class="list-group list-group-flush">
                    <form action="{{url('comment')}}" method="post" class="list-group-item">
                        Comments:
                        <input type="hidden" name="post_id" value="{{$post->id}}">
                        @csrf
                        <div class="input-group">
                            <input type="text" class="form-control" name="body" placeholder="Add Comment">
                            <div class="input-group-append">
                                <input type="submit" value="Send" class="btn btn-primary">
                            </div>
                        </div>
                    </form>
                    @foreach($post->comment as $key => $comment)
                    <li class="list-group-item">
                        <span class="text-primary font-weight-bold">{{$comment->user->name}}</span>
                        {{$comment->body}}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endforeach
        @if ($posts->hasPages())
        <div class="">
            {{ $posts->links("pagination::bootstrap-4") }}
        </div>
        @endif
    </div>
</div>
@endsection

@section("js")
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        var text = $('#body');
        text.summernote();
    });
</script>
@if(Auth::user()->role == "teacher")
<script>
    function invitecopy(copyText, tipe) {
        if (tipe == "code") {
            navigator.clipboard.writeText(copyText);
            alert("Copied the text: " + copyText);
        } else {
            link = "{{url('invitiation')}}" + "/" + copyText;
            navigator.clipboard.writeText(link);
            alert("Copied the text: " + link);
        }
    }
</script>
@endif
@endsection