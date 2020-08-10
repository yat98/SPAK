@if (isset($kodeSurat))
{{ Form::hidden('id',$kodeSurat->id) }}
@endif
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('kode_surat','Kode Surat') }}
            @if ($errors->any())
            @if ($errors->has('kode_surat'))
            {{ Form::text('kode_surat',null,['class'=>'form-control form-control-lg is-invalid','id'=>'kode_surat','placeholder'=>'UN47.B5']) }}
            <div class="invalid-feedback">{{ $errors->first('kode_surat') }}</div>
            @else
            {{ Form::text('kode_surat',null,['class'=>'form-control form-control-lg is-valid','id'=>'kode_surat','placeholder'=>'UN47.B5']) }}
            @endif
            @else
            {{ Form::text('kode_surat',null,['class'=>'form-control form-control-lg','id'=>'kode_surat','placeholder'=>'UN47.B5']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('status_aktif','Status Aktif') }}
            @if ($errors->any())
            @if ($errors->has('status_aktif'))
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'status_aktif','placeholder'=> '-- Pilih Status Aktif--']) }}
            <div class="invalid-feedback">{{ $errors->first('status_aktif') }}</div>
            @else
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],null,['class'=>'form-control form-control-lg is-valid','id'=>'status_aktif','placeholder'=> '-- Pilih Status Aktif--']) }}
            @endif
            @else
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],null,['class'=>'form-control form-control-lg','id'=>'status_aktif','placeholder'=> '-- Pilih Status Aktif--']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>