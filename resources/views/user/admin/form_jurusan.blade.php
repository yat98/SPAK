@if (isset($jurusan))
{{ Form::hidden('id',$jurusan->id) }}
@endif
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('nama_jurusan','Nama Jurusan') }}
            @if ($errors->any())
            @if ($errors->has('nama_jurusan'))
            {{ Form::text('nama_jurusan',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nama_jurusan']) }}
            <div class="invalid-feedback">{{ $errors->first('nama_jurusan') }}</div>
            @else
            {{ Form::text('nama_jurusan',null,['class'=>'form-control form-control-lg is-valid','id'=>'nama_jurusan']) }}
            @endif
            @else
            {{ Form::text('nama_jurusan',null,['class'=>'form-control form-control-lg','id'=>'nama_jurusan']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>