@if (isset($waktuCuti))
{{ Form::hidden('id',$waktuCuti->id) }}
@endif
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            {{ Form::label('id_tahun_akademik','Tahun Akademik') }}
            @if ($errors->any())
            @if ($errors->has('id_tahun_akademik'))
            {{ Form::select('id_tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg is-invalid','id'=>'tahun_akademik','readonly'=>'readonly']) }}
            <div class="invalid-feedback">{{ $errors->first('id_tahun_akademik') }}</div>
            @else
            {{ Form::select('id_tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg is-valid','id'=>'tahun_akademik','readonly'=>'readonly']) }}
            @endif
            @else
            {{ Form::select('id_tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg','id'=>'tahun_akademik','readonly'=>'readonly']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('waktu_cuti','Waktu Cuti') }}
            <br>
            <div class="form-row">
                <div class="col-md-6">
                    @if ($errors->any())
                    @if ($errors->has('tanggal_awal_cuti'))
                    {{ Form::text('tanggal_awal_cuti',isset($waktuCuti)?$waktuCuti->tanggal_awal_cuti->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_awal_cuti','placeholder'=>'Tanggal Awal Cuti']) }}
                    <div class="invalid-feedback">{{ $errors->first('tanggal_awal_cuti') }}</div>
                    @else
                    {{ Form::text('tanggal_awal_cuti',isset($waktuCuti)?$waktuCuti->tanggal_awal_cuti->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_awal_cuti','placeholder'=>'Tanggal Awal Cuti']) }}
                    @endif
                    @else
                    {{ Form::text('tanggal_awal_cuti',isset($waktuCuti)?$waktuCuti->tanggal_awal_cuti->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_awal_cuti','placeholder'=>'Tanggal Awal Cuti']) }}
                    @endif    
                </div>
                <div class="col-md-6">
                    @if ($errors->any())
                    @if ($errors->has('tanggal_akhir_cuti'))
                    {{ Form::text('tanggal_akhir_cuti',isset($waktuCuti)?$waktuCuti->tanggal_akhir_cuti->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_akhir_cuti','placeholder'=>'Tanggal Akhir Cuti']) }}
                    <div class="invalid-feedback">{{ $errors->first('tanggal_akhir_cuti') }}</div>
                    @else
                    {{ Form::text('tanggal_akhir_cuti',isset($waktuCuti)?$waktuCuti->tanggal_akhir_cuti->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_akhir_cuti','placeholder'=>'Tanggal Akhir Kegiatan']) }}
                    @endif
                    @else
                    {{ Form::text('tanggal_akhir_cuti',isset($waktuCuti)?$waktuCuti->tanggal_akhir_cuti->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_akhir_cuti','placeholder'=>'Tanggal Akhir Cuti']) }}
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