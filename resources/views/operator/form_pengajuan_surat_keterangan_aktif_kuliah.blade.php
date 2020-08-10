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
            {{ Form::label('id_tahun_akademik','Tahun Akademik') }}
            @if ($errors->any())
            @if ($errors->has('id_tahun_akademik'))
            {{ Form::select('id_tahun_akademik',$tahunAkademik,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_tahun_akademik']) }}
            <div class="invalid-feedback">{{ $errors->first('id_tahun_akademik') }}</div>
            @else
            {{ Form::select('id_tahun_akademik',$tahunAkademik,null,['class'=>'form-control form-control-lg ','id'=>'id_tahun_akademik']) }}
            @endif
            @else
            {{ Form::select('id_tahun_akademik',$tahunAkademik,null,['class'=>'form-control form-control-lg','id'=>'id_tahun_akademik']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('jenis_surat','Jenis Surat') }}
            {{ Form::select('jenis_surat',['surat keterangan aktif kuliah'=>'Surat Keterangan Aktif Kuliah'],null,['class'=>'form-control form-control-lg','id'=>'semester','readonly'=>'readonly']) }}
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>