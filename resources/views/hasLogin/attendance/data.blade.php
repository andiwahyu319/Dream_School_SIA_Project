@extends("layouts.hasLogin")
@section("css")
<link href="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet" >
@endsection
@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Attendance {{$attendance->name}} data</h1>
</div>

<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-10 mb-4">
        <div class="card shadow mb-4">
            <div class="card-body">
                <b>Attend :</b> <span id="count-attend"></span><br>
                <b>Absence :</b> <span id="count-absence"></span><br>
                <b>Not Recorded :</b> <span id="count-not-recorded"></span>
                <hr>
                <div class="progress mb-3">
                    <div id="progress-attend" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    <div id="progress-absence" class="progress-bar bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <hr>
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 10px">#</th>
                                <th>Name</th>
                                <th>Status</th>
                                <th>Reason</th>
                                <th>Time</th>
                                <th>Late</th>
                            </tr>
                        </thead>
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
    var columns = [
        {render: function (index, row, data, meta) {
                return meta.row + 1
            }, orderable: true},
        {data: "name", orderable: true},
        {data: "status", render: function (data, type) {
                if (data) {
                    return "✔️"
                } else {
                    return "❌"
                }
            }, orderable: false},
        {data: "reason", orderable: false},
        {data: "time", orderable: true},
        {data: "late", orderable: false},
    ];
    table = $("#datatable").DataTable({
        ajax: {
            url: "{{ url('attendance') . '/' . $attendance->id . '/data' }}"
        },
        columns: columns,
        dom: "Bfrtip",
        buttons: [
            {
                extend: "copyHtml5",
                text: "Coppy",
                header: "Attendance {{$attendance->name}} Data \n {{$attendance->end}}",
                messageTop: "Attendance {{$attendance->name}} Data ( {{$attendance->end}} )",
                title: "Attendance {{$attendance->name}} Data \n {{$attendance->end}}"
            },
            {
                extend: "excelHtml5",
                text: "Excel",
                filename: "Attendance {{$attendance->name}} Data",
                header: "Attendance {{$attendance->name}} Data \n {{$attendance->end}}",
                messageTop: "Attendance {{$attendance->name}} Data ( {{$attendance->end}} )"
            },
            {
                extend: "pdfHtml5",
                text: "PDF",
                filename: "Attendance {{$attendance->name}} Data",
                orientation: "portrait",
                pageSize: "A4",
                title: "Attendance {{$attendance->name}} Data \n {{$attendance->end}}"
            },
        ]
    }).on("xhr", function () {
        var datas = table.ajax.json();
        $("#count-attend").text(datas["count"]["attend"]);
        $("#count-absence").text(datas["count"]["absence"]);
        $("#count-not-recorded").text(datas["count"]["not recorded"]);

        $("#progress-attend").attr("style", "width: " + datas["percentage"]["attend"] + "%;");
        $("#progress-attend").attr("aria-valuenow", datas["percentage"]["attend"]);
        $("#progress-absence").attr("style", "width: " + datas["percentage"]["absence"] + "%;");
        $("#progress-absence").attr("aria-valuenow", datas["percentage"]["absence"]);
    });

    setInterval(function () {
        table.ajax.reload().on("xhr", function () {
            var datas = table.ajax.json();
            $("#count-attend").text(datas["count"]["attend"]);
            $("#count-absence").text(datas["count"]["absence"]);
            $("#count-not-recorded").text(datas["count"]["not recorded"]);

            $("#progress-attend").attr("style", "width: " + datas["percentage"]["attend"] + "%;");
            $("#progress-attend").attr("aria-valuenow", datas["percentage"]["attend"]);
            $("#progress-absence").attr("style", "width: " + datas["percentage"]["absence"] + "%;");
            $("#progress-absence").attr("aria-valuenow", datas["percentage"]["absence"]);
        });
    }, 15000);
</script>
@endsection