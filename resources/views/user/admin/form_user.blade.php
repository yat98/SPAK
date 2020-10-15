<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nip','NIP') }}
            @if ($errors->any())
            @if ($errors->has('nip'))
            {{ Form::text('nip',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nip']) }}
            <div class="invalid-feedback">{{ $errors->first('nip') }}</div>
            @else
            {{ Form::text('nip',null,['class'=>'form-control form-control-lg is-valid','id'=>'nip']) }}
            @endif
            @else
            {{ Form::text('nip',null,['class'=>'form-control form-control-lg','id'=>'nip']) }}
            @endif
        </div>
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
            {{ Form::label('jabatan','Jabatan') }}
            @if ($errors->any())
            @if ($errors->has('jabatan'))
            {{ Form::select('jabatan',['dekan'=>'Dekan','wd1'=>'Wakil Dekan 1','wd2'=>'Wakil Dekan 2','wd3'=>'Wakil Dekan 3','kabag tata usaha'=>'Kabag Tata Usaha','kasubag kemahasiswaan'=>'Kasubag Kemahasiswaan','kasubag pendidikan dan pengajaran'=>'Kasubag Pendidikan Dan Pengajaran','kasubag umum & bmn'=>'Kasubag Umum & BMN'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'jabatan','placeholder'=> '-- Pilih Jabatan --']) }}
            <div class="invalid-feedback">{{ $errors->first('jabatan') }}</div>
            @else
            {{ Form::select('jabatan',['dekan'=>'Dekan','wd1'=>'Wakil Dekan 1','wd2'=>'Wakil Dekan 2','wd3'=>'Wakil Dekan 3','kabag tata usaha'=>'Kabag Tata Usaha','kasubag kemahasiswaan'=>'Kasubag Kemahasiswaan','kasubag pendidikan dan pengajaran'=>'Kasubag Pendidikan Dan Pengajaran','kasubag umum & bmn'=>'Kasubag Umum & BMN'],null,['class'=>'form-control form-control-lg is-valid','id'=>'jabatan','placeholder'=> '-- Pilih Jabatan --']) }}
            @endif
            @else
            {{ Form::select('jabatan',['dekan'=>'Dekan','wd1'=>'Wakil Dekan 1','wd2'=>'Wakil Dekan 2','wd3'=>'Wakil Dekan 3','kabag tata usaha'=>'Kabag Tata Usaha','kasubag kemahasiswaan'=>'Kasubag Kemahasiswaan','kasubag pendidikan dan pengajaran'=>'Kasubag Pendidikan Dan Pengajaran','kasubag umum & bmn'=>'Kasubag Umum & BMN'],null,['class'=>'form-control form-control-lg','id'=>'jabatan','placeholder'=> '-- Pilih Jabatan --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('pangkat','Pangkat') }}
            @if ($errors->any())
            @if ($errors->has('pangkat'))
            {{ Form::select('pangkat',['penata muda'=>'Penata Muda','penata muda tkt. I'=>'Penata Muda Tkt. I','penata'=>'Penata','penata tkt. I'=>'Penata Tkt. I','pembina'=>'Pembina','pembina tkt. I '=>'Pembina Tkt. I','pembina utama muda'=>'Pembina Utama Muda','pembina utama madya'=>'Pembina Utama Madya','pembina utama'=>'Pembina Utama'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'pangkat','placeholder'=> '-- Pilih Pangkat --']) }}
            <div class="invalid-feedback">{{ $errors->first('pangkat') }}</div>
            @else
            {{ Form::select('pangkat',['penata muda'=>'Penata Muda','penata muda tkt. I'=>'Penata Muda Tkt. I','penata'=>'Penata','penata tkt. I'=>'Penata Tkt. I','pembina'=>'Pembina','pembina tkt. I '=>'Pembina Tkt. I','pembina utama muda'=>'Pembina Utama Muda','pembina utama madya'=>'Pembina Utama Madya','pembina utama'=>'Pembina Utama'],null,['class'=>'form-control form-control-lg is-valid','id'=>'pangkat','placeholder'=> '-- Pilih Pangkat --']) }}
            @endif
            @else
            {{ Form::select('pangkat',['penata muda'=>'Penata Muda','penata muda tkt. I'=>'Penata Muda Tkt. I','penata'=>'Penata','penata tkt. I'=>'Penata Tkt. I','pembina'=>'Pembina','pembina tkt. I'=>'Pembina Tkt. I','pembina utama muda'=>'Pembina Utama Muda','pembina utama madya'=>'Pembina Utama Madya','pembina utama'=>'Pembina Utama'],null,['class'=>'form-control form-control-lg','id'=>'pangkat','placeholder'=> '-- Pilih Pangkat --']) }}
            @endif
        </div>
         <div class="form-group">
            {{ Form::label('golongan','Golongan') }}
            @if ($errors->any())
            @if ($errors->has('golongan'))
            {{ Form::select('golongan',['III/a'=>'III/a','III/b'=>'III/b','III/c'=>'III/c','III/d'=>'III/d','IV/a'=>'IV/a','IV/b'=>'IV/b','IV/c'=>'IV/c','IV/d'=>'IV/d','IV/e'=>'IV/e'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'golongan','placeholder'=> '-- Pilih Golongan --']) }}
            <div class="invalid-feedback">{{ $errors->first('golongan') }}</div>
            @else
            {{ Form::select('golongan',['III/a'=>'III/a','III/b'=>'III/b','III/c'=>'III/c','III/d'=>'III/d','IV/a'=>'IV/a','IV/b'=>'IV/b','IV/c'=>'IV/c','IV/d'=>'IV/d','IV/e'=>'IV/e'],null,['class'=>'form-control form-control-lg is-valid','id'=>'golongan','placeholder'=> '-- Pilih Golongan --']) }}
            @endif
            @else
            {{ Form::select('golongan',['III/a'=>'III/a','III/b'=>'III/b','III/c'=>'III/c','III/d'=>'III/d','IV/a'=>'IV/a','IV/b'=>'IV/b','IV/c'=>'IV/c','IV/d'=>'IV/d','IV/e'=>'IV/e'],null,['class'=>'form-control form-control-lg','id'=>'golongan','placeholder'=> '-- Pilih Golongan --']) }}
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