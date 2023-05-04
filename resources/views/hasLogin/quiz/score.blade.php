@extends("layouts.hasLogin")
@section("css")
<link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" >
@endsection
@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Quiz {{ $quiz->name }} Score</h1>
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-10 mb-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Submit</th>
                                <th>True</th>
                                <th>False</th>
                                <th>Score</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $key => $item)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$item["name"]}}</td>
                                <td>{{$item["submit"]}}</td>
                                <td>{{$item["answer_true"]}}</td>
                                <td>{{$item["answer_false"]}}</td>
                                <td>{{$item["score"]}}</td>
                            </tr>    
                            @endforeach
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
<script src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script>
    table = $("#datatable").DataTable({
        dom: "Bfrtip",
        buttons: [
            {
                extend: "copyHtml5",
                text: "Copy",
                header: "Score quiz {{$quiz->name}} \n {{$quiz->start}}",
                messageTop: "Score quiz {{$quiz->name}} ( {{$quiz->start}} )",
                title: "Score quiz {{$quiz->name}} \n {{$quiz->start}}"
            },
            {
                extend: "excelHtml5",
                text: "Excel",
                filename: "Score quiz {{$quiz->name}}",
                header: "Score quiz {{$quiz->name}} \n {{$quiz->start}}",
                messageTop: "Score quiz {{$quiz->name}} ( {{$quiz->start}} )"
            },
            {
                extend: "pdfHtml5",
                text: "PDF",
                filename: "Score quiz {{$quiz->name}}",
                orientation: "portrait",
                pageSize: "A4",
                title: "Score quiz {{$quiz->name}} \n {{$quiz->start}}"
            },
        ]
    });
</script>
@endsection