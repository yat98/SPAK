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
            {{ Form::label('username','Username') }}
            @if ($errors->any())
            @if ($errors->has('username'))
            {{ Form::text('username',null,['class'=>'form-control form-control-lg is-invalid','id'=>'username']) }}
            <div class="invalid-feedback">{{ $errors->first('username') }}</div>
            @else
            {{ Form::text('username',null,['class'=>'form-control form-control-lg is-valid','id'=>'username']) }}
            @endif
            @else
            {{ Form::text('username',null,['class'=>'form-control form-control-lg','id'=>'username']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('bagian','Bagian') }}
            @if ($errors->any())
            @if ($errors->has('bagian'))
            {{ Form::text('bagian',$operator->bagian,['class'=>'form-control form-control-lg is-invalid','id'=>'bagian','disabled'=>'disabled']) }}
            <div class="invalid-feedback">{{ $errors->first('bagian') }}</div>
            @else
            {{ Form::text('bagian',$operator->bagian,['class'=>'form-control form-control-lg is-valid','id'=>'bagian','disabled'=>'disabled']) }}
            @endif
            @else
            {{ Form::text('bagian',$operator->bagian,['class'=>'form-control form-control-lg','id'=>'bagian','disabled'=>'disabled']) }}
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
            <a href="{{ url('operator/profil/password') }}" class="btn btn-warning btn-sm btn-password-edit btn-margin btn-tambah mx-md-2 mt-2 mb-2">Ubah Password</a>
            <input type="reset" value="Reset" class="text-center btn btn-danger btn-sm btn-margin btn-tambah mt-2 mb-2"> 
        </div>
    </div>
</div>