@extends('template')

@section('content')
<div class="container-scroller">
    @include('layout.navbar_top')
    <div class="container-fluid page-body-wrapper">
        @include('layout.navbar_side')
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <h3 class="page-title">
                        <span class="page-title-icon bg-gradient-primary text-white mr-2">
                            <i class="mdi mdi-settings"></i>
                        </span> Ubah Profil</h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Ubah Password</h3>
                                {{ Form::open(['url'=>'pimpinan/profil/password']) }}                          
                                    <div class="form-group password-group">
                                        {{ Form::label('password_lama','Password Lama') }}
                                        @if ($errors->any())
                                        @if ($errors->has('password_lama'))
                                        {{ Form::password('password_lama',['class'=>'form-control form-control-lg is-invalid','id'=>'password_lama']) }}
                                        <div class="invalid-feedback">{{ $errors->first('password_lama') }}</div>
                                        @else
                                        {{ Form::password('password_lama',['class'=>'form-control form-control-lg is-valid','id'=>'password_lama']) }}
                                        @endif
                                        @else
                                        {{ Form::password('password_lama',['class'=>'form-control form-control-lg','id'=>'password_lama']) }}
                                        @endif
                                    </div>
                                    <div class="form-group password-group">
                                        {{ Form::label('password','Password Baru') }}
                                        @if ($errors->any())
                                        @if ($errors->has('password'))
                                        {{ Form::password('password',['class'=>'form-control form-control-lg is-invalid','id'=>'password']) }}
                                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                                        @else
                                        {{ Form::password('password',['class'=>'form-control form-control-lg is-valid','id'=>'password']) }}
                                        @endif
                                        @else
                                        {{ Form::password('password',['class'=>'form-control form-control-lg','id'=>'password']) }}
                                        @endif
                                    </div>
                                    <div class="form-group password-group">
                                        {{ Form::label('password_confirmation','Konfirmasi Password') }}
                                        @if ($errors->any())
                                        @if ($errors->has('password_confirmation'))
                                        {{ Form::password('password_confirmation',['class'=>'form-control form-control-lg is-invalid','id'=>'password_confirmation']) }}
                                        <div class="invalid-feedback">{{ $errors->first('password_confirmation') }}</div>
                                        @else
                                        {{ Form::password('password_confirmation',['class'=>'form-control form-control-lg is-valid','id'=>'password_confirmation']) }}
                                        @endif
                                        @else
                                        {{ Form::password('password_confirmation',['class'=>'form-control form-control-lg','id'=>'password_confirmation']) }}
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {{ Form::submit('Simpan',['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
                                        <input type="reset" value="Reset" class="btn btn-danger btn-sm">
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.footer')
        </div>
    </div>
</div>
@endsection