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
                        </span> Surat Permohonan Pengambilan Data Awal</h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Buat Pengajuan Surat Permohonan Pengambilan Data Awal</h3>
                                {{ Form::model($pengajuanSuratDataAwal,['method'=>'PATCH','action'=>['PengajuanSuratPermohonanPengambilanDataAwalController@update',$pengajuanSuratDataAwal->id],'files'=>true]) }}
                                @include('mahasiswa.form_pengajuan_surat_permohonan_pengambilan_data_awal',['buttonLabel'=>'Simpan'])
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