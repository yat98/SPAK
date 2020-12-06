@if (isset($pengajuanSurat))
{{ Form::hidden('id',$pengajuanSurat->id) }}
@endif
{{ Form::hidden('id_operator',Auth::user()->id) }}
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
                    {{ Form::text('tanggal_awal_kegiatan',isset($pengajuanSurat)?$pengajuanSurat->tanggal_awal_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_awal_kegiatan','placeholder'=>'Tanggal Awal Kegiatan','autocomplete'=>'off']) }}
                    <div class="invalid-feedback">{{ $errors->first('tanggal_awal_kegiatan') }}</div>
                    @else
                    {{ Form::text('tanggal_awal_kegiatan',isset($pengajuanSurat)?$pengajuanSurat->tanggal_awal_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_awal_kegiatan','placeholder'=>'Tanggal Awal Kegiatan','autocomplete'=>'off']) }}
                    @endif
                    @else
                    {{ Form::text('tanggal_awal_kegiatan',isset($pengajuanSurat)?$pengajuanSurat->tanggal_awal_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_awal_kegiatan','placeholder'=>'Tanggal Awal Kegiatan','autocomplete'=>'off']) }}
                    @endif    
                </div>
                <div class="col-md-6">
                    @if ($errors->any())
                    @if ($errors->has('tanggal_akhir_kegiatan'))
                    {{ Form::text('tanggal_akhir_kegiatan',isset($pengajuanSurat)?$pengajuanSurat->tanggal_akhir_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_akhir_kegiatan','placeholder'=>'Tanggal Akhir Kegiatan','autocomplete'=>'off']) }}
                    <div class="invalid-feedback">{{ $errors->first('tanggal_akhir_kegiatan') }}</div>
                    @else
                    {{ Form::text('tanggal_akhir_kegiatan',isset($pengajuanSurat)?$pengajuanSurat->tanggal_akhir_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_akhir_kegiatan','placeholder'=>'Tanggal Akhir Kegiatan','autocomplete'=>'off']) }}
                    @endif
                    @else
                    {{ Form::text('tanggal_akhir_kegiatan',isset($pengajuanSurat)?$pengajuanSurat->tanggal_akhir_kegiatan->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_akhir_kegiatan','placeholder'=>'Tanggal Akhir Kegiatan','autocomplete'=>'off']) }}
                    @endif    
                </div>
            </div>
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>