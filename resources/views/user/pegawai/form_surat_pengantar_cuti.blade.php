@if (isset($suratCuti))
{{ Form::hidden('id',$suratCuti->id) }}
@endif
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nomor_surat','Nomor Surat') }}
            <div class="form-row">
                <div class="col-md-4 col-sm-6 mt-1">
                @if(!isset($suratCuti))
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
                @else
                    {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg','id'=>'nomor_surat','readonly'=>'readonly']) }}
                @endif
                </div>
                <div class="col-md col-sm-6 mt-1">
                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg','id'=>'nomor_surat','readonly'=>'readonly']) }}
                </div>
                <div class="col-md-3 col-sm-6 mt-1">
                {{ Form::text('tahun',isset($suratDispensasi)?$suratDispensasi->created_at->format('Y'):date('Y') ,['class'=>'form-control form-control-lg','disabled'=>'disabled']) }}
                </div>
            </div>    
        </div> 
        <div class="form-group">
            {{ Form::label('id_waktu_cuti','Tahun Akademik') }}
            @if ($errors->any())
            @if ($errors->has('id_waktu_cuti'))
            {{ Form::select('id_waktu_cuti',$waktuCuti,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_waktu_cuti','placeholder'=>'-- Pilih Tahun Akademik -- ']) }}
            <div class="invalid-feedback">{{ $errors->first('id_waktu_cuti') }}</div>
            @else
            {{ Form::select('id_waktu_cuti',$waktuCuti,null,['class'=>'form-control form-control-lg ','id'=>'id_waktu_cuti','placeholder'=>'-- Pilih Tahun Akademik -- ']) }}
            @endif
            @else
            {{ Form::select('id_waktu_cuti',$waktuCuti,null,['class'=>'form-control form-control-lg','id'=>'id_waktu_cuti','placeholder'=>'-- Pilih Tahun Akademik -- ']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('nip','Tanda Tangan') }}
            @if ($errors->any())
            @if ($errors->has('nip'))
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nip','readonly'=>'readonly']) }}
            <div class="invalid-feedback">{{ $errors->first('nip') }}</div>
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-valid','id'=>'nip','readonly'=>'readonly']) }}
            @endif
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg','id'=>'nip','readonly'=>'readonly']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>