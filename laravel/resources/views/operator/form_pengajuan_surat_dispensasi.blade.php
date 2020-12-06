@php 
$jumlah = old('jumlah') ?? 1;
@endphp
@if (isset($pengajuanSurat))
{{ Form::hidden('id',$pengajuanSurat->id_surat_masuk) }}
@php
$jumlah = $pengajuanSurat->tahapanKegiatanDispensasi->count();    
@endphp
@endif

<div class="row">
    <div class="col-md-12">
      <div class="form-group ">
            {{ Form::label('nim','Mahasiswa') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::select('nim[]',$mahasiswa,isset($pengajuanSurat) ? $pengajuanSurat->mahasiswa : null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','multiple'=>"multiple"]) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::select('nim[]',$mahasiswa,isset($pengajuanSurat) ? $pengajuanSurat->mahasiswa : null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','multiple'=>"multiple"]) }}
            @endif
            @else
            {{ Form::select('nim[]',$mahasiswa,isset($pengajuanSurat) ? $pengajuanSurat->mahasiswa : null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','multiple'=>"multiple"]) }}
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
        <div class="form-group tahapan">
            {{ Form::hidden('jumlah',$jumlah,['id'=>'jumlah_tahapan_field']) }}
            <button type="button" class="btn-tambah-tahapan btn btn-outline-dark btn-sm mb-2 mr-2">
                <i class="mdi mdi-plus"></i>
            </button>
            {{ Form::label('tahapan kegiatan','Tahapan Kegiatan',['class'=>'mt-2']) }}
            <br>
            @for($i = 0; $i < $jumlah; $i++)
            @if(isset($pengajuanSurat))
                {{ Form::hidden('id_tahapan[]',$pengajuanSurat->tahapanKegiatanDispensasi[$i]->id) }}
            @endif
            <div class="form-row copy-tahapan-field mb-3" id="tahapan-row">
                <div class="col-md-12 mb-1">
                    @if ($errors->any())
                    @if ($errors->has('tahapan_kegiatan_'.$i))
                    {{ Form::text('tahapan_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tahapan_kegiatan:null,['class'=>'form-control is-invalid','id'=>'tahapan_kegiatan','placeholder'=>'Tahapan Kegiatan']) }}
                    <div class="invalid-feedback mb-2">{{ $errors->first('tahapan_kegiatan_'.$i) }}</div>
                    @else
                    {{ Form::text('tahapan_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tahapan_kegiatan:null,['class'=>'form-control is-valid','id'=>'tahapan_kegiatan','placeholder'=>'Tahapan Kegiatan']) }}
                    @endif
                    @else
                    {{ Form::text('tahapan_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tahapan_kegiatan:null,['class'=>'form-control','id'=>'tahapan_kegiatan','placeholder'=>'Tahapan Kegiatan']) }}
                    @endif    
                </div>
                <div class="col-md-6 mb-1">
                    @if ($errors->any())
                    @if ($errors->has('tempat_kegiatan_'.$i))
                    {{ Form::text('tempat_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tempat_kegiatan:null,['class'=>'form-control is-invalid','id'=>'tempat_kegiatan','placeholder'=>'Tempat Kegiatan']) }}
                    <div class="invalid-feedback">{{ $errors->first('tempat_kegiatan_'.$i) }}</div>
                    @else
                    {{ Form::text('tempat_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tempat_kegiatan:null,['class'=>'form-control is-valid','id'=>'tempat_kegiatan','placeholder'=>'Tempat Kegiatan']) }}
                    @endif
                    @else
                    {{ Form::text('tempat_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tempat_kegiatan:null,['class'=>'form-control','id'=>'tempat_kegiatan','placeholder'=>'Tempat Kegiatan']) }}
                    @endif    
                </div>
                <div class="col-md-3">
                    @if ($errors->any())
                    @if ($errors->has('tanggal_awal_kegiatan_'.$i))
                    {{ Form::text('tanggal_awal_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tanggal_awal_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_awal_kegiatan','placeholder'=>'Tanggal Awal Kegiatan','autocomplete'=>'off']) }}
                    <div class="invalid-feedback">{{ $errors->first('tanggal_awal_kegiatan_'.$i) }}</div>
                    @else
                    {{ Form::text('tanggal_awal_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tanggal_awal_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_awal_kegiatan','placeholder'=>'Tanggal Awal Kegiatan','autocomplete'=>'off']) }}
                    @endif
                    @else
                    {{ Form::text('tanggal_awal_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tanggal_awal_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_awal_kegiatan','placeholder'=>'Tanggal Awal Kegiatan','autocomplete'=>'off']) }}
                    @endif    
                </div>
                <div class="col-md-3">
                    @if ($errors->any())
                    @if ($errors->has('tanggal_akhir_kegiatan_'.$i))
                    {{ Form::text('tanggal_akhir_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tanggal_akhir_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_akhir_kegiatan','placeholder'=>'Tanggal Akhir Kegiatan','autocomplete'=>'off']) }}
                    <div class="invalid-feedback">{{ $errors->first('tanggal_akhir_kegiatan_'.$i) }}</div>
                    @else
                    {{ Form::text('tanggal_akhir_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tanggal_akhir_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_akhir_kegiatan','placeholder'=>'Tanggal Akhir Kegiatan','autocomplete'=>'off']) }}
                    @endif
                    @else
                    {{ Form::text('tanggal_akhir_kegiatan[]',isset($pengajuanSurat)?$pengajuanSurat->tahapanKegiatanDispensasi[$i]->tanggal_akhir_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_akhir_kegiatan','placeholder'=>'Tanggal Akhir Kegiatan','autocomplete'=>'off']) }}
                    @endif    
                </div>
            </div>
            @endfor
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>