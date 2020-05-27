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
                        </span> Surat Kegiatan Mahasiswa </h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Tambah Surat Kegiatan Mahasiswa</h3>
                                {{ Form::open(['url'=>'pegawai/surat-kegiatan-mahasiswa','files'=>true]) }}
                                <div class="row">
                                    <div class="col-md-10">
                                        <div class="form-group">
                                            {{ Form::label('nomor_surat_permohonan_kegiatan','Nomor Surat Permohonan Kegiatan') }}
                                            <br>
                                            @if ($errors->any())
                                            @if ($errors->has('nomor_surat_permohonan_kegiatan'))
                                            {{ Form::text('nomor_surat_permohonan_kegiatan',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_surat_permohonan_kegiatan']) }}
                                            <div class="text-danger-red mt-1"><small>{{ $errors->first('nomor_surat_permohonan_kegiatan') }}</small></div>
                                            @else
                                            {{ Form::text('nomor_surat_permohonan_kegiatan',null,['class'=>'form-control form-control-lg','id'=>'nomor_surat_permohonan_kegiatan']) }}
                                            @endif
                                            @else
                                            {{ Form::text('nomor_surat_permohonan_kegiatan',null,['class'=>'form-control form-control-lg','id'=>'nomor_surat_permohonan_kegiatan']) }}
                                            @endif
                                        </div>    
                                        <div class="form-group">
                                            {{ Form::label('nama_kegiatan','Nama Kegiatan') }}
                                            <br>
                                            @if ($errors->any())
                                            @if ($errors->has('nama_kegiatan'))
                                            {{ Form::text('nama_kegiatan',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nama_kegiatan']) }}
                                            <div class="text-danger-red mt-1"><small>{{ $errors->first('nama_kegiatan') }}</small></div>
                                            @else
                                            {{ Form::text('nama_kegiatan',null,['class'=>'form-control form-control-lg','id'=>'nama_kegiatan']) }}
                                            @endif
                                            @else
                                            {{ Form::text('nama_kegiatan',null,['class'=>'form-control form-control-lg','id'=>'nama_kegiatan']) }}
                                            @endif
                                        </div>
                                          <div class="form-group">
                                            {{ Form::label('ormawa','Ormawa') }}
                                            @if ($errors->any())
                                            @if ($errors->has('ormawa'))
                                            {{ Form::select('ormawa',$ormawaList,isset($suratKegiatan)?$suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->id_ormawa:null,['class'=>'form-control form-control-lg is-invalid ormawa-list','id'=>'nip','placeholder'=> '-- Pilih Ormawa --']) }}
                                            <div class="invalid-feedback">{{ $errors->first('ormawa') }}</div>
                                            @else
                                            {{ Form::select('ormawa',$ormawaList,isset($suratKegiatan)?$suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->id_ormawa:null,['class'=>'form-control form-control-lg is-valid ormawa-list','id'=>'nip','placeholder'=> '-- Pilih Ormawa --']) }}
                                            @endif
                                            @else
                                            {{ Form::select('ormawa',$ormawaList,isset($suratKegiatan)?$suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->id_ormawa:null,['class'=>'form-control form-control-lg ormawa-list','id'=>'nip','placeholder'=> '-- Pilih Ormawa --']) }}
                                            @endif
                                        </div>
                                        <div class="form-group">                   
                                            {{ Form::label('file_surat_permohonan_kegiatan','File Surat Permohonan Kegiatan Mahasiswa *(Ukuran File < 1MB)') }}
                                            {{ Form::file('file_surat_permohonan_kegiatan',['class'=>'file-upload-default','id'=>'file_surat_permohonan_kegiatan']) }}           
                                            <div class="input-group col-xs-12">
                                                {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Permohonan Surat Kegiatan Mahasiswa','disabled'=>'disabled']) }}
                                                <span class="input-group-append">
                                                    <button class="file-upload-browse btn btn-gradient-success"
                                                        type="button">Upload</button>
                                                </span>
                                            </div>
                                            @if ($errors->any())
                                            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_permohonan_kegiatan') }}</small></div>
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            {{ Form::label('lampiran_panitia','Lampiran Panitia') }}
                                            @if ($errors->any())
                                            @if ($errors->has('lampiran_panitia'))
                                            {{ Form::textarea('lampiran_panitia',null,['class'=>'form-control form-control-lg is-invalid','id'=>'lampiran_panitia','rows'=>'100']) }}
                                            <div class="invalid-feedback">{{ $errors->first('lampiran_panitia') }}</div>
                                            @else
                                            {{ Form::textarea('lampiran_panitia',null,['class'=>'form-control form-control-lg ','id'=>'lampiran_panitia','rows'=>'100']) }}
                                            @endif
                                            @else
                                            {{ Form::textarea('lampiran_panitia',null,['class'=>'form-control form-control-lg','id'=>'lampiran_panitia','rows'=>'100']) }}
                                            @endif
                                        </div>
                                        <div class="form-group">
                                            {{ Form::submit('Tambah',['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
                                            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
                                        </div>
                                    </div>
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