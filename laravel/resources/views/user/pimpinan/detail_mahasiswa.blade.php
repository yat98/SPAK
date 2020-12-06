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
                            <i class="mdi mdi-account"></i>
                        </span> Mahasiswa</h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-4">
                                        <h4>Detail Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="table-responsive">
                                    <table class="table">
                                        <tr>
                                            <th> NIM</th>
                                            <td> {{ $mahasiswa->nim  }}</td>
                                        </tr>
                                        <tr>
                                            <th> Nama</th>
                                            <td> {{ ucwords($mahasiswa->nama)  }}</td>
                                        </tr>
                                        <tr>
                                            <th> Jenis Kelamin</th>
                                            <td>
                                                @if($mahasiswa->jenis_kelamin == 'L')
                                                    Laki-laki
                                                @else
                                                    Perempuan
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th> Tempat Tanggal Lahir</th>
                                            <td>{{ $mahasiswa->tempat_lahir }}, {{ $mahasiswa->tanggal_lahir->isoFormat('D MMMM Y') }}</td>
                                        </tr>
                                        <tr>
                                            <th> IPK</th>
                                            <td>{{ $mahasiswa->ipk }}</td>
                                        </tr>
                                        <tr>
                                            <th> Jurusan</th>
                                            <td>{{ $mahasiswa->prodi->jurusan->nama_jurusan }}</td>
                                        </tr>
                                        <tr>
                                            <th> Program Studi</th>
                                            <td>{{ $mahasiswa->prodi->strata }} - {{ $mahasiswa->prodi->nama_prodi }}</td>
                                        </tr>
                                        <tr>
                                            <th> Angkatan</th> 
                                            <td> {{ $mahasiswa->angkatan  }}</td>
                                        </tr>
                                        <tr>
                                            <th> Status Mahasiswa</th>
                                            <td>
                                                @if($mahasiswa->tahunAkademik->count())
                                                    <div class="table-responsive">
                                                        <table class="table">
                                                            <tr>
                                                                <th>Semester</th>
                                                                <th>Status</th>
                                                            </tr>
                                                            @foreach($mahasiswa->tahunAkademik as $value)
                                                                 <tr>
                                                                    <td>{{ $value->tahun_akademik }} - {{ ucwords($value->semester) }}</td>
                                                                    <td>
                                                                        @if($value->pivot->status == 'aktif')
                                                                            <label class="badge badge-gradient-info">
                                                                                {{ ucwords($value->pivot->status) }}
                                                                            </label>
                                                                        @elseif($value->pivot->status == 'lulus')
                                                                            <label class="badge badge-gradient-success">
                                                                                {{ ucwords($value->pivot->status) }}
                                                                            </label>
                                                                        @elseif($value->pivot->status == 'keluar' || $value->pivot->status == 'drop out')
                                                                            <label class="badge badge-gradient-danger">
                                                                                {{ ucwords($value->pivot->status) }}
                                                                            </label>
                                                                        @elseif($value->pivot->status == 'cuti')
                                                                            <label class="badge badge-gradient-warning text-dark">
                                                                                {{ ucwords($value->pivot->status) }}
                                                                            </label>
                                                                        @else
                                                                            <label class="badge badge-gradient-dark">
                                                                                {{ ucwords($value->pivot->status) }}
                                                                            </label>
                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </table>            
                                                    </div>
                                                @else
                                                    <label class="badge badge-gradient-dark text-white">Data mahasiswa belum ada</label>   
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="accordion bg-light" id="accordionExample">
                                <div class="card">
                                    <div class="card-header" id="headingOne" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                Surat Keterangan Aktif Kuliah
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratAktif > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables' width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th data-priority="1"> Nama </th>
                                                                <th> Tahun Akademik</th>
                                                                <th> Status</th>
                                                                <th> Waktu Pengajuan</th>
                                                                <th> Keterangan</th>
                                                                <th data-priority="2"> Aksi</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan aktif kuliah belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingTwo" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                                Surat Keterangan Kelakuan Baik
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratBaik > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables1' width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th data-priority="1"> Nama </th>
                                                                <th> Status</th>
                                                                <th> Waktu Pengajuan</th>
                                                                <th> Keterangan</th>
                                                                <th data-priority="2"> Aksi</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan kelakuan baik belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingThree" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseThree">
                                                Surat Dispensasi
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratDispensasi > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables2' width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th data-priority="1"> Nama Kegiatan</th>
                                                                <th> Status</th>
                                                                <th> Waktu Pengajuan</th>
                                                                <th> Keterangan</th>
                                                                <th data-priority="2"> Aksi</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat dispensasi belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingFour" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                                Surat Rekomendasi
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratRekomendasi > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables3' width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th data-priority="1"> Nama Kegiatan</th>
                                                                <th> Status</th>
                                                                <th> Waktu Pengajuan</th>
                                                                <th> Keterangan</th>
                                                                <th data-priority="2"> Aksi</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat rekomendasi belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingFive" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="true" aria-controls="collapseFive">
                                                Surat Tugas
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratTugas > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables4' width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th data-priority="1"> Nama Kegiatan</th>
                                                                <th> Status</th>
                                                                <th> Waktu Pengajuan</th>
                                                                <th> Keterangan</th>
                                                                <th data-priority="2"> Aksi</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat tugas belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingSix" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="true" aria-controls="collapseSix">
                                                Surat Persetujuan Pindah
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratPindah > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables5' width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th data-priority="1"> Nama </th>
                                                                <th> Status</th>
                                                                <th> Waktu Pengajuan</th>
                                                                <th> Keterangan</th>
                                                                <th data-priority="2"> Aksi</th>
                                                            </tr>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat persetujuan pindah belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingSeven" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="true" aria-controls="collapseSeven">
                                                Surat Pengantar Cuti
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratCuti > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables6' width="100%">
                                                        <thead>
                                                            <th data-priority="1"> Nomor Surat</th>
                                                            <th> Tahun Akademik</th>
                                                            <th> Status</th>
                                                            <th data-priority="2"> Aksi</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat pengajuan cuti belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingEight" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="true" aria-controls="collapseEight">
                                                Surat Pengantar Beasiswa
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratBeasiswa > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables7' width="100%">
                                                        <thead>
                                                            <th data-priority="1"> Nomor Surat</th>
                                                            <th> Hal</th>
                                                            <th> Status</th>
                                                            <th data-priority="2"> Aksi</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat pengajuan beasiswa belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @if($mahasiswa->pimpinanOrmawa != null)  
                                    <div class="card">
                                        <div class="card-header" id="headingNine" style="background-color:white !important;">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseNine" aria-expanded="true" aria-controls="collapseNine">
                                                    Surat Kegiatan Mahasiswa
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordionExample">
                                            <div class="card-body">
                                                @if ($countAllSuratKegiatan > 0)
                                                    <div class="table-responsive">
                                                        <table class="table display no-warp" id='datatables8' width="100%">
                                                            <thead>
                                                                <th data-priority="1"> Nama Kegiatan</th>
                                                                <th> Ormawa</th>
                                                                <th> Status</th>
                                                                <th> Waktu Pengajuan</th>
                                                                <th> Keterangan</th>
                                                                <th data-priority="2"> Aksi</th>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                @else
                                                    <div class="row">
                                                        <div class="col text-center">
                                                            <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                            <h4 class="display-4 mt-3">
                                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                            </h4>
                                                            <p class="text-muted">
                                                                {{ (Session::has('search')) ? Session::get('search') : ' Data surat kegiatan mahasiswa belum ada.' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="card">
                                    <div class="card-header" id="headingTen" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseTen" aria-expanded="true" aria-controls="collapseTen">
                                                Surat Keterangan Lulus
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratLulus > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables9' width="100%">
                                                        <thead>
                                                            <th data-priority="1"> Nama </th>
                                                            <th> Status</th>
                                                            <th> Waktu Pengajuan</th>
                                                            <th> Keterangan</th>
                                                            <th data-priority="2"> Aksi</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan lulus belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingEleven" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseEleven" aria-expanded="true" aria-controls="collapseEleven">
                                                Surat Permohonan Pengambilan Material
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratMaterial > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables10' width="100%">
                                                        <thead>
                                                            <th data-priority="1"> Nama Kegiatan</th>
                                                            <th> Status</th>
                                                            <th> Waktu Pengajuan</th>
                                                            <th> Keterangan</th>
                                                            <th data-priority="2"> Aksi</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat permohonan pengambilan material belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingTwelve" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseTwelve" aria-expanded="true" aria-controls="collapseTwelve">
                                                Surat Permohonan Survei
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratSurvei > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables11' width="100%">
                                                        <thead>
                                                            <th data-priority="1"> Nama </th>
                                                            <th> Status</th>
                                                            <th> Waktu Pengajuan</th>
                                                            <th> Keterangan</th>
                                                            <th data-priority="2"> Aksi</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat permohonan survei belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingThirteen" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseThirteen" aria-expanded="true" aria-controls="collapseThirteen">
                                                Surat Rekomendasi Penelitian
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratPenelitian > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables12' width="100%">
                                                        <thead>
                                                            <th data-priority="1"> Nama </th>
                                                            <th> Status</th>
                                                            <th> Waktu Pengajuan</th>
                                                            <th> Keterangan</th>
                                                            <th data-priority="2"> Aksi</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat rekomendasi penelitian belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingFourteen" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseFourteen" aria-expanded="true" aria-controls="collapseFourteen">
                                                Surat Permohonan Pengambilan Data Awal
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratDataAwal > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables13' width="100%">
                                                        <thead>
                                                            <th data-priority="1"> Nama </th>
                                                            <th> Status</th>
                                                            <th> Waktu Pengajuan</th>
                                                            <th> Keterangan</th>
                                                            <th data-priority="2"> Aksi</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat permohonan pengambilan data awal belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingFifteen" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapseFifteen" aria-expanded="true" aria-controls="collapseFifteen">
                                                Surat Keterangan Bebas Perlengkapan
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapseFifteen" class="collapse" aria-labelledby="headingFifteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratPerlengkapan > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables14' width="100%">
                                                        <thead>
                                                            <th data-priority="1"> Nama </th>
                                                            <th> Status</th>
                                                            <th> Waktu Pengajuan</th>
                                                            <th> Keterangan</th>
                                                            <th data-priority="2"> Aksi</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan bebas perlengkapan belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card">
                                    <div class="card-header" id="headingSixteen" style="background-color:white !important;">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link text-dark text-decoration-none" type="button" data-toggle="collapse" data-target="#collapsesixTeen" aria-expanded="true" aria-controls="collapsesixTeen">
                                                Surat Keterangan Bebas Perpustakaan
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="collapsesixTeen" class="collapse" aria-labelledby="headingSixteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            @if ($countAllSuratPerpustakaan > 0)
                                                <div class="table-responsive">
                                                    <table class="table display no-warp" id='datatables15' width="100%">
                                                        <thead>
                                                            <th data-priority="1"> Nama </th>
                                                            <th> Status</th>
                                                            <th> Waktu Pengajuan</th>
                                                            <th> Keterangan</th>
                                                            <th data-priority="2"> Aksi</th>
                                                        </thead>
                                                    </table>
                                                </div>
                                            @else
                                                <div class="row">
                                                    <div class="col text-center">
                                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                        <h4 class="display-4 mt-3">
                                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                                        </h4>
                                                        <p class="text-muted">
                                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan bebas perpustakaan belum ada.' }}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('layout.footer')
        </div>
    </div>
</div>

<div class="modal fade" id="suratKeterangan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-keterangan-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratKeteranganDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-keterangan-aktif-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratDispensasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-dispensasi-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratRekomendasi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-rekomendasi-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratTugas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-tugas-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratPindah" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-pindah-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratKeteranganLulus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-keterangan-lulus-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratMaterial" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-material-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratSurvei" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-survei-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratPenelitian" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-penelitian-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratDataAwal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-data-awal-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="pendaftaranCuti" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='pendaftaran-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratCuti" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-pengantar-cuti-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratBeasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-pengantar-beasiswa-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratPerlengkapan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-perlengkapan-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="suratPerpustakaan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-perpustakaan-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('datatables-javascript')
    <script>
        let linkAktif = "{{ url('pimpinan/surat-keterangan-aktif-kuliah/pengajuan') }}";
        let linkSuratAktif = "{{ url('pimpinan/surat-keterangan-aktif-kuliah') }}";

        let linkBaik = "{{ url('pimpinan/surat-keterangan-kelakuan-baik/pengajuan') }}";
        let linkSuratBaik = "{{ url('pimpinan/surat-keterangan-kelakuan-baik') }}";

        let linkDispensasi = "{{ url('pimpinan/surat-dispensasi/pengajuan') }}";
        let linkSuratDispensasi = "{{ url('pimpinan/surat-dispensasi') }}";

        let linkRekomendasi = "{{ url('pimpinan/surat-rekomendasi/pengajuan') }}";
        let linkSuratRekomendasi = "{{ url('pimpinan/surat-rekomendasi') }}";

        let linkTugas = "{{ url('pimpinan/surat-tugas/pengajuan') }}";
        let linkSuratTugas = "{{ url('pimpinan/surat-tugas') }}";

        let linkPindah = "{{ url('pimpinan/surat-persetujuan-pindah/pengajuan') }}";
        let linkSuratPindah = "{{ url('pimpinan/surat-persetujuan-pindah') }}";

        let linkKegiatan = "{{ url('pimpinan/surat-kegiatan-mahasiswa/pengajuan') }}";
        let linkSuratKegiatan = "{{ url('pimpinan/surat-kegiatan-mahasiswa') }}";

        let linkCuti = "{{ url('pimpinan/surat-pengantar-cuti') }}";
        let linkBeasiswa = "{{ url('pimpinan/surat-pengantar-beasiswa') }}";

        let linkLulus = "{{ url('pimpinan/surat-keterangan-lulus/pengajuan') }}";
        let linkSuratLulus = "{{ url('pimpinan/surat-keterangan-lulus') }}";
        
        let linkMaterial = "{{ url('pimpinan/surat-permohonan-pengambilan-material/pengajuan') }}";
        let linkSuratMaterial = "{{ url('pimpinan/surat-permohonan-pengambilan-material') }}";

        let linkSurvei = "{{ url('pimpinan/surat-permohonan-survei/pengajuan') }}";
        let linkSuratSurvei = "{{ url('pimpinan/surat-permohonan-survei') }}";

        let linkPenelitian = "{{ url('pimpinan/surat-rekomendasi-penelitian/pengajuan') }}";
        let linkSuratPenelitian = "{{ url('pimpinan/surat-rekomendasi-penelitian') }}";

        let linkDataAwal = "{{ url('pimpinan/surat-permohonan-pengambilan-data-awal/pengajuan') }}";
        let linkSuratDataAwal = "{{ url('pimpinan/surat-permohonan-pengambilan-data-awal') }}";

        let linkPerlengkapan = "{{ url('pimpinan/surat-keterangan-bebas-perlengkapan/pengajuan') }}";
        let linkSuratPerlengkapan = "{{ url('pimpinan/surat-keterangan-bebas-perlengkapan') }}";

        let linkPerpustakaan = "{{ url('pimpinan/surat-keterangan-bebas-perpustakaan/pengajuan') }}";
        let linkSuratPerpustakaan = "{{ url('pimpinan/surat-keterangan-bebas-perpustakaan') }}";

        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
                            }
                        },
                        {
                            "targets": 2,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 3,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 5,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkAktif+'/'+row.id}" class="dropdown-item pengajuan-surat-keterangan-detail" data-toggle="modal" data-target="#suratKeterangan">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratAktif+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#suratKeteranganDetail">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratAktif+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [6,7,8],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-keterangan-aktif-kuliah/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'tahun',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
                {
                    data: 'tahun_akademik.tahun_akademik',
                },
                {
                    data: 'tahun_akademik.semester',
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 3, 'desc' ]],
        });

        $('#datatables1').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkBaik+'/'+row.id}" class="dropdown-item pengajuan-surat-keterangan-detail" data-toggle="modal" data-target="#suratKeterangan">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratBaik+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#suratKeteranganDetail">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratBaik+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-keterangan-kelakuan-baik/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables2').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;
                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkDispensasi+'/'+row.id_surat_masuk}" class="dropdown-item btn-pengajuan-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratDispensasi+'/'+row.id_surat_masuk}" class="dropdown-item btn-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratDispensasi+'/'+row.id_surat_masuk+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-dispensasi/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'nama_kegiatan',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables3').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;
                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkRekomendasi+'/'+row.id}" class="dropdown-item btn-pengajuan-surat-rekomendasi-detail" data-toggle="modal" data-target="#suratRekomendasi">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratRekomendasi+'/'+row.id}" class="dropdown-item btn-surat-rekomendasi-detail" data-toggle="modal" data-target="#suratRekomendasi">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratRekomendasi+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-rekomendasi/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'nama_kegiatan',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables4').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;
                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkTugas+'/'+row.id}" class="dropdown-item btn-pengajuan-surat-tugas-detail" data-toggle="modal" data-target="#suratTugas">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratTugas+'/'+row.id}" class="dropdown-item btn-surat-tugas-detail" data-toggle="modal" data-target="#suratTugas">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratTugas+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-tugas/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'nama_kegiatan',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables5').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkPindah+'/'+row.id}" class="dropdown-item pengajuan-surat-pindah-detail" data-toggle="modal" data-target="#suratPindah">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratPindah+'/'+row.id}" class="dropdown-item btn-surat-pindah-detail" data-toggle="modal" data-target="#suratPindah">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratPindah+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-persetujuan-pindah/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables6').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nomor_surat",
                            "render": function ( data, type, row, meta ) {
                                return `${row.nomor_surat}/${row.kode_surat.kode_surat}`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "tahun_akademik.tahun_akademik",
                            "render": function ( data, type, row, meta ) {
                                return `${row.tahun_akademik.tahun_akademik} - ${row.semester}`;
                            }
                        },
                        {
                            "targets": 2,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 3,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                <a href="${linkCuti+'/'+row.id}" class="dropdown-item btn-surat-pengantar-cuti-detail" data-toggle="modal" data-target="#suratCuti">Detail</a>
                                                <a href="${linkCuti+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                            </div>
                                        </div>`;
                            }
                        },
                        {
                            "targets": [4],
                            "visible": false,
                        },
                    ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-pengantar-cuti/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'nomor_surat',
                },
                {
                    data: 'tahun_akademik.tahun_akademik',
                },
                {
                    data: 'status',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'tahun_akademik.semester',
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 0, 'desc' ]],
        });

        $('#datatables7').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nomor_surat",
                            "render": function ( data, type, row, meta ) {
                                return `${row.nomor_surat}/${row.kode_surat.kode_surat}`;
                            }
                        },
                        {
                            "targets": 2,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 3,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                <a href="${linkBeasiswa+'/'+row.id}" class="dropdown-item btn-surat-pengantar-beasiswa-detail" data-toggle="modal" data-target="#suratBeasiswa">Detail</a>
                                                <a href="${linkBeasiswa+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                            </div>
                                        </div>`;
                            }
                        },
                    ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-pengantar-beasiswa/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'nomor_surat',
                },
                {
                    data: 'hal',
                },
                {
                    data: 'status',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 0, 'desc' ]],
        });

        $('#datatables8').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 2,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 3,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 5,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak' || row.status == 'Disposisi WD1' || row.status == 'Disposisi WD2' || row.status == 'Disposisi WD3' || row.status == 'Disposisi Selesai'){
                                    action += `<a href="${linkKegiatan+'/'+row.id}" class="dropdown-item">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratKegiatan+'/'+row.id}" class="dropdown-item">Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratKegiatan+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    }],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-kegiatan-mahasiswa/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'nama_kegiatan',
                },
                {
                    data: 'ormawa.nama',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 3, 'desc' ]],
        });

        $('#datatables9').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkLulus+'/'+row.id}" class="dropdown-item pengajuan-surat-keterangan-lulus-detail" data-toggle="modal" data-target="#suratKeteranganLulus">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratLulus+'/'+row.id}" class="dropdown-item btn-surat-lulus-detail" data-toggle="modal" data-target="#suratKeteranganLulus">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratLulus+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('pimpinan/surat-keterangan-lulus/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
                columns: [{
                        data: 'mahasiswa.nim',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'keterangan',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                    {
                        data: 'mahasiswa.nama',
                    }, 
                ],
                "pageLength": {{ $perPage }},
                "order": [[ 2, 'desc' ]],
        });

        $('#datatables10').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkMaterial+'/'+row.id}" class="dropdown-item pengajuan-surat-material-detail" data-toggle="modal" data-target="#suratMaterial">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratMaterial+'/'+row.id}" class="dropdown-item btn-surat-material-detail" data-toggle="modal" data-target="#suratMaterial">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratMaterial+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-permohonan-pengambilan-material/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'nama_kegiatan',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 3, 'desc' ]],
        });

        $('#datatables11').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkSurvei+'/'+row.id}" class="dropdown-item pengajuan-surat-survei-detail" data-toggle="modal" data-target="#suratSurvei">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratSurvei+'/'+row.id}" class="dropdown-item btn-surat-survei-detail" data-toggle="modal" data-target="#suratSurvei">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratSurvei+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-permohonan-survei/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables12').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkPenelitian+'/'+row.id}" class="dropdown-item pengajuan-surat-penelitian-detail" data-toggle="modal" data-target="#suratPenelitian">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratPenelitian+'/'+row.id}" class="dropdown-item btn-surat-penelitian-detail" data-toggle="modal" data-target="#suratPenelitian">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratPenelitian+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-rekomendasi-penelitian/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables13').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkDataAwal+'/'+row.id}" class="dropdown-item pengajuan-surat-data-awal-detail" data-toggle="modal" data-target="#suratDataAwal">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratDataAwal+'/'+row.id}" class="dropdown-item btn-surat-data-awal-detail" data-toggle="modal" data-target="#suratDataAwal">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratDataAwal+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-permohonan-pengambilan-data-awal/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables14').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkPerlengkapan+'/'+row.id}" class="dropdown-item pengajuan-surat-perlengkapan-detail" data-toggle="modal" data-target="#suratPerlengkapan">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratPerlengkapan+'/'+row.id}" class="dropdown-item btn-surat-perlengkapan-detail" data-toggle="modal" data-target="#suratPerlengkapan">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratPerlengkapan+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-keterangan-bebas-perlengkapan/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables15').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "nim",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="mb-1">${row.mahasiswa.nama}</div>
                                        <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>`;
                            }
                        },
                        {
                            "targets": 1,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Ditolak'){
                                    return ` <label class="badge badge-gradient-danger">
                                                ${row.status}
                                            </label>`;
                                }else if(row.status == 'Selesai'){
                                    return ` <label class="badge badge-gradient-info">
                                            ${row.status}
                                        </label>`;
                                }else{
                                    return ` <label class="badge badge-gradient-warning text-dark">
                                                ${row.status}
                                            </label>`;
                                }
                            }
                        },
                        {
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let action = ``;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkPerpustakaan+'/'+row.id}" class="dropdown-item pengajuan-surat-perpustakaan-detail" data-toggle="modal" data-target="#suratPerpustakaan">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratPerpustakaan+'/'+row.id}" class="dropdown-item btn-surat-perpustakaan-detail" data-toggle="modal" data-target="#suratPerpustakaan">
                                                Detail</a>`;
                                }

                                if(row.status == 'Selesai'){
                                    action += `<a href="${linkSuratPerpustakaan+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${action}
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [5],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-keterangan-bebas-perpustakaan/pengajuan/mahasiswa/'.$mahasiswa->nim) }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'status',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'mahasiswa.nama',
                }, 
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 2, 'desc' ]],
        });
    </script>
@endsection