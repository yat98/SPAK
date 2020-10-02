@if (isset($pengajuanSuratPindah))
{{ Form::hidden('id_pengajuan',$pengajuanSuratPindah->id) }}
@endif
{{ Form::hidden('id_operator',Auth::user()->id) }}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nomor_surat','Nomor Surat') }}
            <div class="form-row">
                <div class="col">
                @if ($errors->any())
                @if ($errors->has('nomor_surat'))
                {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_surat']) }}
                <div class="text-danger-red mt-1"><small>{{ $errors->first('nomor_surat') }}</small></div>
                @else
                {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg is-valid','id'=>'nomor_surat']) }}
                @endif
                @else
                {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg','id'=>'nomor_surat']) }}
                @endif
                </div>
                <div class="col">
                @if ($errors->any())
                @if ($errors->has('id_kode_surat'))
                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_surat','readonly'=>'readonly']) }}
                <div class="text-danger-red mt-1"><small>{{ $errors->first('id_kode_surat') }}</small></div>
                @else
                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg is-valid','id'=>'nomor_surat','readonly'=>'readonly']) }}
                @endif
                @else
                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg','id'=>'nomor_surat','readonly'=>'readonly']) }}
                @endif
                </div>
            </div>    
        </div> 
        <div class="form-group">
            {{ Form::label('nip','Tanda Tangan') }}
            @if ($errors->any())
            @if ($errors->has('nip'))
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nip']) }}
            <div class="invalid-feedback">{{ $errors->first('nip') }}</div>
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-valid','id'=>'nip']) }}
            @endif
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg','id'=>'nip']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit('Tambah',['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>