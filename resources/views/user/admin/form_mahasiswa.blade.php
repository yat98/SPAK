<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('nim','Nim') }}
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::text('nim',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nim']) }}
            <div class="invalid-feedback">{{ $errors->first('nim') }}</div>
            @else
            {{ Form::text('nim',null,['class'=>'form-control form-control-lg is-valid','id'=>'nim']) }}
            @endif
            @else
            {{ Form::text('nim',null,['class'=>'form-control form-control-lg','id'=>'nim']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('nama','Nama') }}
            @if ($errors->any())
            @if ($errors->has('nama'))
            {{ Form::text('nama',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nama']) }}
            <div class="invalid-feedback">{{ $errors->first('nama') }}</div>
            @else
            {{ Form::text('nama',null,['class'=>'form-control form-control-lg is-valid','id'=>'nama']) }}
            @endif
            @else
            {{ Form::text('nama',null,['class'=>'form-control form-control-lg','id'=>'nama']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('sex','Jenis Kelamin') }}
            @if ($errors->any())
            @if ($errors->has('sex'))
            {{ Form::select('sex',['L'=>'Laki-laki','P'=>'Perempuan'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'sex']) }}
            <div class="invalid-feedback">{{ $errors->first('sex') }}</div>
            @else
            {{ Form::select('sex',['L'=>'Laki-laki','P'=>'Perempuan'],null,['class'=>'form-control form-control-lg is-valid','id'=>'sex']) }}
            @endif
            @else
            {{ Form::select('sex',['L'=>'Laki-laki','P'=>'Perempuan'],null,['class'=>'form-control form-control-lg','id'=>'sex']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('angkatan','Angkatan') }}
            @if ($errors->any())
            @if ($errors->has('angkatan'))
            {{ Form::select('angkatan',$angkatan,null,['class'=>'form-control form-control-lg is-invalid','id'=>'angkatan']) }}
            <div class="invalid-feedback">{{ $errors->first('angkatan') }}</div>
            @else
            {{ Form::select('angkatan',$angkatan,null,['class'=>'form-control form-control-lg is-valid','id'=>'angkatan']) }}
            @endif
            @else
            {{ Form::select('angkatan',$angkatan,null,['class'=>'form-control form-control-lg','id'=>'angkatan']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('ipk','IPK') }}
            @if ($errors->any())
            @if ($errors->has('ipk'))
            {{ Form::text('ipk',null,['class'=>'form-control form-control-lg is-invalid','id'=>'strata']) }}
            <div class="invalid-feedback">{{ $errors->first('ipk') }}</div>
            @else
            {{ Form::text('ipk',null,['class'=>'form-control form-control-lg is-valid','id'=>'strata']) }}
            @endif
            @else
            {{ Form::number('ipk',null,['class'=>'form-control form-control-lg','id'=>'strata','min'=>0,'max'=>'4.00','step'=>'0.01' ]) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('id_prodi','Program Studi') }}
            @if ($errors->any())
            @if ($errors->has('id_prodi'))
            {{ Form::select('id_prodi',$prodiList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_prodi']) }}
            <div class="invalid-feedback">{{ $errors->first('id_prodi') }}</div>
            @else
            {{ Form::select('id_prodi',$prodiList,null,['class'=>'form-control form-control-lg is-valid','id'=>'id_prodi']) }}
            @endif
            @else
            {{ Form::select('id_prodi',$prodiList,null,['class'=>'form-control form-control-lg','id'=>'id_prodi']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('password','password') }}
            @if ($errors->any())
            @if ($errors->has('password'))
            {{ Form::password('password',['class'=>'form-control form-control-lg is-invalid','id'=>'password']) }}
            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
            @else
            {{ Form::password('password',['class'=>'form-control form-control-lg is-valid','id'=>'password']) }}
            @endif
            @else
            {{ Form::password('password',['class'=>'form-control form-control-lg','id'=>'password']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>