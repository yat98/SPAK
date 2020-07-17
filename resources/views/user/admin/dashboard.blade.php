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
                                    {{ $countAllJurusan > 0 ? $countAllJurusan.' Jurusan' : 'Data Jurusan Kosong' }}
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
                                    {{ $countAllProdi > 0 ? $countAllProdi.' Program Studi' : 'Data Program Studi Kosong' }}
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
                                    {{ $countAllMahasiswa > 0 ? $countAllMahasiswa.' Mahasiswa' : 'Data Mahasiswa Kosong' }}
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
                                    {{ $countAllUser > 0 ? $countAllUser.' User' : 'Data User Kosong' }}
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
                                    {{ $countAllStatusMahasiswa > 0 ? $countAllStatusMahasiswa.' Status Mahasiswa' : 'Data Status Mahasiswa Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('admin/status-mahasiswa') }}" class="text-white">Lihat data status mahasiswa</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-bloody-mary text-white card-img-holder">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Ormawa <i
                                        class="mdi mdi-check-decagram mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllOrmawa > 0 ? $countAllOrmawa.' Ormawa' : 'Data Ormawa Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('admin/ormawa') }}" class="text-white">Lihat data ormawa</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-deep-space-sea text-white card-img-holder">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Data Pimpinan Ormawa <i
                                        class="mdi mdi-account-multiple mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPimpinanOrmawa > 0 ? $countAllPimpinanOrmawa.' Pimpinan Ormawa' : 'Data Pimpinan Ormawa Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('admin/pimpinan-ormawa') }}" class="text-white">Lihat data pimpinan ormawa</a>
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
                                @if ($countAllJurusan > 0)
                                <div class="table-responsive dashboard">
                                    <table class="table display no-warp" id='datatables' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama Jurusan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
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
                                @if ($countAllProdi> 0)
                                <div class="table-responsive dashboard">
                                    <table class="table display no-warp prodi" id='datatables2' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama Program Studi</th>
                                                <th> Jurusan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
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
                                @if ($countAllTahunAkademik > 0)
                                 <div class="table-responsive dashboard">
                                    <table class="table display no-warp prodi" id='datatables3' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Tahun Akademik</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
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
                                @if ($countAllMahasiswa > 0)
                                <div class="table-responsive dashboard">
                                    <table class="table display no-warp prodi" id='datatables4' width="100%">
                                        <thead>
                                            <tr>
                                                 <th data-priority="1"> Nama</th>
                                                <th> Jurusan</th>
                                                <th> Angkatan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
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
                                @if ($countAllUser > 0)
                                <div class="table-responsive dashboard">
                                    <table class="table display no-warp prodi" id='datatables5' width="100%">
                                        <thead>
                                            <tr>
                                                 <th data-priority="1"> Nama</th>
                                                <th> Jabatan</th>
                                                <th> Status Aktif</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
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
                                @if ($countAllStatusMahasiswa > 0)
                                <div class="table-responsive dashboard">
                                    <table class="table display no-warp prodi" id='datatables6' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama</th>
                                                <th> Tahun Akademik</th>
                                                <th> Status Aktif</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
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
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Data Ormawa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllOrmawa > 0)
                                <div class="table-responsive dashboard">
                                    <table class="table display no-warp prodi" id='datatables7' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama</th>
                                                <th> Nama Jurusan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Ormawa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data ormawa terlebih dahulu.' }}
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
                                        <h4>Data Pimpinan Ormawa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPimpinanOrmawa > 0)
                                <div class="table-responsive dashboard">
                                    <table class="table display no-warp prodi" id='datatables8' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama</th>
                                                <th> Jurusan</th>
                                                <th> Jabatan</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Pimpinan Ormawa Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data pimpinan ormawa terlebih dahulu.' }}
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


<div class="modal fade" id="jurusan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='jurusan-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="prodi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='prodi-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="tahunAkademik" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='tahun-akademik-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="mahasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='mahasiswa-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="user" tabindex="-1" role="dialog" aria-labelledby="user"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='user-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="statusMahasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='status-mahasiswa-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="ormawa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <div class="modal-body" id='ormawa-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pimpinanOrmawa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='pimpinan-ormawa-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('datatables-javascript')
<script>
    let linkJurusan = "{{ url('admin/jurusan/') }}";

        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nama_jurusan",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${linkJurusan}/${row.id}" class="jurusan-detail text-dark" data-toggle="modal" data-target="#jurusan">
                                            <div class="mb-1">${row.nama_jurusan}</div>
                                        </a>`;
                            }
            }],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('admin/jurusan/limit') }}',
            columns: [{
                    data: 'nama_jurusan',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'updated_at',
                },
            ],
            "pageLength": {{ $perPageDashboard }}
        });

        let linkProdi = "{{ url('admin/program-studi/') }}";

        $('#datatables2').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nama_prodi",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${linkProdi}/${row.id}" class="prodi-detail text-dark" data-toggle="modal" data-target="#prodi">
                                            <div class="mb-1">${row.strata} - ${row.nama_prodi}</div>
                                        </a>`;
                            }
            }],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('admin/program-studi/limit') }}',
            columns: [{
                    data: 'nama_prodi',
                },
                {
                    data: 'jurusan.nama_jurusan',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'updated_at',
                },
            ],
            "pageLength": {{ $perPageDashboard }}
        });

        let linkTahunAkademik = "{{ url('admin/tahun-akademik/') }}";

        $('#datatables3').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "tahun_akademik",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${linkTahunAkademik}/${row.id}" class="tahun-akademik-detail text-dark" data-toggle="modal" data-target="#tahunAkademik">
                                            <div class="mb-1">${row.tahun_akademik} - ${row.semester}</div>
                                        </a>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "status_aktif",
                            "render": function ( data, type, row, meta ) {
                                if(data == 'Aktif'){
                                    return '<label class="badge badge-gradient-info">'+data+'</label>';
                                }else{
                                    return '<label class="badge badge-gradient-dark">'+data+'</label>';
                                }
                            }
            }],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('admin/tahun-akademik/limit') }}',
            columns: [{
                    data: 'tahun_akademik',
                },
                {
                    data: 'status_aktif',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'updated_at',
                },
            ],
            "pageLength": {{ $perPageDashboard }}
        });

        let linkMahasiswa = "{{ url('admin/mahasiswa/') }}";

        let datatables = $('#datatables4').DataTable({
            responsive: true,
            columnDefs: [{
                        "targets": 0,
                        "data": "nim",
                        "render": function ( data, type, row, meta ) {
                            return `<a href="${linkMahasiswa}/${row.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                        <div class="mb-1">${row.nama}</div>
                                        <span class="text-muted small">NIM. ${row.nim}</span>
                                    </a>`;
                        }
                    }
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('admin/mahasiswa/limit') }}',
            columns: [{
                    data: 'nama',
                },
                {
                    data: 'prodi.jurusan.nama_jurusan',
                },
                {
                    data: 'angkatan',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'updated_at',
                },
            ],
            "pageLength": {{ $perPageDashboard }}
    });  

    let linkUser = "{{ url('admin/user/') }}";

    $('#datatables5').DataTable({
        responsive: true,
        columnDefs: [{
                        "targets": 2,
                        "data": "status_aktif",
                        "render": function ( data, type, row, meta ) {
                            if(data == 'Aktif'){
                                return '<label class="badge badge-gradient-info">'+data+'</label>';
                            }else{
                                return '<label class="badge badge-gradient-dark">'+data+'</label>';
                            }
                        }
                    },
                    {
                        "targets": 0,
                        "data": "nama",
                        "render": function ( data, type, row, meta ) {
                            return `<a href="${linkUser}/${row.nip}" class="user-detail text-dark" data-toggle="modal" data-target="#user">
                                        <div class="mb-1">${row.nama}</div>
                                        <span class="text-muted small">NIP. ${row.nip}</span>
                                    </a>`;
                        }
                    }
        ],
        autoWidth: false,
        language: bahasa,
        processing: true,
        serverSide: true,
        ajax: '{{ url('admin/user/limit') }}',
        columns: [{
                data: 'nip',
            },
            {
                data: 'jabatan',
            },
            {
                data: 'status_aktif',
            },
            {
                data: 'created_at',
            },
            {
                data: 'updated_at',
            },
        ],
        "pageLength": {{ $perPageDashboard }}
    });

    let linkStatusMahasiswa = "{{ url('admin/status-mahasiswa/') }}";

    $('#datatables6').DataTable({
        responsive: true,
        columnDefs: [{
                        "targets": 0,
                        "data": "nim",
                        "render": function ( data, type, row, meta ) {
                            return `<a href="${linkStatusMahasiswa}/${row.tahun_akademik.id}/${row.nim}" class="status-mahasiswa-detail text-dark" data-toggle="modal" data-target="#statusMahasiswa">
                                        <div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.nim}</span>
                                    </a>`;
                        }
                    },
                    {
                        "targets": 1,
                        "data": "tahun_akademik",
                        "render": function ( data, type, row, meta ) {
                            return row.tahun_akademik.tahun_akademik+' - '+row.tahun_akademik.semester;
                        }
                    },
                    {
                        "targets": 2,
                        "data": "status",
                        "render": function( data, type, row, met ){
                            if (row.status == 'Aktif'){
                                return `<label class="badge badge-gradient-info">${row.status}</label>`;
                            }else if(row.status == 'lulus'){
                                return `<label class="badge badge-gradient-success">${row.status}</label>`;
                            }else if(row.status == 'drop out' || row.status == 'keluar'){
                                return `<label class="badge badge-gradient-danger">${row.status}</label>`;
                            }else if(row.status == 'cuti'){
                                return `<label class="badge badge-gradient-warning">${row.status}</label>`;
                            }else{
                                return `<label class="badge badge-gradient-dark">${row.status}</label>`;
                            }
                        }
                    },
        ],
        autoWidth: false,
        language: bahasa,
        processing: true,
        serverSide: true,
        ajax: '{{ url('admin/status-mahasiswa/limit') }}',
        columns: [{
                data: 'mahasiswa.nim',
            },
            {
                data: 'tahun_akademik.tahun_akademik',
            },
            {
                data: 'status',
            },
            {
                data: 'created_at',
            },
            {
                data: 'updated_at',
            },
        ],
        "pageLength": {{ $perPageDashboard }}
    });  

    let linkOrmawa = "{{ url('admin/ormawa/') }}";

    $('#datatables7').DataTable({
        responsive: true,
        columnDefs: [{
                        "targets": 0,
                        "data": "nama",
                        "render": function ( data, type, row, meta ) {
                            return `<a href="${linkOrmawa}/${row.id}" class="ormawa-detail text-dark" data-toggle="modal" data-target="#ormawa">
                                        <div class="mb-1">${row.nama}</div>
                                    </a>`;
                        }
        }],
        autoWidth: false,
        language: bahasa,
        processing: true,
        serverSide: true,
        ajax: '{{ url('admin/ormawa/limit') }}',
        columns: [{
                data: 'nama',
            },
            {
                data: 'jurusan.nama_jurusan',
            },
            {
                data: 'created_at',
            },
            {
                data: 'updated_at',
            },
        ],
        "pageLength": {{ $perPageDashboard }}
    }); 

    let linkPimpinanOrmawa = "{{ url('admin/pimpinan-ormawa/') }}";

    $('#datatables8').DataTable({
        responsive: true,
        columnDefs: [{
                        "targets": 0,
                        "data": "mahasiswa.nama",
                        "render": function ( data, type, row, meta ) {
                            return `<a href="${linkPimpinanOrmawa}/${row.nim}" class="pimpinan-ormawa-detail text-dark" data-toggle="modal" data-target="#pimpinanOrmawa">
                                            <div class="mb-1">${row.mahasiswa.nama}</div>
                                    <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                    </a>`;
                        }
                    },
                    {
                        "targets": 2,
                        "data": "jabatan",
                        "render": function ( data, type, row, meta ) {
                            return row.jabatan.ucwords();
                        }
                    },
                    {
                        "targets": 3,
                        "data": "status_aktif",
                        "render": function ( data, type, row, meta ) {
                            if(data == 'Aktif'){
                                return '<label class="badge badge-gradient-info">'+data+'</label>';
                            }else{
                                return '<label class="badge badge-gradient-dark">'+data+'</label>';
                            }
                        }
                    },
        ],
        autoWidth: false,
        language: bahasa,
        processing: true,
        serverSide: true,
        ajax: '{{ url('admin/pimpinan-ormawa/limit') }}',
        columns: [{
                data: 'mahasiswa.nama',
            },
            {
                data: 'mahasiswa.prodi.jurusan.nama_jurusan',
            },
            {
                data: 'jabatan',
            },
                {
                data: 'status_aktif',
            },
            {
                data: 'created_at',
            },
            {
                data: 'updated_at',
            },
        ],
        "pageLength": {{ $perPageDashboard }}
    });
</script>
@endsection