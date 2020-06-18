@if(isset($pengajuanSuratLulus))
    {{ Form::hidden('id',$pengajuanSuratLulus->id) }}
@endif
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            {{ Form::label('nim','Mahasiswa') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::select('nim',$mahasiswa,null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','placeholder'=> '-- Pilih Mahasiswa --']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::select('nim',$mahasiswa,null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','placeholder'=> '-- Pilih Mahasiswa --']) }}
            @endif
            @else
            {{ Form::select('nim',$mahasiswa,null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','placeholder'=> '-- Pilih Mahasiswa --']) }}
            @endif
        </div> 
        <div class="form-group">
            {{ Form::label('nomor_surat','Nomor Surat') }}
            <div class="form-row">
                <div class="col-md-2 col-sm-3 mt-1">
                {{ Form::text('tipe_surat','B',['class'=>'form-control form-control-lg','disabled'=>'disabled']) }}
                </div>
                <div class="col-md-4 col-sm-3 mt-1">
                @if(!isset($pengajuanSuratLulus))
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
                @else
                    {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg','id'=>'nomor_surat','readonly'=>'readonly']) }}
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
                {{ Form::text('tahun',isset($pengajuanSuratLulus)?$pengajuanSuratLulus->created_at->format('Y'):date('Y') ,['class'=>'form-control form-control-lg','disabled'=>'disabled']) }}
                </div>
            </div>    
        </div> 
         <div class="form-group">
            {{ Form::label('ipk','IPK') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('ipk'))
            {{ Form::text('ipk',Session::get('ipk'),['class'=>'form-control form-control-lg is-invalid','id'=>'ipk']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('ipk') }}</small></div>
            @else
            {{ Form::text('ipk',Session::get('ipk'),['class'=>'form-control is-valid form-control-lg','id'=>'ipk']) }}
            @endif
            @else
            {{ Form::text('ipk',Session::get('ipk'),['class'=>'form-control form-control-lg','id'=>'ipk']) }}
            @endif
        </div>
         <div class="form-group">
            {{ Form::label('tanggal_wisuda','Tanggal Wisuda') }}
            @if ($errors->any())
            @if ($errors->has('tanggal_wisuda'))
            {{ Form::text('tanggal_wisuda',isset($pengajuanSuratLulus)?$pengajuanSuratLulus->tanggal_wisuda->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_wisuda','placeholder'=>'yyyy-mm-dd']) }}
            <div class="invalid-feedback">{{ $errors->first('tanggal_wisuda') }}</div>
            @else
            {{ Form::text('tanggal_wisuda',isset($pengajuanSuratLulus)?$pengajuanSuratLulus->tanggal_wisuda->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_wisuda','placeholder'=>'yyyy-mm-dd']) }}
            @endif
            @else
            {{ Form::text('tanggal_wisuda',isset($pengajuanSuratLulus)?$pengajuanSuratLulus->tanggal_wisuda->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_wisuda','placeholder'=>'yyyy-mm-dd']) }}
            @endif    
        </div>
        <div class="form-group">
            @if(isset($pengajuanSuratLulus))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_rekomendasi_jurusan','File Surat Rekomendasi Jurusan *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_rekomendasi_jurusan/'.$pengajuanSuratLulus->file_rekomendasi_jurusan) }}"  data-lightbox="{{ explode('.',$pengajuanSuratLulus->file_rekomendasi_jurusan)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
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
                {{ Form::label('file_rekomendasi_jurusan','File Surat Rekomendasi Jurusan *(Ukuran File < 1MB)') }}
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
            @if(isset($pengajuanSuratLulus))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_berita_acara_ujian','File Berita Acara Ujian *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_berita_acara_ujian/'.$pengajuanSuratLulus->file_berita_acara_ujian) }}"  data-lightbox="{{ explode('.',$pengajuanSuratLulus->file_berita_acara_ujian)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_berita_acara_ujian',['class'=>'file-upload-default','id'=>'file_berita_acara_ujian']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Berita Acara Ujian','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_berita_acara_ujian','File Berita Acara Ujian *(Ukuran File < 1MB)') }}
                {{ Form::file('file_berita_acara_ujian',['class'=>'file-upload-default','id'=>'file_berita_acara_ujian']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Berita Acara Ujian','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_berita_acara_ujian') }}</small></div>
            @endif
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
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>