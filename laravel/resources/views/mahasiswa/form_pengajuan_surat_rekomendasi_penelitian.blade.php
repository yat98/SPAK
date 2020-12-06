@if(isset($pengajuanSurat))
    {{ Form::hidden('id',$pengajuanSurat->id) }}
@endif
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nim','NIM') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::text('nim',Auth::user()->nim,['class'=>'form-control form-control-lg is-invalid','id'=>'nim','readonly'=>'readonly']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::text('nim',Auth::user()->nim,['class'=>'form-control form-control-lg','id'=>'nim','readonly'=>'readonly']) }}
            @endif
            @else
            {{ Form::text('nim',Auth::user()->nim,['class'=>'form-control form-control-lg','id'=>'nim','readonly'=>'readonly']) }}
            @endif
        </div>  
        <div class="form-group">
            {{ Form::label('nama','Nama') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nama'))
            {{ Form::text('nama',Auth::user()->nama,['class'=>'form-control form-control-lg is-invalid','id'=>'nama','disabled'=>'disabled']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nama') }}</small></div>
            @else
            {{ Form::text('nama',Auth::user()->nama,['class'=>'form-control form-control-lg','id'=>'nama','disabled'=>'disabled']) }}
            @endif
            @else
            {{ Form::text('nama',Auth::user()->nama,['class'=>'form-control form-control-lg','id'=>'nama','disabled'=>'disabled']) }}
            @endif
        </div> 
        <div class="form-group">
            {{ Form::label('kepada','Kepada') }}
            @if ($errors->any())
            @if ($errors->has('kepada'))
            {{ Form::text('kepada',null,['class'=>'form-control form-control-lg is-invalid','id'=>'kepada','placeholder'=>'Contoh : Pimpinan PT.Cahaya Nusa Sulutarindo']) }}
            <div class="invalid-feedback">{{ $errors->first('kepada') }}</div>
            @else
            {{ Form::text('kepada',null,['class'=>'form-control form-control-lg is-valid','id'=>'kepada','placeholder'=>'Contoh : Pimpinan PT.Cahaya Nusa Sulutarindo']) }}
            @endif
            @else
            {{ Form::text('kepada',null,['class'=>'form-control form-control-lg','id'=>'kepada','placeholder'=>'Contoh : Pimpinan PT.Cahaya Nusa Sulutarindo']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('judul','Judul') }}
            @if ($errors->any())
            @if ($errors->has('judul'))
            {{ Form::text('judul',null,['class'=>'form-control form-control-lg is-invalid','id'=>'judul']) }}
            <div class="invalid-feedback">{{ $errors->first('judul') }}</div>
            @else
            {{ Form::text('judul',null,['class'=>'form-control form-control-lg is-valid','id'=>'judul']) }}
            @endif
            @else
            {{ Form::text('judul',null,['class'=>'form-control form-control-lg','id'=>'judul']) }}
            @endif
        </div>
        <div class="form-group">
            @if(isset($pengajuanSurat))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_rekomendasi_jurusan','File Surat Rekomendasi Jurusan *(Ukuran File < 2MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_rekomendasi_jurusan/'.$pengajuanSurat->file_rekomendasi_jurusan) }}"  data-lightbox="{{ explode('.',$pengajuanSurat->file_rekomendasi_jurusan)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_rekomendasi_jurusan',['class'=>'file-upload-default','id'=>'file_rekomendasi_jurusan']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Rekomendasi Jurusan','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_rekomendasi_jurusan','File Surat Rekomendasi Jurusan *(Ukuran File < 2MB)') }}
                {{ Form::file('file_rekomendasi_jurusan',['class'=>'file-upload-default','id'=>'file_rekomendasi_jurusan']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Rekomendasi Jurusan','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_rekomendasi_jurusan') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>