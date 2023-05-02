@extends("layouts.hasLogin")

@section("css")
<link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Quiz in {{$classname}}</h1>
    @if(Auth::user()->role == "teacher")
    <a href="{{ url('quiz') . '/create'}}" class="btn btn-primary">Make New</a>
    @endif
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-10 mb-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="lesson" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Duration</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($quizzes as $key => $quiz)
                            <tr>
                                <td>{{$key + 1}}.</td>
                                <td>{{$quiz->name}}</td>
                                <td>
                                    @if (strtotime(date("Y-m-d H:i:s")) < strtotime($quiz->start))
                                        <span class="text-warning" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="Start : {{$quiz->start}} <br>End : {{$quiz->end}}">Upcoming</span>
                                    @elseif((strtotime(date("Y-m-d H:i:s")) > strtotime($quiz->start)) and (strtotime(date("Y-m-d H:i:s")) < strtotime($quiz->end)))
                                        <span class="text-success" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="Start : {{$quiz->start}} <br>End : {{$quiz->end}}">Now</span>
                                    @elseif(strtotime(date("Y-m-d H:i:s")) > strtotime($quiz->end))
                                        <span class="text-danger" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="right" data-html="true" data-content="Start : {{$quiz->start}} <br>End : {{$quiz->end}}">Ended</span>
                                    @endif
                                </td>
                                <td>{{$quiz->duration / 60}} Minutes</td>
                                <td>
                                    @if (Auth::user()->id == $quiz->teacher)
                                        <a href="{{ url('quiz') . '/' . $quiz->id . '/edit'}}" class="btn btn-info btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ url('quiz') . '/' . $quiz->id }}" method="post" class="btn p-0">
                                            @csrf @method("delete")
                                            <button type="submit" class="btn btn-danger btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @else
                                    <a href="{{url('quiz') . '/' . $quiz->id}}" class="btn btn-primary btn-circle btn-sm" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="bottom" data-content="Go">
                                        <i class="fas fa-book"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach()
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section("js")
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
<script>
    $("#lesson").DataTable();
    $("[data-toggle='popover']").popover();
</script>
@endsection