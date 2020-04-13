@if (isset($ormawa))
{{ Form::hidden('id',$ormawa->id) }}
@endif
<div class="row">
    <div class="col-md-6">
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
            {{ Form::label('id_jurusan','Jurusan') }}
            @if ($errors->any())
            @if ($errors->has('id_jurusan'))
            {{ Form::select('id_jurusan',$jurusan,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_jurusan','placeholder'=>'-- Pilih Jurusan --']) }}
            <div class="invalid-feedback">{{ $errors->first('id_jurusan') }}</div>
            @else
            {{ Form::select('id_jurusan',$jurusan,null,['class'=>'form-control form-control-lg is-valid','id'=>'id_jurusan','placeholder'=>'-- Pilih Jurusan --']) }}
            @endif
            @else
            {{ Form::select('id_jurusan',$jurusan,null,['class'=>'form-control form-control-lg','id'=>'id_jurusan','placeholder'=>'-- Pilih Jurusan --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>