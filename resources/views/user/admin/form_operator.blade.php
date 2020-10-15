@if(isset($operator))
    {{ Form::hidden('id',$operator->id) }}
@endif
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nama','Nama') }}
            @if ($errors->any())
            @if ($errors->has('nama'))
            {{ Form::text('nama',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nama']) }}
            <div class="invalid-feedback">{{ $errors->first('nama') }}</div>
            @else
            {{ Form::text('nama',null,['class'=>'form-control form-control-lg is-valid','id'=>'nama']) }}
            @endif
            @else
            {{ Form::text('nama',null,['class'=>'form-control form-control-lg','id'=>'nama']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('username','Username') }}
            @if ($errors->any())
            @if ($errors->has('username'))
            {{ Form::text('username',null,['class'=>'form-control form-control-lg is-invalid','id'=>'username']) }}
            <div class="invalid-feedback">{{ $errors->first('username') }}</div>
            @else
            {{ Form::text('username',null,['class'=>'form-control form-control-lg is-valid','id'=>'username']) }}
            @endif
            @else
            {{ Form::text('username',null,['class'=>'form-control form-control-lg','id'=>'username']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('bagian','Bagian') }}
            @if ($errors->any())
            @if ($errors->has('bagian'))
            {{ Form::select('bagian',['front office'=>'Front Office','subbagian kemahasiswaan'=>'Subbagian Kemahasiswaan','subbagian pendidikan dan pengajaran'=>'Subbagian Pendidikan Dan Pengajaran','subbagian umum & bmn'=>'Subbagian Umum & BMN'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'jabatan','placeholder'=> '-- Pilih Jabatan --']) }}
            <div class="invalid-feedback">{{ $errors->first('bagian') }}</div>
            @else
            {{ Form::select('bagian',['front office'=>'Front Office','subbagian kemahasiswaan'=>'Subbagian Kemahasiswaan','subbagian pendidikan dan pengajaran'=>'Subbagian Pendidikan Dan Pengajaran','subbagian umum & bmn'=>'Subbagian Umum & BMN'],null,['class'=>'form-control form-control-lg is-valid','id'=>'jabatan','placeholder'=> '-- Pilih Jabatan --']) }}
            @endif
            @else
            {{ Form::select('bagian',['front office'=>'Front Office','subbagian kemahasiswaan'=>'Subbagian Kemahasiswaan','subbagian pendidikan dan pengajaran'=>'Subbagian Pendidikan Dan Pengajaran','subbagian umum & bmn'=>'Subbagian Umum & BMN'],null,['class'=>'form-control form-control-lg','id'=>'jabatan','placeholder'=> '-- Pilih Jabatan --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('status_aktif','Status Aktif') }}
            @if ($errors->any())
            @if ($errors->has('status_aktif'))
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'status_aktif','placeholder'=> '-- Pilih Status Aktif --']) }}
            <div class="invalid-feedback">{{ $errors->first('status_aktif') }}</div>
            @else
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],null,['class'=>'form-control form-control-lg is-valid','id'=>'status_aktif','placeholder'=> '-- Pilih Status Aktif --']) }}
            @endif
            @else
            {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],null,['class'=>'form-control form-control-lg','id'=>'status_aktif','placeholder'=> '-- Pilih Status Aktif --']) }}
            @endif
        </div>
        <div class="form-group password-group">
            @if($formPassword)
            {{ Form::label('password','Password') }}
            @if ($errors->any())
            @if ($errors->has('password'))
            {{ Form::password('password',['class'=>'form-control form-control-lg is-invalid','id'=>'password']) }}
            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
            @else
            {{ Form::password('password',['class'=>'form-control form-control-lg is-valid','id'=>'password']) }}
            @endif
            @else
            {{ Form::password('password',['class'=>'form-control form-control-lg','id'=>'password']) }}
            @endif
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            @if(!$formPassword)
            <a href="" class="btn btn-warning btn-sm btn-password">Ubah Password</a>
            @endif
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>