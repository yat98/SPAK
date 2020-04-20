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
                        </span> Surat Keterangan Kelakuan Baik</h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Tambah Surat Keterangan Kelakuan Baik</h3>
                                {{ Form::open(['url'=>'pegawai/surat-keterangan-kelakuan-baik']) }}
                                @include('user.pegawai.form_surat_keterangan_kelakuan_baik',['buttonLabel'=>'Tambah'])
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