<div class="row">
    <div class="col-md-12">
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
            {{ Form::label('id_waktu_cuti','Tahun Akademik') }}
            @if ($errors->any())
            @if ($errors->has('id_waktu_cuti'))
            {{ Form::select('id_waktu_cuti',$waktuCuti,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_waktu_cuti']) }}
            <div class="invalid-feedback">{{ $errors->first('id_waktu_cuti') }}</div>
            @else
            {{ Form::select('id_waktu_cuti',$waktuCuti,null,['class'=>'form-control form-control-lg ','id'=>'id_waktu_cuti']) }}
            @endif
            @else
            {{ Form::select('id_waktu_cuti',$waktuCuti,null,['class'=>'form-control form-control-lg','id'=>'id_waktu_cuti']) }}
            @endif
        </div>
        <div class="form-group">
            @if(isset($pendaftaranCuti))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_surat_permohonan_cuti','File Surat Permohonan Cuti *(Ukuran File < 2MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_permohonan_cuti/'.$pendaftaranCuti->file_surat_permohonan_cuti) }}"  data-lightbox="{{ explode('.',$pendaftaranCuti->file_surat_permohonan_cuti)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_permohonan_cuti',['class'=>'d-none file-upload-default','id'=>'file_surat_permohonan_cuti']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Permohonan Cuti','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_permohonan_cuti','File Surat Permohonan Cuti *(Ukuran File < 2MB)') }}
                {{ Form::file('file_surat_permohonan_cuti',['class'=>'file-upload-default','id'=>'file_surat_keterangan_bebas_perlengkapan_fakultas']) }}           
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Permohonan Cuti','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_permohonan_cuti') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            @if(isset($pendaftaranCuti))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_krs_sebelumnya','File KRS Sebelumnya *(Ukuran File < 2MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_permohonan_cuti/'.$pendaftaranCuti->file_krs_sebelumnya) }}"  data-lightbox="{{ explode('.',$pendaftaranCuti->file_krs_sebelumnya)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_krs_sebelumnya',['class'=>'d-none file-upload-default','id'=>'file_krs_sebelumnya']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload KRS Sebelumnya','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_krs_sebelumnya','File KRS Sebelumnya *(Ukuran File < 2MB)') }}
                {{ Form::file('file_krs_sebelumnya',['class'=>'file-upload-default','id'=>'file_krs_sebelumnya']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File KRS Sebelumnya','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_krs_sebelumnya') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            @if(isset($pendaftaranCuti))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_slip_ukt','File Slip UKT Semester Sebelumnya *(Ukuran File < 2MB)') }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_permohonan_cuti/'.$pendaftaranCuti->file_slip_ukt) }}"  data-lightbox="{{ explode('.',$pendaftaranCuti->file_slip_ukt)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_slip_ukt',['class'=>'file-upload-default','id'=>'file_slip_ukt']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Slip UKT Semester Sebelumnya','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_slip_ukt','File Slip UKT Semester Sebelumnya *(Ukuran File < 2MB)') }}
                {{ Form::file('file_slip_ukt',['class'=>'file-upload-default','id'=>'file_slip_ukt']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Slip UKT Semester Sebelumnya','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>    
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_slip_ukt') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('alasan_cuti','Alasan Cuti') }}
            @if ($errors->any())
            @if ($errors->has('alasan_cuti'))
            {{ Form::textarea('alasan_cuti',null,['class'=>'form-control form-control-lg is-invalid','id'=>'alasan_cuti','rows'=>'7']) }}
            <div class="invalid-feedback">{{ $errors->first('alasan_cuti') }}</div>
            @else
            {{ Form::textarea('alasan_cuti',null,['class'=>'form-control form-control-lg ','id'=>'alasan_cuti','rows'=>'7']) }}
            @endif
            @else
            {{ Form::textarea('alasan_cuti',null,['class'=>'form-control form-control-lg','id'=>'alasan_cuti','rows'=>'7']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>