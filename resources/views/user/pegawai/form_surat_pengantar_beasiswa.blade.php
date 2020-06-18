@if (isset($suratBeasiswa))
{{ Form::hidden('id',$suratBeasiswa->id) }}
@endif

<div class="row">
    <div class="col-md-12">
      <div class="form-group ">
            {{ Form::label('nim','Mahasiswa') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::select('nim[]',$mahasiswa,isset($suratBeasiswa) ? $suratBeasiswa->mahasiswa : null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','multiple'=>"multiple"]) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::select('nim[]',$mahasiswa,isset($suratBeasiswa) ? $suratBeasiswa->mahasiswa : null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','multiple'=>"multiple"]) }}
            @endif
            @else
            {{ Form::select('nim[]',$mahasiswa,isset($suratBeasiswa) ? $suratBeasiswa->mahasiswa : null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','multiple'=>"multiple"]) }}
            @endif
      </div> 
      <div class="form-group">
            {{ Form::label('id_surat_masuk','Nomor Surat Masuk') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('id_surat_masuk'))
            {{ Form::select('id_surat_masuk',$suratMasuk,null,['class'=>'select form-control form-control-lg is-invalid','id'=>'id_surat_masuk','placeholder'=>'-- Pilih Nomor Surat Masuk --']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('id_surat_masuk') }}</small></div>
            @else
            {{ Form::select('id_surat_masuk',$suratMasuk,null,['class'=>'select form-control form-control-lg is-valid','id'=>'id_surat_masuk','placeholder'=>'-- Pilih Nomor Surat Masuk --']) }}
            @endif
            @else
            {{ Form::select('id_surat_masuk',$suratMasuk,null,['class'=>'select form-control form-control-lg','id'=>'id_surat_masuk','placeholder'=>'-- Pilih Nomor Surat Masuk --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('nomor_surat','Nomor Surat') }}
            <div class="form-row">
                <div class="col-md-2 col-sm-6 mt-1">
                {{ Form::text('tipe_surat','B',['class'=>'form-control form-control-lg','disabled'=>'disabled']) }}
                </div>
                <div class="col-md-4 col-sm-6 mt-1">
                @if(!isset($suratBeasiswa))
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
                    {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg','id'=>'nomor_surat','readonly','readonly']) }}
                @endif
                </div>
                <div class="col-md col-sm-6 mt-1">
                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg','id'=>'nomor_surat','readonly'=>'readonly']) }}
                </div>
                <div class="col-md-3 col-sm-6 mt-1">
                {{ Form::text('tahun',isset($suratBeasiswa)?$suratBeasiswa->created_at->format('Y'):date('Y') ,['class'=>'form-control form-control-lg','disabled'=>'disabled']) }}
                </div>
            </div>    
        </div> 
        <div class="form-group">
            {{ Form::label('hal','Hal') }}
            @if ($errors->any())
            @if ($errors->has('hal'))
            {{ Form::text('hal',null,['class'=>'form-control form-control-lg is-invalid','id'=>'hal']) }}
            <div class="invalid-feedback">{{ $errors->first('hal') }}</div>
            @else
            {{ Form::text('hal',null,['class'=>'form-control form-control-lg is-valid','id'=>'hal']) }}
            @endif
            @else
            {{ Form::text('hal',null,['class'=>'form-control form-control-lg','id'=>'hal']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('nip','Tanda Tangan') }}
            @if ($errors->any())
            @if ($errors->has('nip'))
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nip','placeholder'=> '-- Pilih Tanda Tangan --']) }}
            <div class="invalid-feedback">{{ $errors->first('nip') }}</div>
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-valid','id'=>'nip','placeholder'=> '-- Pilih Tanda Tangan --']) }}
            @endif
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg','id'=>'nip','placeholder'=> '-- Pilih Tanda Tangan --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>