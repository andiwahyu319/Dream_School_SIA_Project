@extends("layouts.hasLogin")

@section("css")
<style>
    #square {
        width: 100%;
        padding-top: 100%;
        height: 0px;
        position: relative;
    }
    #preview {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }
</style>
@endsection

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Scan QR</h1>
</div>
<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-6">
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="card-img" id="preview"></div>
            </div>
        </div>
    </div>
</div>

<!-- Result Modal-->
<div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="dataModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dataModal">Result</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body p-0">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <b>Name :</b> <span id="name" class="float-right"></span>
                    </li>
                    <li class="list-group-item">
                        <b>Status :</b> <span id="status" class="float-right"></span>
                    </li>
                    <li class="list-group-item">
                        <b>Time :</b> <span id="time" class="float-right"></span>
                    </li>
                    <li class="list-group-item">
                        <b>Late :</b> <span id="late" class="float-right"></span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section("js")
<script src="https://unpkg.com/html5-qrcode"></script>
<script type="text/javascript">
    function onScanSuccess(decodedText, decodedResult) {
        $.post( window.location.href, {
            "_token": "{{csrf_token()}}",
            "data": decodedResult.decodedText,
        }).done(function(datas) {
            data = jQuery.parseJSON(JSON.stringify(datas));
            $("#name").text(data["name"]);
            $("#status").text(data.status);
            $("#time").text(data.time);
            $("#late").text(data.late);
            $("#resultModal").modal();
        });
    }
    let config = { 
        fps: 10,
        qrbox : { width: 250, height: 250 },
        aspectRatio: 1.0,
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA],
        formatsToSupport: [ Html5QrcodeSupportedFormats.QR_CODE ]
     }
    var html5QrcodeScanner = new Html5QrcodeScanner("preview", config);
    html5QrcodeScanner.render(onScanSuccess);
</script>
@endsection