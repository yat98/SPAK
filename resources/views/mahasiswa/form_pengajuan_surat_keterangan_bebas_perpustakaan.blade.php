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
            {{ Form::label('nokta','No KTA') }}
            @if ($errors->any())
            @if ($errors->has('nokta'))
            {{ Form::text('nokta',null,['class'=>'form-control is-invalid','id'=>'nokta']) }}
            <div class="invalid-feedback">{{ $errors->first('nokta') }}</div>
            @else
            {{ Form::text('nokta',null,['class'=>'form-control is-valid','id'=>'nokta']) }}
            @endif
            @else
            {{ Form::text('nokta',null,['class'=>'form-control','id'=>'nokta']) }}
            @endif    
        </div>
        <div class="form-group">
            {{ Form::label('telp','Telp') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('telp'))
            {{ Form::text('telp',null,['class'=>'form-control form-control-lg is-invalid','id'=>'telp']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('ipk') }}</small></div>
            @else
            {{ Form::text('telp',null,['class'=>'form-control is-valid form-control-lg','id'=>'telp']) }}
            @endif
            @else
            {{ Form::text('telp',null,['class'=>'form-control form-control-lg','id'=>'telp']) }}
            @endif
        </div> 
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>