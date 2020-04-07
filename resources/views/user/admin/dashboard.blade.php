@extends('template')

@section('content')
<div class="container-scroller">
    @include('layout.navbar_top')
    <div class="container-fluid page-body-wrapper">
        @include('layout.navbar_side')
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="page-header">
                    <h3 class="page-title">
                        <span class="page-title-icon bg-gradient-primary text-white mr-2">
                            <i class="mdi mdi-home"></i>
                        </span> Dashboard </h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Jurusan
                                    <i class="mdi mdi-bank mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countJurusan > 0 ? $countJurusan.' Jurusan' : 'Data Jurusan Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('admin/jurusan') }}" class="text-white">Lihat data jurusan</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Program Studi <i
                                    class="mdi mdi-book-multiple mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countProdi > 0 ? $countProdi.' Program Studi' : 'Data Program Studi Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('admin/program-studi') }}" class="text-white">Lihat data program
                                        studi</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tahun Akademik<i
                                    class="mdi mdi-calendar-text mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ isset($tahunAkademikAktif) ? $tahunAkademikAktif->tahun_akademik.' - '.ucwords($tahunAkademikAktif->semester) : 'Tidak Ada Tahun Akademik Aktif' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('admin/tahun-akademik') }}" class="text-white">Lihat data tahun
                                        akademik</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Mahasiswa<i
                                        class="mdi mdi-account mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countMahasiswa > 0 ? $countMahasiswa.' Mahasiswa' : 'Data Mahasiswa Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('admin/mahasiswa') }}" class="text-white">Lihat data mahasiswa</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-dark card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">User<i
                                        class="mdi mdi-account mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countUser > 0 ? $countUser.' User' : 'Data User Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('admin/user') }}" class="text-white">Lihat data user</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Status Mahasiswa<i
                                        class="mdi mdi-checkbox-multiple-marked mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countStatusMahasiswa > 0 ? $countStatusMahasiswa.' Status Mahasiswa' : 'Data Status Mahasiswa Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('admin/status-mahasiswa') }}" class="text-white">Lihat data status mahasiswa</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Data Jurusan</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countJurusan > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Jurusan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($jurusanList as $jurusan)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $jurusan->nama_jurusan  }}</td>
                                                <td> {{ $jurusan->created_at->diffForHumans() }}</td>
                                                <td> {{ $jurusan->updated_at->diffForHumans() }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Jurusan kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data jurusan terlebih dahulu.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Data Program Studi</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countProdi> 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Jurusan</th>
                                                <th> Strata</th>
                                                <th> Nama Program Studi</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($prodiList as $prodi)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $prodi->jurusan->nama_jurusan}}</td>
                                                <td> {{ $prodi->strata  }}</td>
                                                <td> {{ $prodi->nama_prodi  }}</td>
                                                <td> {{ $prodi->created_at->diffForHumans() }}</td>
                                                <td> {{ $prodi->updated_at->diffForHumans() }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">Data Program Studi kosong!</h4>
                                        <p class="text-muted">Silahkan mengisi data program studi terlebih dahulu.
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Data Tahun Akademik</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countTahunAkademik > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Tahun Akademik</th>
                                                <th> Semester</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tahunAkademikList as $tahunAkademik)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $tahunAkademik->tahun_akademik  }}</td>
                                                <td> {{ ucwords($tahunAkademik->semester)  }}</td>
                                                <td>
                                                    @if ($tahunAkademik->status_aktif == 'aktif')
                                                    <label
                                                        class="badge badge-gradient-info">{{ ucwords($tahunAkademik->status_aktif) }}</label>
                                                    @else
                                                    <label
                                                        class="badge badge-gradient-dark">{{ ucwords($tahunAkademik->status_aktif) }}</label>
                                                    @endif
                                                </td>
                                                <td>{{ $tahunAkademik->created_at->diffForHumans() }}</td>
                                                <td>{{ $tahunAkademik->updated_at->diffForHumans() }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">Data Tahun Akademik kosong!</h4>
                                        <p class="text-muted">Silahkan mengisi data tahun Akademik terlebih dahulu.
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-4">
                                        <h4>Data Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countMahasiswa > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nim</th>
                                                <th> Nama</th>
                                                <th> Jurusan</th>
                                                <th> Program Studi</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($mahasiswaList as $mahasiswa)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $mahasiswa->nim  }}</td>
                                                <td> {{ ucwords($mahasiswa->nama)  }}</td>
                                                <td>{{ $mahasiswa->prodi->jurusan->nama_jurusan }}</td>
                                                <td>
                                                    {{ $mahasiswa->prodi->strata }} -
                                                    {{ $mahasiswa->prodi->nama_prodi }}
                                                </td>
                                                <td>{{ $mahasiswa->created_at->diffForHumans() }}</td>
                                                <td>{{ $mahasiswa->updated_at->diffForHumans() }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data Mahasiswa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Silahkan mengisi data mahasiswa terlebih dahulu.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Data User</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countUser > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> NIP</th>
                                                <th> Nama</th>
                                                <th> Jabatan</th>
                                                <th> Status Aktif</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($userList as $user)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{  $user->nip }}</td>
                                                <td> {{  $user->nama }}</td>
                                                <td> {{  ucwords($user->jabatan) }}</td>
                                                <td>
                                                @if ($user->status_aktif == 'aktif')
                                                <label
                                                    class="badge badge-gradient-info">{{ ucwords($user->status_aktif) }}</label>
                                                @else
                                                <label
                                                    class="badge badge-gradient-dark">{{ ucwords($user->status_aktif) }}</label>
                                                @endif
                                                </td>
                                                <td> {{ $user->created_at->diffForHumans() }}</td>
                                                <td> {{ $user->updated_at->diffForHumans() }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data user kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data user terlebih dahulu.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-4">
                                        <h4>Data Status Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countStatusMahasiswa > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> NIM</th>
                                                <th> Nama</th>
                                                <th> Tahun Akademik</th>
                                                <th> Status Aktif</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($statusMahasiswaList as $statusMahasiswa)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $statusMahasiswa->nim  }}</td>
                                                <td> {{ ucwords($statusMahasiswa->nama)  }}</td>
                                                <td> {{ $statusMahasiswa->tahun_akademik.' - '.ucwords($statusMahasiswa->semester)  }}</td>
                                                <td>
                                                    @if ($statusMahasiswa->status == 'aktif')
                                                        <label class="badge badge-gradient-info">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @elseif($statusMahasiswa->status == 'lulus')
                                                        <label class="badge badge-gradient-success">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @elseif($statusMahasiswa->status == 'drop out' || $statusMahasiswa->status == 'keluar')
                                                        <label class="badge badge-gradient-danger">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @elseif($statusMahasiswa->status == 'cuti')
                                                        <label class="badge badge-gradient-warning">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @else
                                                        <label class="badge badge-gradient-dark">{{ ucwords($statusMahasiswa->status) }}</label>
                                                    @endif
                                                </td>
                                                <td>{{ $statusMahasiswa->created_at->diffForHumans() }}</td>
                                                <td>{{ $statusMahasiswa->updated_at->diffForHumans() }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Status Mahasiswa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Silahkan mengisi data status mahasiswa terlebih dahulu.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.footer')
        </div>
    </div>
</div>
@endsection