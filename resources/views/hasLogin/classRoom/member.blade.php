@extends("layouts.hasLogin")
@section("css")
<link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
@endsection
@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Members of {{$room->name}}</h1>
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-10 mb-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="member" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Birthdate</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($room->member as $key => $people)
                            <tr>
                                <td>{{$key + 1}}.</td>
                                <td>{{$people->name}}</td>
                                <td>{{$people->gender}}</td>
                                <td>{{$people->birthdate}}</td>
                                <td>{{$people->address}}</td>
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
    $("#member").DataTable();
</script>
@endsection