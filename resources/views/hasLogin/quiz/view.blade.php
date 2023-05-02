@extends("layouts.quizCustom")

@section('css')
<style>
    .qlist {
        display: flex;
        justify-content: center;
        margin-bottom: 0.25rem!important;
        flex: 0 0 20%;
        max-width: 20%;
        position: relative;
        width: 100%;
    }
    .qlist button {
        width: 90%
    }
    #option label, #option input {
        display: block;
        border-radius: 0.35rem;
    }
    #option input[type="radio"] {
        opacity: 0.01;
        z-index: 100;
    }

    #option input[type="radio"]:checked+label,
    .Checked+label {
        background: #1cc88a;
        color: #fff;
    }

    #option label {
        padding: 0.375rem 0.75rem;
        border: 1px solid #CCC;
        cursor: pointer;
        z-index: 90;
    }

    #option label:hover {
        background: #DDD;
    }
    @media (min-width: 768px) {
        .sidebar {
            width: 18rem!important;
        }
    }

</style>
@endsection

@section("content")
<div id="app">
    <div id="show">
        <div id="wrapper">
            <div  class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Dream School') }}</div>
                </a>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">Time</div>
                <div class="nav-item">
                    <h3 class="text-white text-center text-bold">00:20:58</h3>
                </div>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">Question</div>
                <div class="row m-3">
                    <div class="qlist">
                        <button class="btn btn-secondary btn-sm">1</button>
                    </div>
                    <div class="qlist">
                        <button class="btn btn-secondary btn-sm">1</button>
                    </div>
                    <div class="qlist">
                        <button class="btn btn-secondary btn-sm">11</button>
                    </div>
                    <div class="qlist">
                        <button class="btn btn-secondary btn-sm">1</button>
                    </div>
                    <div class="qlist">
                        <button class="btn btn-secondary btn-sm">1</button>
                    </div>
                    <div class="qlist">
                        <button class="btn btn-secondary btn-sm">1</button>
                    </div>
                    <div class="qlist">
                        <button class="btn btn-secondary btn-sm">1</button>
                    </div>
                    <div class="qlist">
                        <button class="btn btn-secondary btn-sm">1</button>
                    </div>
                </div>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">Submit</div>
                <div class="nav-item">
                    <div class="nav-link">
                        <button class="btn btn-success btn-sm w-100">Submit</button>
                    </div>
                </div>
            </div>
            <div id="content-wrapper" class="d-flex flex-column">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                </nav>
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col col-md-8 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-body">
                                    <div id="question" class="mb-4">
                                        Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum inventore maiores numquam? Aut ab, sunt quibusdam amet eaque, placeat voluptatum ipsam, minus pariatur omnis necessitatibus maiores ad magni deleniti voluptas.
                                    </div>
                                    <div class="row" id="option">
                                        <div class="col col-6">
                                            <input type="radio" id="option_a" name="select_option">
                                            <label for="option_a">lorem</label>
                                            <input type="radio" id="option_b" name="select_option">
                                            <label for="option_b">lorem</label>
                                        </div>
                                        <div class="col col-6">
                                            <input type="radio" id="option_c" name="select_option">
                                            <label for="option_c">lorem</label>
                                            <input type="radio" id="option_d" name="select_option">
                                            <label for="option_d">lorem</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection