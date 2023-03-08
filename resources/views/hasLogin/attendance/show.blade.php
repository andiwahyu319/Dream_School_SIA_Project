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
            <div class="card-body">
                <img src="https://chart.googleapis.com/chart?cht=qr&chs=300x300&data={{ $attendance->key }}" class="card-img" alt="Qr Code">
            </div>
        </div>
    </div>
</div>
@endsection