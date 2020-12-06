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
                            <i class="mdi mdi-bank"></i>
                        </span> Mahasiswa</h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Import Data Mahasiswa</h3>
                                {{ Form::open(['url'=>'admin/mahasiswa/import-mahasiswa','files'=>true]) }}
                                <div class="form-group">
                                    {{ Form::label('import_mahasiswa','File Import Mahasiswa') }}
                                    {{ Form::file('data_mahasiswa',['class'=>'file-upload-default','id'=>'import_mahasiswa']) }}
                                    <div class="input-group col-xs-12">
                                        @if ($errors->any())
                                        {{ Form::text('',null,['class'=>'form-control is-invalid file-upload-info','placeholder'=>'Upload Data Status Mahasiswa','disabled'=>'disabled']) }}
                                        @else
                                        {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Mahasiswa','disabled'=>'disabled']) }}
                                        @endif
                                        <span class="input-group-append">
                                            <button class="file-upload-browse btn btn-gradient-success"
                                                type="button">Upload</button>
                                        </span>
                                    </div>
                                    @if ($errors->any())
                                    <div class="text-danger-red mt-1"><small>{{ $errors->first('data_mahasiswa') }}</small></div>
                                    @endif
                                    @if (isset($failures))
                                    <div class="text-danger-red mt-1">
                                        {{ head($failures)->errors()[0] }}
                                    </div>
                                    @endif

                                </div>
                                <div class="form-group">
                                    {{ Form::submit('Import',['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn btn-upload']) }}
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