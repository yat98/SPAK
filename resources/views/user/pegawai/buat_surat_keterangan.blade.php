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
                        </span> {{ $jenisSurat }}</h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Buat {{ $jenisSurat }}</h3>
                                @if($jenisSurat == 'Surat Keterangan Aktif Kuliah')
                                {{ Form::open(['url'=>'pegawai/surat-keterangan-aktif-kuliah/pengajuan/buat-surat']) }}
                                @else
                                {{ Form::open(['url'=>'pegawai/surat-keterangan-kelakuan-baik/pengajuan/buat-surat']) }}
                                @endif
                                @if (isset($pengajuanSuratKeterangan))
                                {{ Form::hidden('id_pengajuan_surat_keterangan',$pengajuanSuratKeterangan->id) }}
                                @endif
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('nomor_surat','Nomor Surat') }}
                                            <div class="form-row">
                                                <div class="col-md-2 col-sm-3 mt-1">
                                                {{ Form::text('tipe_surat','B',['class'=>'form-control form-control-lg','disabled'=>'disabled']) }}
                                                </div>
                                                <div class="col-md-4 col-sm-3 mt-1">
                                                @if ($errors->any())
                                                @if ($errors->has('nomor_surat'))
                                                {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_surat']) }}
                                                <div class="text-danger-red mt-1"><small>{{ $errors->first('nomor_surat') }}</small></div>
                                                @else
                                                {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg is-valid','id'=>'nomor_surat']) }}
                                                @endif
                                                @else
                                                {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg','id'=>'nomor_surat']) }}
                                                @endif
                                                </div>
                                                <div class="col-md col-sm-3 mt-1">
                                                @if ($errors->any())
                                                @if ($errors->has('id_kode_surat'))
                                                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_surat','readonly'=>'readonly']) }}
                                                <div class="text-danger-red mt-1"><small>{{ $errors->first('id_kode_surat') }}</small></div>
                                                @else
                                                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg is-valid','id'=>'nomor_surat','readonly'=>'readonly']) }}
                                                @endif
                                                @else
                                                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg','id'=>'nomor_surat','readonly'=>'readonly']) }}
                                                @endif
                                                </div>
                                                <div class="col-md-3 col-sm-3 mt-1">
                                                {{ Form::text('tahun',isset($pengajuanSuratPersetujuanPindah)?$pengajuanSuratPersetujuanPindah->created_at->format('Y'):date('Y') ,['class'=>'form-control form-control-lg','disabled'=>'disabled']) }}
                                                </div>
                                            </div>    
                                        </div> 
                                        <div class="form-group">
                                            {{ Form::label('nip','Tanda Tangan') }}
                                            @if ($errors->any())
                                            @if ($errors->has('nip'))
                                            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nip','readonly'=> 'readonly']) }}
                                            <div class="invalid-feedback">{{ $errors->first('nip') }}</div>
                                            @else
                                            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-valid','id'=>'nip','readonly'=> 'readonly']) }}
                                            @endif
                                            @else
                                            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg','id'=>'nip','readonly'=> 'readonly']) }}
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