@extends('template')

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        <div class="mb-4 text-center">
                            <img src="{{ asset('image/logo/logo_ung.png') }}" class="logo-size-medium">
                        </div>
                        <h4 class="text-center mb-5">Sistem Pengelolaan Administrasi Kemahasiswaan</h4>
                        {{ Form::open(['url'=>'admin/login','class'=>'pt-3']) }}
                        <div class="form-group">
                            {{ Form::label('username','Username') }}
                            {{ Form::text('username',(Session::has('username')) ? Session::get('username') : null,['class'=>'form-control form-control-lg','placeholder'=>'Username','id'=>'username']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('password','Password') }}
                            {{ Form::password('password',['class'=>'form-control form-control-lg','placeholder'=>'Password','id'=>'password']) }}
                        </div>
                        <div class="mt-3">
                            {{ Form::submit('Login',['class'=>'btn btn-block btn-gradient-info btn-lg font-weight-medium auth-form-btn']) }}
                        </div>
                        <div class="my-2 d-flex justify-content-between align-items-center">
                            <div class="form-check"></div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection