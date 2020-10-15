@if (isset($tahunAkademik))
{{ Form::hidden('id',$tahunAkademik->id) }}
@endif
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('tahun_akademik','Tahun Akademik') }}
            @if ($errors->any())
            @if ($errors->has('tahun_akademik'))
            {{ Form::select('tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg is-invalid','id'=>'tahun_akademik','placeholder'=> '-- Pilih Tahun Akademik --']) }}
            <div class="invalid-feedback">{{ $errors->first('tahun_akademik') }}</div>
            @else
            {{ Form::select('tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg is-valid','id'=>'tahun_akademik','placeholder'=> '-- Pilih Tahun Akademik --']) }}
            @endif
            @else
            {{ Form::select('tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg','id'=>'tahun_akademik','placeholder'=> '-- Pilih Tahun Akademik --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('semester','Semester') }}
            @if ($errors->any())
            @if ($errors->has('semester'))
            {{ Form::select('semester',['ganjil'=>'Ganjil','genap'=>'Genap'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'semester','placeholder'=> '-- Pilih Semester --']) }}
            <div class="invalid-feedback">{{ $errors->first('semester') }}</div>
            @else
            {{ Form::select('semester',['ganjil'=>'Ganjil','genap'=>'Genap'],null,['class'=>'form-control form-control-lg is-valid','id'=>'semester','placeholder'=> '-- Pilih Semester --']) }}
            @endif
            @else
            {{ Form::select('semester',['ganjil'=>'Ganjil','genap'=>'Genap'],null,['class'=>'form-control form-control-lg','id'=>'semester','placeholder'=> '-- Pilih Semester --']) }}
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