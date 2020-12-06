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
                        </span> Surat Permohonan Survei </h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Edit Surat Permohonan Survei</h3>
                                {{ Form::model($suratSurvei->toArray() + $suratSurvei->pengajuanSuratPermohonanSurvei->toArray(),['method'=>'PATCH','files'=>true,'action'=>['SuratPermohonanSurveiController@update',$suratSurvei->id_pengajuan]]) }}
                                @include('user.pegawai.form_surat_permohonan_survei',['buttonLabel'=>'Simpan'])
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