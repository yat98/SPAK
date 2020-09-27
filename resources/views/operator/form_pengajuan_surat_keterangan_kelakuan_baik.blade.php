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
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>