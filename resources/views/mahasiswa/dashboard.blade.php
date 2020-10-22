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
                @if(isset($waktuCuti))  
                    @if($tgl->greaterThanOrEqualTo($waktuCuti->tanggal_awal_cuti) && $tgl->lessThanOrEqualTo($waktuCuti->tanggal_akhir_cuti)) 
                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card mb-2">
                                    <div class="card-body text-center">
                                        <h1 class="h1">Waktu Pendaftaran Cuti</h1>
                                        <div class="wrapper my-4">
                                            <ul class="flip-clock-container flip-2">
                                                <li class="flip-item-seconds">{{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->s }}</li>
                                                <li class="flip-item-minutes">{{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->i }}</li>
                                                <li class="flip-item-hours">{{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->h }}</li>
                                                <li class="flip-item-days">{{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->d }}</li>
                                            </ul>
                                        </div>
                                        <p class="text-secondary h4">
                                            Waktu pendaftaran cuti tersisa {{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->d }} Hari {{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->h }} Jam {{ $tgl->diff($waktuCuti->tanggal_akhir_cuti)->i }} Menit
                                        </p>
                                    </div>    
                                </div>
                            </div>
                        </div>
                    @elseif($tgl->lessThanOrEqualTo($waktuCuti->tanggal_awal_cuti)) 
                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card mb-2 bg-dark text-white">
                                    <div class="card-body text-center">
                                        <h1>Pendaftaran Cuti Pada Semester Ini Belum Di Buka</h1>
                                    </div>     
                                </div>
                            </div>
                        </div>
                    @elseif($tgl->greaterThanOrEqualTo($waktuCuti->tanggal_akhir_cuti)) 
                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card mb-2 bg-dark text-white">
                                    <div class="card-body text-center">
                                        <h1>Pendaftaran Cuti Pada Semester Ini Telah Selesai</h1>
                                    </div>     
                                </div>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card mb-2 bg-dark text-white">
                                <div class="card-body text-center">
                                    <h1>Pendaftaran Cuti Pada Semester Ini Belum Di Buka</h1>
                                </div>     
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-danger card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tahun Akademik<i
                                    class="mdi mdi-calendar-text mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ isset($tahunAkademikAktif) ? $tahunAkademikAktif->tahun_akademik.' - '.ucwords($tahunAkademikAktif->semester) : 'Belum Aktif' }}
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Keterangan Aktif Kuliah<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratAktif > 0 ? $countAllSuratAktif.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-keterangan-aktif-kuliah') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Keterangan Kelakuan Baik<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratBaik > 0 ? $countAllSuratBaik.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-keterangan-kelakuan-baik') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Dispensasi<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratDispensasi > 0 ? $countAllSuratDispensasi.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-dispensasi') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Rekomendasi<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratRekomendasi > 0 ? $countAllSuratRekomendasi.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-rekomendasi') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Tugas<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratTugas > 0 ? $countAllSuratTugas.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-tugas') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Persetujuan Pindah<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratPindah > 0 ? $countAllSuratPindah.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-persetujuan-pindah') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @if(isset($pimpinanOrmawa))
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Kegiatan Mahasiswa<i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratKegiatan > 0 ? $countAllSuratKegiatan.' Surat' : 'Data Surat Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('mahasiswa/surat-kegiatan-mahasiswa') }}" class="text-white">Lihat data surat</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Keterangan Lulus<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratLulus > 0 ? $countAllSuratLulus.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-keterangan-lulus') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Permohonan<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                        <br>
                                        Pengambilan Material
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratMaterial > 0 ? $countAllSuratMaterial.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-permohonan-pengambilan-material') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Permohonan Survei<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratSurvei > 0 ? $countAllSuratSurvei.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-permohonan-survei') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Rekomendasi Penelitian<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratPenelitian > 0 ? $countAllSuratPenelitian.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-rekomendasi-penelitian') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Permohonan<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                        <br>
                                        Pengambilan Data Awal
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratDataAwal > 0 ? $countAllSuratDataAwal.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/surat-permohonan-pengambilan-data-awal') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-dark card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pendaftaran Cuti<i
                                        class="mdi mdi-playlist-check mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPendaftaran > 0 ? $countAllPendaftaran.' Pendaftaran Cuti' : 'Pendaftaran Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('mahasiswa/pendaftaran-cuti') }}" class="text-white">Lihat data pendaftaran</a>
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
                                        <h4>Surat Keterangan Aktif Kuliah</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratAktif > 0)
                                <div class="table-responsive dashboard">
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
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Surat Keterangan Kelakuan Baik</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratBaik > 0)
                                <div class="table-responsive dashboard">
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
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Surat Dispensasi</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratDispensasi > 0)
                                <div class="table-responsive dashboard">
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Surat Dispensasi Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data surat dispensasi belum ada.' }}
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
                                        <h4>Surat Rekomendasi</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratRekomendasi > 0)
                                <div class="table-responsive dashboard">
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Surat Rekomendasi Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data surat rekomendasi belum ada.' }}
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
                                        <h4>Surat Tugas</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratTugas > 0)
                                <div class="table-responsive dashboard">
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Surat Tugas Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data surat tugas belum ada.' }}
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
                                        <h4>Surat Persetujuan Pindah</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratPindah > 0)
                                 <div class="table-responsive dashboard">
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat persetujuan pindah kuliah belum ada.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(isset($pimpinanOrmawa))
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-12 col-md-6">
                                            <h4>Surat Kegiatan Mahasiswa</h4>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllSuratKegiatan > 0)
                                    <div class="table-responsive dashboard">
                                        <table class="table display no-warp" id='datatables6' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama Kegiatan</th>
                                                    <th> Ormawa</th>
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
                                                {{ (Session::has('search')) ? Session::get('search') : 'Data surat kegiatan mahasiswa belum ada.' }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Surat Keterangan Lulus</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratLulus > 0)
                                 <div class="table-responsive dashboard">
                                    <table class="table display no-warp" id='datatables7' width="100%">
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan lulus belum ada.' }}
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
                                        <h4>Surat Permohonan Pengambilan Material</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratMaterial > 0)
                                 <div class="table-responsive dashboard">
                                    <table class="table display no-warp" id='datatables8' width="100%">
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat permohonan pengambilan material belum ada.' }}
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
                                        <h4>Surat Permohonan Survei</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratSurvei > 0)
                                <div class="table-responsive dashboard">
                                    <table class="table display no-warp" id='datatables9' width="100%">
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat permohonan survei belum ada.' }}
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
                                        <h4>Surat Rekomendasi Penelitian</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratPenelitian > 0)
                                 <div class="table-responsive dashboard">
                                    <table class="table display no-warp" id='datatables10' width="100%">
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat rekomendasi penelitian belum ada.' }}
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
                                        <h4>Surat Permohonan Pengambilan Data Awal</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratDataAwal > 0)
                                <div class="table-responsive dasboard">
                                    <table class="table display no-warp" id='datatables11' width="100%">
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat permohonan pengambilan data awal belum ada.' }}
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
                                        <h4>Pendaftaran Cuti</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPendaftaran > 0)
                                 <div class="table-responsive dashboard">
                                    <table class="table display no-warp" id='datatables12' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama </th>
                                                <th> Tahun Akademik</th>
                                                <th> Alasan Cuti</th>
                                                <th data-priority="2"> Status</th>
                                                <th> Waktu Pendaftaran</th>
                                                <th> Keterangan</th>
                                                <th data-priority="3"> Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Pendaftaran Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data pendaftaran cuti belum ada.' }}
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Progress Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-progress-content'></div>
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
@endsection

@section('datatables-javascript')
    <script>
        let linkAktif = "{{ url('mahasiswa/surat-keterangan-aktif-kuliah/pengajuan') }}";
        let linkSuratAktif = "{{ url('mahasiswa/surat-keterangan-aktif-kuliah') }}";

        let linkBaik = "{{ url('mahasiswa/surat-keterangan-kelakuan-baik/pengajuan') }}";
        let linkSuratBaik = "{{ url('mahasiswa/surat-keterangan-kelakuan-baik') }}";

        let linkDispensasi = "{{ url('mahasiswa/surat-dispensasi/pengajuan') }}";
        let linkSuratDispensasi = "{{ url('mahasiswa/surat-dispensasi') }}";

        let linkRekomendasi = "{{ url('mahasiswa/surat-rekomendasi/pengajuan') }}";
        let linkSuratRekomendasi = "{{ url('mahasiswa/surat-rekomendasi') }}";

        let linkTugas = "{{ url('mahasiswa/surat-tugas/pengajuan') }}";
        let linkSuratTugas = "{{ url('mahasiswa/surat-tugas') }}";

        let linkPindah = "{{ url('mahasiswa/surat-persetujuan-pindah/pengajuan') }}";
        let linkSuratPindah = "{{ url('mahasiswa/surat-persetujuan-pindah') }}";

        let linkKegiatan = "{{ url('mahasiswa/surat-kegiatan-mahasiswa/pengajuan') }}";
        let linkSuratKegiatan = "{{ url('mahasiswa/surat-kegiatan-mahasiswa') }}";

        let linkLulus = "{{ url('mahasiswa/surat-keterangan-lulus/pengajuan') }}";
        let linkSuratLulus = "{{ url('mahasiswa/surat-keterangan-lulus') }}";
        
        let linkMaterial = "{{ url('mahasiswa/surat-permohonan-pengambilan-material/pengajuan') }}";
        let linkSuratMaterial = "{{ url('mahasiswa/surat-permohonan-pengambilan-material') }}";

        let linkSurvei = "{{ url('mahasiswa/surat-permohonan-survei/pengajuan') }}";
        let linkSuratSurvei = "{{ url('mahasiswa/surat-permohonan-survei') }}";

        let linkPenelitian = "{{ url('mahasiswa/surat-rekomendasi-penelitian/pengajuan') }}";
        let linkSuratPenelitian = "{{ url('mahasiswa/surat-rekomendasi-penelitian') }}";

        let linkDataAwal = "{{ url('mahasiswa/surat-permohonan-pengambilan-data-awal/pengajuan') }}";
        let linkSuratDataAwal = "{{ url('mahasiswa/surat-permohonan-pengambilan-data-awal') }}";

        let linkPendaftaran = "{{ url('mahasiswa/pendaftaran-cuti') }}";

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
                                let action = `<a href="${linkAktif+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

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
            ajax: '{{ url('mahasiswa/surat-keterangan-aktif-kuliah/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
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
                                let action = `<a href="${linkBaik+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

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
            ajax: '{{ url('mahasiswa/surat-keterangan-kelakuan-baik/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
            "order": [[ 3, 'desc' ]],
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
                                let action = ` <a href="${linkDispensasi+'/'+row.id_surat_masuk}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkDispensasi+'/'+row.id_surat_masuk}" class="dropdown-item btn-pengajuan-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratDispensasi+'/'+row.id_surat_masuk}" class="dropdown-item btn-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>`;
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
            ajax: '{{ url('mahasiswa/surat-dispensasi/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
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
                                let action = ` <a href="${linkRekomendasi+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkRekomendasi+'/'+row.id}" class="dropdown-item btn-pengajuan-surat-rekomendasi-detail" data-toggle="modal" data-target="#suratRekomendasi">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratRekomendasi+'/'+row.id}" class="dropdown-item btn-surat-rekomendasi-detail" data-toggle="modal" data-target="#suratRekomendasi">Detail</a>`;
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
            ajax: '{{ url('mahasiswa/surat-rekomendasi/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
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
                                let action = ` <a href="${linkTugas+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

                                if(row.status == 'Diajukan' || row.status == 'Ditolak'){
                                    action += `<a href="${linkTugas+'/'+row.id}" class="dropdown-item btn-pengajuan-surat-tugas-detail" data-toggle="modal" data-target="#suratTugas">Detail</a>`;
                                }else{
                                    action += `<a href="${linkSuratTugas+'/'+row.id}" class="dropdown-item btn-surat-tugas-detail" data-toggle="modal" data-target="#suratTugas">Detail</a>`;
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
            ajax: '{{ url('mahasiswa/surat-tugas/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
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
                                let action = `<a href="${linkPindah+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

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
            ajax: '{{ url('mahasiswa/surat-persetujuan-pindah/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables6').DataTable({
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
                                let action = `<a href="${linkKegiatan+'/'+row.id}/progress" class="dropdown-item btn-surat-kegiatan-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

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
            ajax: '{{ url('mahasiswa/surat-kegiatan-mahasiswa/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
            "order": [[ 3, 'desc' ]],
        });

        $('#datatables7').DataTable({
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
                                let action = `<a href="${linkLulus+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

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
                ajax: '{{ url('mahasiswa/surat-keterangan-lulus/pengajuan/all') }}',
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
                "pageLength": {{ $perPageDashboard }},
                "order": [[ 2, 'desc' ]],
        });

        $('#datatables8').DataTable({
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
                                let action = `<a href="${linkMaterial+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

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
            ajax: '{{ url('mahasiswa/surat-permohonan-pengambilan-material/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
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
                                let action = `<a href="${linkSurvei+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

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
            ajax: '{{ url('mahasiswa/surat-permohonan-survei/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
            "order": [[ 2, 'desc' ]],
        });

        $('#datatables10').DataTable({
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
                                let action = `<a href="${linkPenelitian+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

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
            ajax: '{{ url('mahasiswa/surat-rekomendasi-penelitian/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
            "order": [[ 2, 'desc' ]],
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
                                let action = `<a href="${linkDataAwal+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>`;

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
            ajax: '{{ url('mahasiswa/surat-permohonan-pengambilan-data-awal/pengajuan/all') }}',
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
            "pageLength": {{ $perPageDashboard }},
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
                            "targets": 3,
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
                            "targets": 4,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 6,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                <a href="${linkPendaftaran+'/'+row.id}" class="dropdown-item btn-pendaftaran-cuti-detail" data-toggle="modal" data-target="#pendaftaranCuti">Detail</a>
                                            </div>
                                        </div>`;
                        }
                    },
                    {
                        "targets": [7,8,9],
                        "visible": false,
                    },],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('mahasiswa/pendaftaran-cuti/all') }}',
            columns: [{
                    data: 'mahasiswa.nim',
                },
                {
                    data: 'tahun',
                },
                {
                    data: 'alasan_cuti',
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
            "pageLength": {{ $perPageDashboard }},
            "order": [[ 3, 'desc' ]],
        });
    </script>
@endsection