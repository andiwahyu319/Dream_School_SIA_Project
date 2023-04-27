@extends("layouts.hasLogin")

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">QR</h1>
</div>
<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{$attendance->name}} QR
                </h6>
                <a class="text-secondary" role="button" onclick="fullscreenqr()">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </div>
            <div class="card-body p-0">
                <img id="qr" src="https://chart.googleapis.com/chart?cht=qr&chs=300x300&data={{ $attendance->key }}" class="card-img" alt="Qr Code">
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function fullscreenqr() {
        document.getElementById("qr").requestFullscreen()
    }
</script>
@endsection