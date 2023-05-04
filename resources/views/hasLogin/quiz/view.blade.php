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
    <div id="before" v-if="view == null">
        <div class="container h-100">
                <div class="row h-100 justify-content-center">
                <div class="col col-md-6 my-auto">
                    <div class="card shadow mt-5 mb-4">
                        <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">
                                Quiz {{$quiz->name}}
                            </h6>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <b>Name :</b> <span class="float-right">{{$quiz->name}}</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Duration :</b> <span class="float-right">{{$quiz->duration / 60}} Minutes</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Total Quiz :</b> <span class="float-right">@{{question.length}}</span>
                                </li>
                            </ul>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-info btn-icon-split btn-block" @click="show(0)" :disabled="question.length == 0">
                                <span class="icon text-white-50">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                                <span class="text btn-block" v-if="question.length != 0">Go !!!</span>
                                <span class="text btn-block text-danger" v-else>Waiting for a list of questions</span>
                            </button>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="show" v-else>
        <div id="wrapper">
            <div  class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
                <div class="sidebar-brand d-flex align-items-center justify-content-center">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <i class="fas fa-laugh-wink"></i>
                    </div>
                    <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Dream School') }}</div>
                </div>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">Time</div>
                <div class="nav-item">
                    <h3 id="time" class="text-white text-center text-bold">00:00:00</h3>
                </div>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">Question</div>
                <div class="row m-3">
                    <div class="qlist" v-for="(item, index) in question">
                        <button :class="[answer[index].answer != '' ? 'btn btn-success btn-sm' : index != view ? 'btn btn-secondary btn-sm' : 'btn btn-warning btn-sm']" @click="show(index)">@{{index + 1}}</button>
                    </div>
                </div>
                <hr class="sidebar-divider">
                <div class="sidebar-heading">Submit</div>
                <div class="nav-item">
                    <div class="nav-link">
                        <button class="btn btn-success btn-sm w-100" data-toggle="modal" data-target="#confirmSubmitModal">Submit</button>
                    </div>
                </div>
            </div>
            <div id="content-wrapper" class="d-flex flex-column">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link" role="button" onclick="document.getElementsByTagName('body')[0].requestFullscreen()">
                                <i class="fas fa-expand-arrows-alt"></i>
                            </a>
                        </li>
                        <div class="topbar-divider d-none d-sm-block"></div>
                        <li class="nav-item dropdown no-arrow mx-1">
                            <div class="nav-link">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">{{ Auth::user()->name }}</span>
                                <img class="img-profile rounded-circle"
                                    src="{{ asset('assets/img/undraw_profile.svg') }}">
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col col-md-8 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-sm-flex align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        Q : @{{view + 1}}
                                    </h6>
                                    <div class="">
                                        <button type="button" class="btn btn-sm text-primary" v-if="view != 0" @click="show(view - 1)"><i class="fas fa-play fa-rotate-180"></i></button>
                                        <button type="button" class="btn btn-sm text-primary" v-if="view < question.length - 1" @click="show(view + 1)"><i class="fas fa-play"></i></button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div id="question" class="mb-4" v-html="question[view].question"></div>
                                    <div class="row" id="option">
                                        <div class="col col-6">
                                            <input type="radio" id="option_a" name="select_option" value="a" v-model="option_selected" @change="updateSelect('a')">
                                            <label for="option_a">A. @{{question[view].option_a}}</label>
                                            <input type="radio" id="option_b" name="select_option" value="b" v-model="option_selected" @change="updateSelect('b')">
                                            <label for="option_b">B. @{{question[view].option_b}}</label>
                                        </div>
                                        <div class="col col-6">
                                            <input type="radio" id="option_c" name="select_option" value="c" v-model="option_selected" @change="updateSelect('c')">
                                            <label for="option_c">C. @{{question[view].option_c}}</label>
                                            <input type="radio" id="option_d" name="select_option" value="d" v-model="option_selected" @change="updateSelect('d')">
                                            <label for="option_d">D. @{{question[view].option_d}}</label>
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
    <div class="modal fade" id="confirmSubmitModal" tabindex="-1" role="dialog" aria-labelledby="confirmSubmitLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gradient-success text-white">
                    <h5 class="modal-title" id="confirmSubmitLabel">Confirm Submit</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Submit" below if you are ready to end this quiz.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-success" type="button" @click="submit()">Submit</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="resultModal" tabindex="-1" role="dialog" aria-labelledby="resultLabel"
        aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header bg-gradient-primary">
                    <h5 class="modal-title text-bold" id="resultLabel">Result</h5>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <b>Name :</b> <span class="float-right">@{{result.student}}</span>
                        </li>
                        <li class="list-group-item">
                            <b>True Answer :</b> <span class="float-right">@{{result.answer_true}}</span>
                        </li>
                        <li class="list-group-item">
                            <b>False Answer :</b> <span class="float-right">@{{result.answer_false}}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Not Answered :</b> <span class="float-right">@{{result.not_answered}}</span>
                        </li>
                        <li class="list-group-item">
                            <b>Score :</b> <span class="float-right">@{{result.score}}</span>
                        </li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <a v-bind:href="url_redirect" class="btn btn-primary">Ok</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    const { createApp } = Vue
    var controller = createApp({
        data() {
            return {
                timer: 1000,
                redirect_time: 5,
                view: null,
                option_selected: null,
                question: [],
                answer: [],
                result: {},
                url_q_list: "{{ url('quiz') . '/' . $quiz->id . '/q-list' }}",
                url_submit: "{{ url('quiz') . '/' . $quiz->id }}",
                url_redirect: "{{ url('quiz') . '/' . $quiz->id . '/score' }}",
            }
        },
        mounted: function () {
            const _this = this;
            $.get(_this.url_q_list, function (data) {
                _this.question = data.question;
                _this.timer = data.duration;
                for (const key in data.question) {
                    if (Object.hasOwnProperty.call(data.question, key)) {
                        const item = data.question[key];
                        _this.answer.push({question_id: item.id, answer: ""});
                    }
                };
            });
            _this.countdown();
        },
        methods: {
            countdown() {
                const _this = this;
                var x = setInterval(() => {
                    if (_this.view != null && _this.result != {}) {
                        var timer = _this.timer;
                        var totalMinutes = Math.floor(timer / 60);
                        var seconds = timer % 60;
                        var hours = Math.floor(totalMinutes / 60);
                        var minutes = totalMinutes % 60;
                        var hh = (hours < 10) ? "0" + hours : hours;
                        var mm = (minutes < 10) ? "0" + minutes : minutes;
                        var ss = (seconds < 10) ? "0" + seconds : seconds;
                        $("#time").text(hh + ":" + mm + ":" + ss);
                        _this.timer--;
                    }
                    if (_this.timer == 0) {
                        clearInterval(x);
                        _this.submit();
                    }
                }, 1000);
            },
            show(index) {
                this.view = index;
                this.option_selected = this.answer[index].answer;
            },
            updateSelect(selected) {
                this.answer[this.view].answer = selected;
            },
            submit() {
                const _this = this;
                $.post( _this.url_submit, {
                    "_token": "{{csrf_token()}}",
                    "user": {{Auth::user()->id}},
                    "answer": JSON.stringify(_this.answer)
                }).done(function(datas) {
                    _this.result = datas;
                    $("#confirmSubmitModal").modal("hide");
                    $("#resultModal").modal("show");
                    window.location.href = _this.url_redirect;
                });
            },
        },
    }).mount('#app')
  </script>
@endsection