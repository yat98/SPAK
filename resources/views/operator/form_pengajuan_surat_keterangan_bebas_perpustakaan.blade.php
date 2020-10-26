<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nim','Mahasiswa') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::select('nim',$mahasiswa,null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','placeholder'=> '-- Pilih Mahasiswa --']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::select('nim',$mahasiswa,null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','placeholder'=> '-- Pilih Mahasiswa --']) }}
            @endif
            @else
            {{ Form::select('nim',$mahasiswa,null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','placeholder'=> '-- Pilih Mahasiswa --']) }}
            @endif
        </div> 
        <div class="form-group">
            {{ Form::label('telp','Telp') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('telp'))
            {{ Form::text('telp',null,['class'=>'form-control form-control-lg is-invalid','id'=>'telp']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('telp') }}</small></div>
            @else
            {{ Form::text('telp',null,['class'=>'form-control is-valid form-control-lg','id'=>'telp']) }}
            @endif
            @else
            {{ Form::text('telp',null,['class'=>'form-control form-control-lg','id'=>'telp']) }}
            @endif
        </div> 
        <div class="form-group">
            {{ Form::label('alamat','Alamat') }}
            @if ($errors->any())
            @if ($errors->has('alamat'))
            {{ Form::textarea('alamat',null,['class'=>'form-control form-control-lg is-invalid','id'=>'alamat','rows'=>'7']) }}
            <div class="invalid-feedback">{{ $errors->first('alamat') }}</div>
            @else
            {{ Form::textarea('alamat',null,['class'=>'form-control form-control-lg ','id'=>'alamat','rows'=>'7']) }}
            @endif
            @else
            {{ Form::textarea('alamat',null,['class'=>'form-control form-control-lg','id'=>'alamat','rows'=>'7']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>