@extends('template')

@section('content')
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row flex-grow">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                        <div class="text-center mb-4">
                            <img src="{{ asset('image/logo/logo_ung.png') }}" class="logo-size-medium">
                        </div>
                        <h4 class="text-center mb-5">Sistem Pengelolaan Administrasi Kemahasiswaan</h4>
                        {{ Form::open(['url'=>'login','class'=>'pt-3']) }}
                        @csrf
                        <div class="form-group">
                            {{ Form::label('jenis-user','Jenis User') }}
                            {{ Form::select('jenis_user',['mahasiswa'=>'Mahasiswa','operator'=>'Operator','pegawai'=>'Pegawai','pimpinan'=>'Pimpinan'],Session::get('jenis_user_login'),['class'=>'form-control form-control-lg','id'=>'jenis-user']) }}
                        </div>
                        <div class="form-group">
                            {{ Form::label('username','Username') }}
                             @php
                                 $jenisUser = Session::get('jenis_user_login');
                                 $placeholder = 'NIM';
                                 switch($jenisUser){
                                     case 'operator':
                                        $placeholder = 'Username' ;
                                        break;
                                    case 'pegawai' :
                                        $placeholder = 'NIP' ;
                                        break;
                                    case 'pimpinan' :
                                        $placeholder = 'NIP' ;
                                        break;
                                }
                             @endphp
                            @if ($errors->any())
                            @if ($errors->has('username'))
                             {{ Form::text('username',(Session::has('username')) ? Session::get('username') : null,['class'=>'is-invalid form-control form-control-lg','placeholder'=>$placeholder,'id'=>'username']) }}
                            <div class="invalid-feedback">{{ $errors->first('username') }}</div>
                            @else
                             {{ Form::text('username',(Session::has('username')) ? Session::get('username') : null,['class'=>'form-control form-control-lg','placeholder'=>$placeholder,'id'=>'username']) }}
                            @endif
                            @else
                             {{ Form::text('username',(Session::has('username')) ? Session::get('username') : null,['class'=>'form-control form-control-lg','placeholder'=>$placeholder,'id'=>'username']) }}
                            @endif
                        </div>
                        <div class="form-group">
                            {{ Form::label('password','Password') }}
                            @if ($errors->any())
                            @if ($errors->has('password'))
                             {{ Form::password('password',['class'=>'is-invalid form-control form-control-lg','placeholder'=>'Password','id'=>'password']) }}
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @else
                             {{ Form::password('password',['class'=>'form-control form-control-lg','placeholder'=>'Password','id'=>'password']) }}
                            @endif
                            @else
                             {{ Form::password('password',['class'=>'form-control form-control-lg','placeholder'=>'Password','id'=>'password']) }}
                            @endif
                            
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