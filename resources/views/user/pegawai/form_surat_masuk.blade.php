@if (isset($suratMasuk))
{{ Form::hidden('id',$suratMasuk->id) }}
@endif
@php
    if(isset($suratMasuk)){
        $bagian = ['subbagian kemahasiswaan'=>'Subbagian Kemahasiswaan','subbagian pendidikan dan pengajaran'=>'Subbagian Pendidikan Dan Pengajaran'];
    }else{
        if(Session::get('jabatan') == 'kasubag kemahasiswaan'){
            $bagian = ['subbagian kemahasiswaan'=>'Subbagian Kemahasiswaan'];
        }else{
            $bagian = ['subbagian pendidikan dan pengajaran'=>'Subbagian Pendidikan Dan Pengajaran'];
        }
    }
@endphp
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            {{ Form::label('bagian','Bagian') }}
            @if ($errors->any())
            @if ($errors->has('bagian'))
            {{ Form::select('bagian',$bagian,null,['class'=>'form-control form-control-lg is-invalid','id'=>'bagian','readonly'=>'readonly']) }}
            <div class="invalid-feedback">{{ $errors->first('bagian') }}</div>
            @else
            {{ Form::select('bagian',$bagian,null,['class'=>'form-control form-control-lg is-valid','id'=>'bagian','readonly'=>'readonly']) }}
            @endif
            @else
            {{ Form::select('bagian',$bagian,null,['class'=>'form-control form-control-lg','id'=>'bagian','readonly'=>'readonly']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('nomor_surat','Nomor Surat') }}
            @if ($errors->any())
            @if ($errors->has('nomor_surat'))
            {{ Form::text('nomor_surat',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_surat']) }}
            <div class="invalid-feedback">{{ $errors->first('nomor_surat') }}</div>
            @else
            {{ Form::text('nomor_surat',null,['class'=>'form-control form-control-lg is-valid','id'=>'nomor_surat']) }}
            @endif
            @else
            {{ Form::text('nomor_surat',null,['class'=>'form-control form-control-lg','id'=>'nomor_surat']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('perihal','Perihal') }}
            @if ($errors->any())
            @if ($errors->has('perihal'))
            {{ Form::text('perihal',null,['class'=>'form-control form-control-lg is-invalid','id'=>'perihal']) }}
            <div class="invalid-feedback">{{ $errors->first('perihal') }}</div>
            @else
            {{ Form::text('perihal',null,['class'=>'form-control form-control-lg is-valid','id'=>'perihal']) }}
            @endif
            @else
            {{ Form::text('perihal',null,['class'=>'form-control form-control-lg','id'=>'perihal']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('instansi','Instansi') }}
            @if ($errors->any())
            @if ($errors->has('instansi'))
            {{ Form::text('instansi',null,['class'=>'form-control form-control-lg is-invalid','id'=>'instansi']) }}
            <div class="invalid-feedback">{{ $errors->first('instansi') }}</div>
            @else
            {{ Form::text('instansi',null,['class'=>'form-control form-control-lg is-valid','id'=>'instansi']) }}
            @endif
            @else
            {{ Form::text('instansi',null,['class'=>'form-control form-control-lg','id'=>'instansi']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('tanggal_surat_masuk','Tanggal Surat Masuk') }}
            @if ($errors->any())
            @if ($errors->has('tanggal_surat_masuk'))
            {{ Form::text('tanggal_surat_masuk',isset($suratMasuk) ? $suratMasuk->created_at->format('Y-m-d') : null,['class'=>'form-control form-control-lg is-invalid','id'=>'tanggal_surat_masuk','placeholder'=>'yyyy-mm-dd']) }}
            <div class="invalid-feedback">{{ $errors->first('tanggal_surat_masuk') }}</div>
            @else
            {{ Form::text('tanggal_surat_masuk',isset($suratMasuk) ? $suratMasuk->created_at->format('Y-m-d') : null,['class'=>'form-control form-control-lg is-valid','id'=>'tanggal_surat_masuk','placeholder'=>'yyyy-mm-dd']) }}
            @endif
            @else
            {{ Form::text('tanggal_surat_masuk',isset($suratMasuk) ? $suratMasuk->created_at->format('Y-m-d') : null,['class'=>'form-control form-control-lg','id'=>'tanggal_surat_masuk','placeholder'=>'yyyy-mm-dd']) }}
            @endif
        </div>
        <div class="form-group">
            @if(isset($suratMasuk))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_surat_masuk','File Surat Masuk *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_surat_masuk/'.$suratMasuk->file_surat_masuk) }}"  data-lightbox="{{ explode('.',$suratMasuk->file_surat_masuk)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_masuk',['class'=>'file-upload-default','id'=>'file_surat_masuk']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Masuk','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_masuk','File Surat Masuk *(Ukuran File < 1MB)') }}
                {{ Form::file('file_surat_masuk',['class'=>'file-upload-default','id'=>'file_surat_masuk']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Masuk','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_masuk') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>