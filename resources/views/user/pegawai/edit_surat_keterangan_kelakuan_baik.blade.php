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
                            <i class="mdi mdi-file-document-box menu-icon"></i>
                        </span> Surat Keterangan Kelakuan Baik </h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Edit Surat Keterangan Kelakuan Baik</h3>
                                {{ Form::model($suratKeterangan->toArray() + $suratKeterangan->pengajuanSuratKeterangan->toArray(),['method'=>'PATCH','action'=>['SuratKeteranganController@updateSuratKeteranganKelakuanBaik',$suratKeterangan->id_pengajuan_surat_keterangan]]) }}
                                @include('user.pegawai.form_surat_keterangan_kelakuan_baik',['buttonLabel'=>'Simpan'])
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