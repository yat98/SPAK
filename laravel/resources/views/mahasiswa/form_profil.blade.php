<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nim','NIM') }}
            {{ Form::text('nim',null,['class'=>'form-control form-control-lg','id'=>'nim','readonly'=>'readonly']) }}
        </div>
        <div class="form-group">
            {{ Form::label('nama','Nama') }}
            {{ Form::text('nama',null,['class'=>'form-control form-control-lg','id'=>'nama','readonly'=>'readonly']) }}
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
            {{ Form::label('angkatan','Angkatan') }}            
            {{ Form::text('angkatan',$mahasiswa->angkatan,['class'=>'form-control form-control-lg','id'=>'angkatan','disabled'=>'disabled']) }}
        </div>
        <div class="form-group">
            {{ Form::label('jurusan','Jurusan') }}
            {{ Form::text('jurusan',$mahasiswa->prodi->jurusan->nama_jurusan,['class'=>'form-control form-control-lg','id'=>'jurusan','disabled'=>'disabled']) }}
        </div>
         <div class="form-group">
            {{ Form::label('prodi','Program Studi') }}
            {{ Form::text('jurusan',$mahasiswa->prodi->nama_prodi,['class'=>'form-control form-control-lg','id'=>'prodi','disabled'=>'disabled']) }}
        </div>
        <div class="form-group">
            {{ Form::label('jenis_kelamin','Jenis Kelamin') }}
            {{ Form::text('jenis_kelamin',($mahasiswa->sex == 'L')?'Laki-laki':'Perempuan',['class'=>'form-control form-control-lg','id'=>'jenis_kelamin','disabled'=>'disabled']) }}
        </div>
         <div class="form-group">
            {{ Form::label('ipk','IPK') }}
            {{ Form::text('ipk',$mahasiswa->ipk,['class'=>'form-control form-control-lg','id'=>'ipk','disabled'=>'disabled']) }}
        </div>
        @if($mahasiswa->tahunAkademik->count() > 0)
            <div class="form-group pt-3">
                {{ Form::label('status_mahasiswa','Status Mahasiswa') }}
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>Tahun Akademik</td>
                            <td>Status</td>
                        </tr>
                        @foreach($mahasiswa->tahunAkademik as $statusMahasiswa)   
                        <tr>
                                <td>{{ $statusMahasiswa->tahun_akademik }} - {{ ucwords($statusMahasiswa->semester) }}</td> 
                                <td>
                                    @if ($statusMahasiswa->pivot->status == 'aktif')
                                        <label class="badge badge-gradient-info">{{ ucwords($statusMahasiswa->pivot->status) }}</label>
                                    @elseif($statusMahasiswa->pivot->status == 'lulus')
                                        <label class="badge badge-gradient-success">{{ ucwords($statusMahasiswa->pivot->status) }}</label>
                                    @elseif($statusMahasiswa->pivot->status == 'drop out' || $statusMahasiswa->pivot->status  == 'keluar')
                                        <label class="badge badge-gradient-danger">{{ ucwords($statusMahasiswa->pivot->status) }}</label>
                                    @elseif($statusMahasiswa->pivot->status == 'cuti')
                                        <label class="badge badge-gradient-warning text-dark">{{ ucwords($statusMahasiswa->pivot->status) }}</label>
                                    @else
                                        <label class="badge badge-gradient-dark">{{ ucwords($statusMahasiswa->pivot->status) }}</label>
                                    @endif
                                </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        @else
        <div class="form-group pt-3">
            {{ Form::label('status_mahasiswa','Status Mahasiswa') }}
            <br>
            <label class="badge badge-gradient-dark">Belum ada data status mahasiswa</label>
        </div>
        @endif
        <div class="form-row">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn btn-margin btn-tambah mt-2 mb-2']) }}
            <a href="{{ url('mahasiswa/profil/password') }}" class="btn btn-warning btn-sm btn-password-edit btn-margin btn-tambah mx-md-2 mt-2 mb-2">Ubah Password</a>
            <input type="reset" value="Reset" class="text-center btn btn-danger btn-sm btn-margin btn-tambah mt-2 mb-2"> 
    </div>
</div>