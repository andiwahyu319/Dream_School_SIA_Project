@extends("layouts.hasLogin")

@section("content")
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">My Account</h1>
</div>
<!-- Page Content -->
<div class="row justify-content-center">
    <div class="col col-md-4 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    Generated Id Card
                </h6>
            </div>
            <div class="card-body">
                <canvas id="idcard" width="300px" height="150px" style="border:1px solid #5a5c69"></canvas>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-primary btn-icon-split" onclick="idcarddl()">
                    <span class="icon text-white-50">
                        <i class="fas fa-download"></i>
                    </span>
                    <span class="text">Download</span>
                </button>
            </div>
        </div>
    </div>
    <div class="col col-md-6 mb-4">
        <div class="card shadow mb-4">
            <form action="{{url('/account')}}" method="post">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Edit Account
                    </h6>
                </div>
                <div class="card-body">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="name" value="{{$user->name}}"
                            placeholder="Enter Name" required>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{$user->email}}"
                            placeholder="Enter Email" required>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Birthdate</label>
                        <input type="date" name="birthdate" class="form-control @error('birthdate') is-invalid @enderror" id="birthdate" value="{{$user->birthdate}}"
                            placeholder="Enter Birthdate" required>
                        @error('birthdate')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" id="address" value="{{$user->address}}"
                            placeholder="Enter Address" required>
                        @error('address')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" value="Update Account">
                </div>
            </form>
        </div>
        <div class="card shadow mb-4">
            <form action="{{url('/account')}}" method="post">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        Edit Password
                    </h6>
                </div>
                @csrf
                <div class="card-body">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="form-group">
                        <label>Old Password</label>
                        <input type="password" name="old_password" class="form-control @error('old_password') is-invalid @enderror" id="old_password"
                            placeholder="Enter Old Password" required>
                        @error('old_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror" id="new_password"
                            placeholder="Enter New Password" required>
                        @error('new_password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Confirm New Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" id="new_password_confirmation"
                            placeholder="Confirm New Password" required>
                    </div>
                </div>
                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" value="Update Password">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section("js")
<script>
    var canvas = document.getElementById('idcard');
    var ctx = canvas.getContext('2d');
    var identity = {
        x : [60, 72, 84, 96, 108],
        y : [100, 145, 150]
    }

    ctx.font = "bold 20px Nunito";
    ctx.textAlign = "center"
    ctx.fillStyle = "#4e73df";
    ctx.fillText("{{ config('app.name', 'Dream School') }}", 150, 25);
    ctx.stroke();

    ctx.strokeStyle  = "#4e73df";
    ctx.moveTo(5, 40);
    ctx.lineTo(295, 40);
    ctx.stroke();

    var img = new Image;
    img.onload = function(){
        ctx.drawImage(img, 5, 45);
    };
    var url = "https://chart.googleapis.com/chart?cht=qr&chs=90x90&margin=0&data=" + "user" + "{{$user->id}}";
    img.src = url;

    ctx.font = "bold 10px Nunito";
    ctx.textAlign = "start"
    ctx.fillStyle = "#5a5c69";
    ctx.fillText("Name", identity.y[0], identity.x[0]);
    ctx.fillText("Email", identity.y[0], identity.x[1]);
    ctx.fillText("Gender", identity.y[0], identity.x[2]);
    ctx.fillText("Birthdate", identity.y[0], identity.x[3]);
    ctx.fillText("Address", identity.y[0], identity.x[4]);
    ctx.stroke();

    ctx.font = "bold 10px Nunito";
    ctx.textAlign = "start"
    ctx.fillStyle = "#5a5c69";
    for (let i = 0; i < 5; i++) {
        ctx.fillText(":", identity.y[1], identity.x[i]);
    }
    ctx.stroke();

    ctx.font = "10px Nunito";
    ctx.textAlign = "start"
    ctx.fillStyle = "#858796";
    ctx.fillText("{{$user->name}}", identity.y[2], identity.x[0]);
    ctx.fillText("{{$user->email}}", identity.y[2], identity.x[1]);
    ctx.fillText("{{$user->gender}}", identity.y[2], identity.x[2]);
    ctx.fillText("{{$user->birthdate}}", identity.y[2], identity.x[3]);
    ctx.fillText("{{$user->address}}", identity.y[2], identity.x[4]);
    ctx.stroke();

    //===============

    function idcarddl() {
        var canvas = document.getElementById("idcard");
        image = canvas.toDataURL("image/png", 1.0).replace("image/png", "image/octet-stream");
        var link = document.createElement('a');
        link.download = "id-card.png";
        link.href = image;
        link.click();
    }
</script>
@endsection