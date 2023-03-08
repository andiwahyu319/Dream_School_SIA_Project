@extends("layouts.hasLogin")

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Absence Permit</h1>
</div>
<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-6">
        <div class="card shadow mb-4">
            <form action="" method="post">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Absence Permit
                    </h6>
                </div>
                <div class="card-body">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        <div class="form-control">{{Auth::user()->name}}</div>
                    </div>
                    <div class="form-group">
                        <label>Reason</label>
                        <input type="text" name="reason" class="form-control" id="reason" placeholder="Enter Reason" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-info btn-icon-split btn-block">
                        <span class="icon text-white-50">
                            <i class="fas fa-arrow-right"></i>
                        </span>
                        <span class="text btn-block">Permit</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection