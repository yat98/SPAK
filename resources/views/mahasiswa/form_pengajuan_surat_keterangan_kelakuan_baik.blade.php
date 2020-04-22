<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            {{ Form::label('nim','NIM') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::text('nim',Session::get('nim'),['class'=>'form-control form-control-lg is-invalid','id'=>'nim','readonly'=>'readonly']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::text('nim',Session::get('nim'),['class'=>'form-control form-control-lg','id'=>'nim','readonly'=>'readonly']) }}
            @endif
            @else
            {{ Form::text('nim',Session::get('nim'),['class'=>'form-control form-control-lg','id'=>'nim','readonly'=>'readonly']) }}
            @endif
        </div>  
        <div class="form-group">
            {{ Form::label('nama','Nama') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nama'))
            {{ Form::text('nama',Session::get('username'),['class'=>'form-control form-control-lg is-invalid','id'=>'nama','disabled'=>'disabled']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::text('nama',Session::get('username'),['class'=>'form-control form-control-lg','id'=>'nama','disabled'=>'disabled']) }}
            @endif
            @else
            {{ Form::text('nama',Session::get('username'),['class'=>'form-control form-control-lg','id'=>'nama','disabled'=>'disabled']) }}
            @endif
        </div>  
        <div class="form-group">
            {{ Form::label('id_tahun_akademik','Tahun Akademik') }}
            @if ($errors->any())
            @if ($errors->has('id_tahun_akademik'))
            {{ Form::select('id_tahun_akademik',$tahunAkademik,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_tahun_akademik','readonly']) }}
            <div class="invalid-feedback">{{ $errors->first('id_tahun_akademik') }}</div>
            @else
            {{ Form::select('id_tahun_akademik',$tahunAkademik,null,['class'=>'form-control form-control-lg ','id'=>'id_tahun_akademik','readonly']) }}
            @endif
            @else
            {{ Form::select('id_tahun_akademik',$tahunAkademik,null,['class'=>'form-control form-control-lg','id'=>'id_tahun_akademik','readonly']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('jenis_surat','Jenis Surat') }}
            {{ Form::select('jenis_surat',['surat keterangan kelakuan baik'=>'Surat Keterangan Kelakuan Baik'],null,['class'=>'form-control form-control-lg','id'=>'semester','readonly'=>'readonly']) }}
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>