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
                            <i class="mdi mdi-file-document-box"></i>
                        </span>Surat Persetujuan Pindah</h3>
                </div>
                @if (Session::has('info-badge'))   
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body bg-info text-white">
                                <div class="row">
                                    <div class="col-12 d-flex align-items-center">
                                        <div class="d-flex flex-row align-items-center">
                                        <i class="mdi mdi-information icon-lg text-white"></i>
                                        <h3 class="h3 mb-0 ml-1"> Informasi </h3>
                                      </div>
                                    </div>
                                    <div class="col-12 mt-3">
                                        <p>{{ Session::get('info-badge') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Edit Pengajuan Surat Persetujuan Pindah</h3>
                                {{ Form::model($pengajuanSurat,['method'=>'PATCH','action'=>['PengajuanSuratPersetujuanPindahController@update',$pengajuanSurat->id],'files'=>true]) }}
                                @include('operator.form_pengajuan_surat_persetujuan_pindah',['buttonLabel'=>'Simpan'])
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