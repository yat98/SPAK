@if (isset($suratRekomendasi))
{{ Form::hidden('id',$suratRekomendasi->id) }}
@endif
<div class="row">
    <div class="col-md-12">
      <div class="form-group ">
            {{ Form::label('nim','Mahasiswa') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::select('nim[]',$mahasiswa,isset($suratRekomendasi) ? $suratRekomendasi->mahasiswa : null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','multiple'=>"multiple"]) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::select('nim[]',$mahasiswa,isset($suratRekomendasi) ? $suratRekomendasi->mahasiswa : null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','multiple'=>"multiple"]) }}
            @endif
            @else
            {{ Form::select('nim[]',$mahasiswa,isset($suratRekomendasi) ? $suratRekomendasi->mahasiswa : null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','multiple'=>"multiple"]) }}
            @endif
      </div> 
      <div class="form-group">
            {{ Form::label('nomor_surat','Nomor Surat') }}
            <div class="form-row">
                <div class="col-md-4 col-sm-6 mt-1">
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
                <div class="col-md col-sm-6 mt-1">
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
                <div class="col-md-3 col-sm-6 mt-1">
                {{ Form::text('tahun',isset($suratRekomendasi)?$suratRekomendasi->created_at->format('Y'):date('Y') ,['class'=>'form-control form-control-lg','disabled'=>'disabled']) }}
                </div>
            </div>    
        </div> 
        <div class="form-group">
            {{ Form::label('nama_kegiatan','Nama Kegiatan') }}
            @if ($errors->any())
            @if ($errors->has('nama_kegiatan'))
            {{ Form::text('nama_kegiatan',null,['class'=>'form-control form-control-lg is-invalid','id'=>'perihal']) }}
            <div class="invalid-feedback">{{ $errors->first('nama_kegiatan') }}</div>
            @else
            {{ Form::text('nama_kegiatan',null,['class'=>'form-control form-control-lg is-valid','id'=>'perihal']) }}
            @endif
            @else
            {{ Form::text('nama_kegiatan',null,['class'=>'form-control form-control-lg','id'=>'perihal']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('tempat_kegiatan','Tempat Kegiatan') }}
            @if ($errors->any())
            @if ($errors->has('tempat_kegiatan'))
            {{ Form::text('tempat_kegiatan',null,['class'=>'form-control is-invalid','id'=>'tempat_kegiatan','placeholder'=>'Tempat kegiatan']) }}
            <div class="invalid-feedback">{{ $errors->first('tempat_kegiatan') }}</div>
            @else
            {{ Form::text('tempat_kegiatan',null,['class'=>'form-control is-valid','id'=>'tempat_kegiatan','placeholder'=>'Tempat kegiatan']) }}
            @endif
            @else
            {{ Form::text('tempat_kegiatan',null,['class'=>'form-control','id'=>'tempat_kegiatan','placeholder'=>'Tempat kegiatan']) }}
            @endif    
        </div>
        <div class="form-group">
            {{ Form::label('tanggal_kegiatan','Tanggal Kegiatan') }}
            <br>
            <div class="form-row">
                <div class="col-md-6">
                    @if ($errors->any())
                    @if ($errors->has('tanggal_awal_kegiatan'))
                    {{ Form::text('tanggal_awal_kegiatan',null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_awal_kegiatan','placeholder'=>'Tanggal Awal Kegiatan']) }}
                    <div class="invalid-feedback">{{ $errors->first('tanggal_awal_kegiatan') }}</div>
                    @else
                    {{ Form::text('tanggal_awal_kegiatan',null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_awal_kegiatan','placeholder'=>'Tanggal Awal Kegiatan']) }}
                    @endif
                    @else
                    {{ Form::text('tanggal_awal_kegiatan',null,['class'=>'tanggal form-control','id'=>'tanggal_awal_kegiatan','placeholder'=>'Tanggal Awal Kegiatan']) }}
                    @endif    
                </div>
                <div class="col-md-6">
                    @if ($errors->any())
                    @if ($errors->has('tanggal_akhir_kegiatan'))
                    {{ Form::text('tanggal_akhir_kegiatan',null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_akhir_kegiatan','placeholder'=>'Tanggal Akhir Kegiatan']) }}
                    <div class="invalid-feedback">{{ $errors->first('tanggal_akhir_kegiatan') }}</div>
                    @else
                    {{ Form::text('tanggal_akhir_kegiatan',null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_akhir_kegiatan','placeholder'=>'Tanggal Akhir Kegiatan']) }}
                    @endif
                    @else
                    {{ Form::text('tanggal_akhir_kegiatan',null,['class'=>'tanggal form-control','id'=>'tanggal_akhir_kegiatan','placeholder'=>'Tanggal Akhir Kegiatan']) }}
                    @endif    
                </div>
            </div>
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