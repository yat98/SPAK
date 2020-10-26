<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nip','NIP') }}
            @if ($errors->any())
            @if ($errors->has('nip'))
            {{ Form::text('nip',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nip']) }}
            <div class="invalid-feedback">{{ $errors->first('nip') }}</div>
            @else
            {{ Form::text('nip',null,['class'=>'form-control form-control-lg is-valid','id'=>'nip']) }}
            @endif
            @else
            {{ Form::text('nip',null,['class'=>'form-control form-control-lg','id'=>'nip']) }}
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
            {{ Form::label('jabatan','Jabatan') }}
            @if ($errors->any())
            @if ($errors->has('jabatan'))
            {{ Form::text('jabatan',null,['class'=>'form-control form-control-lg is-invalid','id'=>'jabatan','disabled'=>'disabled']) }}
            <div class="invalid-feedback">{{ $errors->first('jabatan') }}</div>
            @else
            {{ Form::text('jabatan',null,['class'=>'form-control form-control-lg is-valid','id'=>'jabatan','disabled'=>'disabled']) }}
            @endif
            @else
            {{ Form::text('jabatan',null,['class'=>'form-control form-control-lg','id'=>'jabatan','disabled'=>'disabled']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('status','Status Aktif') }}
            <br>
            @if ($status == 'aktif')
            <label
            class="badge badge-gradient-info">{{ ucwords($status) }}</label>
            @else
            <label
            class="badge badge-gradient-dark">{{ ucwords($status) }}</label>
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn btn-margin btn-tambah mt-2 mb-2']) }}
            <a href="{{ url('pegawai/profil/password') }}" class="btn btn-warning btn-sm btn-password-edit btn-margin btn-tambah mx-md-2 mt-2 mb-2">Ubah Password</a>
            <input type="reset" value="Reset" class="text-center btn btn-danger btn-sm btn-margin btn-tambah mt-2 mb-2"> 
        </div>
    </div>
</div>