@if (isset($prodi))
{{ Form::hidden('id',$prodi->id) }}
@endif
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('id_jurusan','Nama Jurusan') }}
            @if ($errors->any())
            @if ($errors->has('id_jurusan'))
            {{ Form::select('id_jurusan',$jurusanList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_jurusan','placeholder'=> '-- Pilih Jurusan --']) }}
            <div class="invalid-feedback">{{ $errors->first('id_jurusan') }}</div>
            @else
            {{ Form::select('id_jurusan',$jurusanList,null,['class'=>'form-control form-control-lg is-valid','id'=>'id_jurusan','placeholder'=> '-- Pilih Jurusan --']) }}
            @endif
            @else
            {{ Form::select('id_jurusan',$jurusanList,null,['class'=>'form-control form-control-lg','id'=>'id_jurusan','placeholder'=> '-- Pilih Jurusan --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('strata','Strata') }}
            @if ($errors->any())
            @if ($errors->has('strata'))
            {{ Form::select('strata',['D3'=>'D3','S1'=>'S1','S2'=>'S2','S3'=>'S3'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'strata','placeholder'=> '-- Pilih Strata --']) }}
            <div class="invalid-feedback">{{ $errors->first('strata') }}</div>
            @else
            {{ Form::select('strata',['D3'=>'D3','S1'=>'S1','S2'=>'S2','S3'=>'S3'],null,['class'=>'form-control form-control-lg is-valid','id'=>'strata','placeholder'=> '-- Pilih Strata --']) }}
            @endif
            @else
            {{ Form::select('strata',['D3'=>'D3','S1'=>'S1','S2'=>'S2','S3'=>'S3'],null,['class'=>'form-control form-control-lg','id'=>'strata','placeholder'=> '-- Pilih Strata --']) }}
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