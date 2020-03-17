@if (isset($tahunAkademik))
{{ Form::hidden('id',$tahunAkademik->id) }}
@endif
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('tahun_akademik','Tahun Akademik') }}
            @if ($errors->any())
            @if ($errors->has('tahun_akademik'))
            {{ Form::select('tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg is-invalid','id'=>'tahun_akademik']) }}
            <div class="invalid-feedback">{{ $errors->first('tahun_akademik') }}</div>
            @else
            {{ Form::select('tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg is-valid','id'=>'tahun_akademik']) }}
            @endif
            @else
            {{ Form::select('tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg','id'=>'tahun_akademik']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('semester','Semester') }}
            @if ($errors->any())
            @if ($errors->has('semester') || $errors->has('tahun_akademik'))
            {{ Form::select('semester',['ganjil'=>'Ganjil','genap'=>'Genap'],isset($tahunAkademik->semester) ? $tahunAkademik->semester : null,['class'=>'form-control form-control-lg is-invalid','id'=>'semester']) }}
            <div class="invalid-feedback">{{ $errors->first('semester') }}</div>
            <div class="invalid-feedback">{{ $errors->first('tahun_akademik') }}</div>
            @else
            {{ Form::select('semester',['ganjil'=>'Ganjil','genap'=>'Genap'],isset($tahunAkademik->semester) ? $tahunAkademik->semester : null,['class'=>'form-control form-control-lg is-valid','id'=>'semester']) }}
            @endif
            @else
            {{ Form::select('semester',['ganjil'=>'Ganjil','genap'=>'Genap'],isset($tahunAkademik->semester) ? $tahunAkademik->semester : null,['class'=>'form-control form-control-lg','id'=>'semester']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('status_aktif','Status Aktif') }}
            @if ($errors->any())
            @if ($errors->has('status_aktif'))
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],isset($tahunAkademik->status_aktif) ? strtolower($tahunAkademik->status_aktif) : null,['class'=>'form-control form-control-lg is-invalid','id'=>'status_aktif']) }}
            <div class="invalid-feedback">{{ $errors->first('status_aktif') }}</div>
            @else
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],isset($tahunAkademik->status_aktif) ? strtolower($tahunAkademik->status_aktif) : null,['class'=>'form-control form-control-lg is-valid','id'=>'status_aktif']) }}
            @endif
            @else
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],isset($tahunAkademik->status_aktif) ? strtolower($tahunAkademik->status_aktif) : null,['class'=>'form-control form-control-lg','id'=>'status_aktif']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>