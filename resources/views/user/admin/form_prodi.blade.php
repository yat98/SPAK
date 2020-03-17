@if (isset($prodi))
{{ Form::hidden('id',$prodi->id) }}
@endif
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('id_jurusan','Nama Jurusan') }}
            @if ($errors->any())
            @if ($errors->has('id_jurusan'))
            {{ Form::select('id_jurusan',$jurusanList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_jurusan']) }}
            <div class="invalid-feedback">{{ $errors->first('id_jurusan') }}</div>
            @else
            {{ Form::select('id_jurusan',$jurusanList,null,['class'=>'form-control form-control-lg is-valid','id'=>'id_jurusan']) }}
            @endif
            @else
            {{ Form::select('id_jurusan',$jurusanList,null,['class'=>'form-control form-control-lg','id'=>'id_jurusan']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('nama_prodi','Nama Program Studi') }}
            @if ($errors->any())
            @if ($errors->has('nama_prodi'))
            {{ Form::text('nama_prodi',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nama_prodi']) }}
            <div class="invalid-feedback">{{ $errors->first('nama_prodi') }}</div>
            @else
            {{ Form::text('nama_prodi',null,['class'=>'form-control form-control-lg is-valid','id'=>'nama_prodi']) }}
            @endif
            @else
            {{ Form::text('nama_prodi',null,['class'=>'form-control form-control-lg','id'=>'nama_prodi']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>