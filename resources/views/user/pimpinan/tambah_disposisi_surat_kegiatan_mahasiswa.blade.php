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
                        </span> Surat Kegiatan Mahasiswa</h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <h3 class="mb-5">Disposisi Surat Kegiatan Mahasiswa</h3>
                                {{ Form::open(['url'=>'pimpinan/surat-kegiatan-mahasiswa/disposisi']) }}
                                {{ Form::hidden('id',$pengajuanKegiatan->id) }}
                                <div class="row">
                                    <div class="col-md-12">
                                        @if(Auth::user()->jabatan != 'wd3')
                                            <div class="form-group">
                                                {{ Form::label('nip_disposisi','Disposisi Kepada') }}
                                                @if ($errors->any())
                                                @if ($errors->has('nip_disposisi'))
                                                {{ Form::select('nip_disposisi',$userDisposisi,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nip_disposisi']) }}
                                                <div class="invalid-feedback">{{ $errors->first('nip_disposisi') }}</div>
                                                @else
                                                {{ Form::select('nip_disposisi',$userDisposisi,null,['class'=>'form-control form-control-lg is-valid','id'=>'nip_disposisi']) }}
                                                @endif
                                                @else
                                                {{ Form::select('nip_disposisi',$userDisposisi,null,['class'=>'form-control form-control-lg','id'=>'nip_disposisi']) }}
                                                @endif
                                            </div>
                                        @endif
                                        <div class="form-group">
                                            {{ Form::label('catatan','Catatan') }}
                                            @if ($errors->any())
                                            @if ($errors->has('catatan'))
                                            {{ Form::textarea('catatan',null,['class'=>'form-control form-control-lg is-invalid','id'=>'catatan','rows'=>'7']) }}
                                            <div class="invalid-feedback">{{ $errors->first('catatan') }}</div>
                                            @else
                                            {{ Form::textarea('catatan',null,['class'=>'form-control form-control-lg ','id'=>'catatan','rows'=>'7']) }}
                                            @endif
                                            @else
                                            {{ Form::textarea('catatan',null,['class'=>'form-control form-control-lg','id'=>'catatan','rows'=>'7']) }}
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