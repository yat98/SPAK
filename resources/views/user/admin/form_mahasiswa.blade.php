<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            {{ Form::label('nim','NIM') }}
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::text('nim',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nim']) }}
            <div class="invalid-feedback">{{ $errors->first('nim') }}</div>
            @else
            {{ Form::text('nim',null,['class'=>'form-control form-control-lg is-valid','id'=>'nim']) }}
            @endif
            @else
            {{ Form::text('nim',null,['class'=>'form-control form-control-lg','id'=>'nim']) }}
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
        <div class="form-row">
            <div class="form-group col-md-7">
                {{ Form::label('tempat_lahir','Tempat Lahir') }}
                @if ($errors->any())
                @if ($errors->has('tempat_lahir'))
                {{ Form::text('tempat_lahir',null,['class'=>'form-control form-control-lg is-invalid','id'=>'tempat_lahir']) }}
                <div class="invalid-feedback">{{ $errors->first('tempat_lahir') }}</div>
                @else
                {{ Form::text('tempat_lahir',null,['class'=>'form-control form-control-lg is-valid','id'=>'tempat_lahir']) }}
                @endif
                @else
                {{ Form::text('tempat_lahir',null,['class'=>'form-control form-control-lg','id'=>'tempat_lahir']) }}
                @endif
            </div>
            <div class="form-group col-md-5">
                {{ Form::label('tanggal_lahir','Tanggal Lahir') }}
                @if ($errors->any())
                @if ($errors->has('tanggal_lahir'))
                {{ Form::text('tanggal_lahir',isset($mahasiswa)?$mahasiswa->tanggal_lahir->format('Y-m-d'):null,['class'=>'tanggal form-control is-invalid','id'=>'tanggal_lahir','placeholder'=>'yyyy-mm-dd']) }}
                <div class="invalid-feedback">{{ $errors->first('tanggal_lahir') }}</div>
                @else
                {{ Form::text('tanggal_lahir',isset($mahasiswa)?$mahasiswa->tanggal_lahir->format('Y-m-d'):null,['class'=>'tanggal form-control is-valid','id'=>'tanggal_lahir','placeholder'=>'yyyy-mm-dd']) }}
                @endif
                @else
                {{ Form::text('tanggal_lahir',isset($mahasiswa)?$mahasiswa->tanggal_lahir->format('Y-m-d'):null,['class'=>'tanggal form-control','id'=>'tanggal_lahir','placeholder'=>'yyyy-mm-dd']) }}
                @endif 
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('sex','Jenis Kelamin') }}
            @if ($errors->any())
            @if ($errors->has('sex'))
            {{ Form::select('sex',['L'=>'Laki-laki','P'=>'Perempuan'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'sex','placeholder'=> '-- Pilih Jenis Kelamin --']) }}
            <div class="invalid-feedback">{{ $errors->first('sex') }}</div>
            @else
            {{ Form::select('sex',['L'=>'Laki-laki','P'=>'Perempuan'],null,['class'=>'form-control form-control-lg is-valid','id'=>'sex','placeholder'=> '-- Pilih Jenis Kelamin --']) }}
            @endif
            @else
            {{ Form::select('sex',['L'=>'Laki-laki','P'=>'Perempuan'],null,['class'=>'form-control form-control-lg','id'=>'sex','placeholder'=> '-- Pilih Jenis Kelamin --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('angkatan','Angkatan') }}
            @if ($errors->any())
            @if ($errors->has('angkatan'))
            {{ Form::select('angkatan',$angkatan,null,['class'=>'form-control form-control-lg is-invalid','id'=>'angkatan','placeholder'=> '-- Pilih Angkatan --']) }}
            <div class="invalid-feedback">{{ $errors->first('angkatan') }}</div>
            @else
            {{ Form::select('angkatan',$angkatan,null,['class'=>'form-control form-control-lg is-valid','id'=>'angkatan','placeholder'=> '-- Pilih Angkatan --']) }}
            @endif
            @else
            {{ Form::select('angkatan',$angkatan,null,['class'=>'form-control form-control-lg','id'=>'angkatan','placeholder'=> '-- Pilih Angkatan --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('ipk','IPK') }}
            @if ($errors->any())
            @if ($errors->has('ipk'))
            {{ Form::text('ipk',null,['class'=>'form-control form-control-lg is-invalid','id'=>'strata']) }}
            <div class="invalid-feedback">{{ $errors->first('ipk') }}</div>
            @else
            {{ Form::text('ipk',null,['class'=>'form-control form-control-lg is-valid','id'=>'strata']) }}
            @endif
            @else
            {{ Form::number('ipk',null,['class'=>'form-control form-control-lg','id'=>'strata','min'=>0,'max'=>'4.00','step'=>'0.01' ]) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('id_prodi','Program Studi') }}
            @if ($errors->any())
            @if ($errors->has('id_prodi'))
            {{ Form::select('id_prodi',$prodiList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'id_prodi','placeholder'=> '-- Pilih Program Studi --']) }}
            <div class="invalid-feedback">{{ $errors->first('id_prodi') }}</div>
            @else
            {{ Form::select('id_prodi',$prodiList,null,['class'=>'form-control form-control-lg is-valid','id'=>'id_prodi','placeholder'=> '-- Pilih Program Studi --']) }}
            @endif
            @else
            {{ Form::select('id_prodi',$prodiList,null,['class'=>'form-control form-control-lg','id'=>'id_prodi','placeholder'=> '-- Pilih Program Studi --']) }}
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