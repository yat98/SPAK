<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nim','Mahasiswa') }}
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::select('nim',$mahasiswaList,null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','placeholder'=> '-- Pilih Mahasiswa --']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::select('nim',$mahasiswaList,null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','placeholder'=> '-- Pilih Mahasiswa --']) }}
            @endif
            @else
            {{ Form::select('nim',$mahasiswaList,null,['class'=>'form-control form-control-lg','id'=>'mahasiswa_list','placeholder'=> '-- Pilih Mahasiswa --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('jabatan','Jabatan') }}
            @if ($errors->any())
            @if ($errors->has('jabatan'))
            {{ Form::select('jabatan',['ketua'=>'Ketua','sekretaris'=>'Sekretaris','bendahara'=>'Bendahara'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'jabatan','placeholder'=>'-- Pilih Jabatan --']) }}
            <div class="invalid-feedback">{{ $errors->first('jabatan') }}</div>
            @else
            {{ Form::select('jabatan',['ketua'=>'Ketua','sekretaris'=>'Sekretaris','bendahara'=>'Bendahara'],null,['class'=>'form-control form-control-lg is-valid','id'=>'jabatan','placeholder'=>'-- Pilih Jabatan --']) }}
            @endif
            @else
            {{ Form::select('jabatan',['ketua'=>'Ketua','sekretaris'=>'Sekretaris','bendahara'=>'Bendahara'],null,['class'=>'form-control form-control-lg','id'=>'jabatan','placeholder'=>'-- Pilih Jabatan --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('id_ormawa','Ormawa') }}
            @if ($errors->any())
            @if ($errors->has('id_ormawa'))
            {{ Form::select('id_ormawa',$ormawaList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_ormawa','placeholder'=>'-- Pilih Ormawa --']) }}
            <div class="invalid-feedback">{{ $errors->first('id_ormawa') }}</div>
            @else
            {{ Form::select('id_ormawa',$ormawaList,null,['class'=>'form-control form-control-lg is-valid','id'=>'id_ormawa','placeholder'=>'-- Pilih Ormawa --']) }}
            @endif
            @else
            {{ Form::select('id_ormawa',$ormawaList,null,['class'=>'form-control form-control-lg','id'=>'id_ormawa','placeholder'=>'-- Pilih Ormawa --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('status_aktif','Status Aktif') }}
            @if ($errors->any())
            @if ($errors->has('status_aktif'))
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'status','placeholder'=> '-- Pilih Status Aktif--']) }}
            <div class="invalid-feedback">{{ $errors->first('status_aktif') }}</div>
            @else
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],null,['class'=>'form-control form-control-lg is-valid','id'=>'status','placeholder'=> '-- Pilih Status Aktif--']) }}
            @endif
            @else
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],null,['class'=>'form-control form-control-lg','id'=>'status','placeholder'=> '-- Pilih Status Aktif--']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>