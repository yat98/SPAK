<div class="row">
    <div class="col-md-12">
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
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <a href="{{ url('admin/profil/password') }}" class="btn btn-warning btn-sm btn-password-edit ">Ubah Password</a>
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>