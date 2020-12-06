<div class="row">
    <div class="col-md-12">
        <div class="form-group ">
            {{ Form::label('id_ormawa','Ormawa') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('id_ormawa'))
            {{ Form::select('id_ormawa',$ormawa,null,['class'=>'form-control form-control-lg is-invalid','readonly'=>'readonly']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('id_ormawa') }}</small></div>
            @else
            {{ Form::select('id_ormawa',$ormawa,null,['class'=>'form-control form-control-lg is-valid','readonly'=>'readonly']) }}
            @endif
            @else
            {{ Form::select('id_ormawa',$ormawa,null,['class'=>'form-control form-control-lg','readonly'=>'readonly']) }}
            @endif
        </div> 
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
            @if(isset($pengajuanKegiatan))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_surat_permohonan_kegiatan','File Surat Permohonan Kegiatan *(Ukuran File < 2MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_surat_permohonan_kegiatan/'.$pengajuanKegiatan->file_surat_permohonan_kegiatan) }}"  data-lightbox="{{ explode('.',$pengajuanKegiatan->file_surat_permohonan_kegiatan)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_permohonan_kegiatan',['class'=>'d-none file-upload-default','id'=>'file_surat_permohonan_kegiatan']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Permohonan Surat Kegiatan Mahasiswa','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_permohonan_kegiatan','File Surat Permohonan Kegiatan Mahasiswa *(Ukuran File < 2MB)') }}
                {{ Form::file('file_surat_permohonan_kegiatan',['class'=>'file-upload-default','id'=>'file_surat_permohonan_kegiatan']) }}           
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Permohonan Surat Kegiatan Mahasiswa','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_permohonan_kegiatan') }}</small></div>
            @endif
        </div>  
        <div class="form-group">
            @if(isset($pengajuanKegiatan))
                 <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_proposal_kegiatan','File Proposal Kegiatan *(Ukuran File < 2MB, File PDF)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="" class="btn btn-sm btn-info ml-4 btn-proposal">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_proposal_kegiatan',['class'=>'d-none file-upload-default','id'=>'file_proposal_kegiatan']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Proposal Kegiatan','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
                <embed src="{{ url('upload_proposal_kegiatan/'.$pengajuanKegiatan->file_proposal_kegiatan) }}" class="mt-2 d-none" id="embed-proposal"  frameborder="0" height="800px" width="100%">
            @else
                {{ Form::label('file_proposal_kegiatan','File Proposal Kegiatan *(Ukuran File < 2MB, File PDF)') }}
                {{ Form::file('file_proposal_kegiatan',['class'=>'file-upload-default','id'=>'file_proposal_kegiatan']) }}           
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Proposal Kegiatan','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_proposal_kegiatan') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>