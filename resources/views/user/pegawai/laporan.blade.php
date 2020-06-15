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
                            <i class="mdi mdi-file-document-box "></i>
                        </span> Laporan</h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                           <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Laporan</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        {{ Form::open(['url'=>'pegawai/laporan']) }}
                                        <div class="form-row">
                                            <div class="form-group col-md-4">
                                                <label for="jenis_surat">Jenis Surat</label>
                                                {{ Form::select('jenis_surat',$jenisSurat,null,['class'=>'form-control form-control-lg','id'=>'jenis_surat','placeholder'=> '-- Pilih Jenis Surat --']) }}
                                                <div class="text-danger-red mt-1"><small>{{ $errors->first('jenis_surat') }}</small></div>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label for="tahun">Tahun</label>
                                                {{ Form::select('tahun',$tahun,null,['class'=>'form-control form-control-lg','id'=>'tahun','placeholder'=> '-- Pilih Tahun --']) }}
                                                <div class="text-danger-red mt-1"><small>{{ $errors->first('tahun') }}</small></div>
                                            </div>
                                            <div class="form-group col-md-2">
                                                <button class="btn btn-success mt-4" type="submit">
                                                    <i class="mdi mdi-magnify btn-icon-prepend"></i>
                                                    Tampilkan
                                                </button>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
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