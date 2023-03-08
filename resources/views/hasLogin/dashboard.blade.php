@extends("layouts.hasLogin")

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    @if(count($posts) == 0)
    <p class="text-danger">Nothing here, please join classroom dan posted in there</p>
    @endif
    <div class="col-md-8 mb-4">
        @foreach($posts as $key => $post)
        <div class="card shadow mb-4">
            <div class="card-header py-3 @if($post->user_id == Auth::user()->id) d-flex flex-row align-items-center justify-content-between @endif">
                <div class="col">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{$post->user->name}} <i class="fas fa-fw fa-play"></i> {{$post->classroom->name}}
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
                            <input type="text" class="form-control" placeholder="Add Comment">
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
