{{ Form::hidden('id_pengajuan',$pengajuanKegiatan->id) }}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nomor_agenda','Nomor Agenda') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nomor_agenda'))
            {{ Form::text('nomor_agenda',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_agenda']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nomor_agenda') }}</small></div>
            @else
            {{ Form::text('nomor_agenda',null,['class'=>'form-control form-control-lg','id'=>'nomor_agenda']) }}
            @endif
            @else
            {{ Form::text('nomor_agenda',null,['class'=>'form-control form-control-lg','id'=>'nomor_agenda']) }}
            @endif
        </div>  
        <div class="form-group">
            {{ Form::label('hal','Hal') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('hal'))
            {{ Form::text('hal',null,['class'=>'form-control form-control-lg is-invalid','id'=>'hal']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('hal') }}</small></div>
            @else
            {{ Form::text('hal',null,['class'=>'form-control form-control-lg','id'=>'hal']) }}
            @endif
            @else
            {{ Form::text('hal',null,['class'=>'form-control form-control-lg','id'=>'hal']) }}
            @endif
        </div>  
        <div class="form-group">
            {{ Form::label('tanggal_surat','Tanggal Surat') }}
            @if ($errors->any())
            @if ($errors->has('tanggal_surat'))
            {{ Form::text('tanggal_surat',isset($disposisiSurat)?$disposisiSurat->tanggal_wisuda->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_surat','placeholder'=>'yyyy-mm-dd','autocomplete'=>'off']) }}
            <div class="invalid-feedback">{{ $errors->first('tanggal_surat') }}</div>
            @else
            {{ Form::text('tanggal_surat',isset($disposisiSurat)?$disposisiSurat->tanggal_wisuda->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_surat','placeholder'=>'yyyy-mm-dd','autocomplete'=>'off']) }}
            @endif
            @else
            {{ Form::text('tanggal_surat',isset($disposisiSurat)?$disposisiSurat->tanggal_wisuda->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_surat','placeholder'=>'yyyy-mm-dd','autocomplete'=>'off']) }}
            @endif    
        </div>
        <div class="form-group">
            {{ Form::label('tanggal_terima','Tanggal Terima') }}
            @if ($errors->any())
            @if ($errors->has('tanggal_terima'))
            {{ Form::text('tanggal_terima',isset($disposisiSurat)?$disposisiSurat->tanggal_terima->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_terima','placeholder'=>'yyyy-mm-dd','autocomplete'=>'off']) }}
            <div class="invalid-feedback">{{ $errors->first('tanggal_terima') }}</div>
            @else
            {{ Form::text('tanggal_terima',isset($disposisiSurat)?$disposisiSurat->tanggal_terima->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_terima','placeholder'=>'yyyy-mm-dd','autocomplete'=>'off']) }}
            @endif
            @else
            {{ Form::text('tanggal_terima',isset($disposisiSurat)?$disposisiSurat->tanggal_terima->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_terima','placeholder'=>'yyyy-mm-dd','autocomplete'=>'off']) }}
            @endif    
        </div>
        <div class="form-group">
            {{ Form::label('keterangan','Keterangan') }}
            @if ($errors->any())
            @if ($errors->has('keterangan'))
            {{ Form::textarea('keterangan',null,['class'=>'form-control form-control-lg is-invalid','id'=>'keterangan','rows'=>'5']) }}
            <div class="invalid-feedback">{{ $errors->first('keterangan') }}</div>
            @else
            {{ Form::textarea('keterangan',null,['class'=>'form-control form-control-lg ','id'=>'keterangan','rows'=>'5']) }}
            @endif
            @else
            {{ Form::textarea('keterangan',null,['class'=>'form-control form-control-lg','id'=>'keterangan','rows'=>'5']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>