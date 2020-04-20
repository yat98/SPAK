@php
    $attrNim;
    if($nimReadOnly){
        $attrNim = [
            'class'=>'form-control form-control-lg',
            'id'=>'mahasiswa_list_readonly',
            'placeholder'=> '-- Pilih Mahasiswa --',
            'readonly'=>'readonly'
        ];
    }else{
        $attrNim = [
            'class'=>'form-control form-control-lg',
            'id'=>'mahasiswa_list',
            'placeholder'=> '-- Pilih Mahasiswa --',
        ];
    }
@endphp
<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {{ Form::label('nim','NIM') }}
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::select('nim',$mahasiswaList,null,$attrNim) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::select('nim',$mahasiswaList,null,$attrNim) }}
            @endif
            @else
            {{ Form::select('nim',$mahasiswaList,null,$attrNim) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('tahun_akademik','Tahun Akademik') }}
            @if ($errors->any())
            @if ($errors->has('id_tahun_akademik'))
            {{ Form::select('id_tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_tahun_akademik','readonly'=>'readonly']) }}
            <div class="invalid-feedback">{{ $errors->first('id_tahun_akademik') }}</div>
            @else
            {{ Form::select('id_tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg is-valid','id'=>'id_tahun_akademik','readonly'=>'readonly']) }}
            @endif
            @else
            {{ Form::select('id_tahun_akademik',$tahun,null,['class'=>'form-control form-control-lg','id'=>'id_tahun_akademik','readonly'=>'readonly']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('status','Status') }}
            @if ($errors->any())
            @if ($errors->has('status'))
            {{ Form::select('status',['aktif'=>'Aktif','non aktif'=>'Non Aktif','cuti'=>'Cuti','drop out'=>'Drop Out','lulus'=>'Lulus','keluar'=>'Keluar'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'status','placeholder'=> '-- Pilih Status Aktif--']) }}
            <div class="invalid-feedback">{{ $errors->first('status') }}</div>
            @else
            {{ Form::select('status',['aktif'=>'Aktif','non aktif'=>'Non Aktif','cuti'=>'Cuti','drop out'=>'Drop Out','lulus'=>'Lulus','keluar'=>'Keluar'],null,['class'=>'form-control form-control-lg is-valid','id'=>'status','placeholder'=> '-- Pilih Status Aktif--']) }}
            @endif
            @else
            {{ Form::select('status',['aktif'=>'Aktif','non aktif'=>'Non Aktif','cuti'=>'Cuti','drop out'=>'Drop Out','lulus'=>'Lulus','keluar'=>'Keluar'],null,['class'=>'form-control form-control-lg','id'=>'status','placeholder'=> '-- Pilih Status Aktif--']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>