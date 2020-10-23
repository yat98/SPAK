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
                @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian kemahasiswaan')
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
                    @if(Auth::user()->bagian == 'subbagian kemahasiswaan')
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-quepal card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Kode Surat <i
                                            class="mdi mdi-format-list-numbered mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ isset($kodeSuratAktif) ? $kodeSuratAktif->kode_surat : 'Belum Aktif' }}
                                    </h2>
                                    <h6 class="card-text"></h6>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Masuk<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratMasuk > 0 ? $countAllSuratMasuk.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('operator/surat-masuk') }}" class="text-white">Lihat data surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian kemahasiswaan')
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
                                        <a href="{{ url('operator/surat-keterangan-aktif-kuliah') }}" class="text-white">Lihat data surat</a>
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
                                        <a href="{{ url('operator/surat-keterangan-kelakuan-baik') }}" class="text-white">Lihat data surat</a>
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
                                        <a href="{{ url('operator/surat-dispensasi') }}" class="text-white">Lihat data surat</a>
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
                                        <a href="{{ url('operator/surat-rekomendasi') }}" class="text-white">Lihat data surat</a>
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
                                        <a href="{{ url('operator/surat-tugas') }}" class="text-white">Lihat data surat</a>
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
                                        <a href="{{ url('operator/surat-persetujuan-pindah') }}" class="text-white">Lihat data surat</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->bagian == 'subbagian kemahasiswaan')
                            <div class="col-md-4 stretch-card grid-margin">
                                <div class="card bg-gradient-info card-img-holder text-white">
                                    <div class="card-body">
                                        <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                            alt="circle-image" />
                                        <h4 class="font-weight-normal mb-3">Surat Pengantar Cuti<i
                                                class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                        </h4>
                                        <h2 class="mb-5">
                                            {{ $countAllSuratCuti > 0 ? $countAllSuratCuti.' Surat' : 'Data Surat Kosong' }}
                                        </h2>
                                        <h6 class="card-text">
                                            <a href="{{ url('operator/surat-pengantar-cuti') }}" class="text-white">Lihat data surat</a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 stretch-card grid-margin">
                                <div class="card bg-gradient-info card-img-holder text-white">
                                    <div class="card-body">
                                        <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                            alt="circle-image" />
                                        <h4 class="font-weight-normal mb-3">Surat Pengantar Beasiswa<i
                                                class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                        </h4>
                                        <h2 class="mb-5">
                                            {{ $countAllSuratBeasiswa > 0 ? $countAllSuratBeasiswa.' Surat' : 'Data Surat Kosong' }}
                                        </h2>
                                        <h6 class="card-text">
                                            <a href="{{ url('operator/surat-pengantar-beasiswa') }}" class="text-white">Lihat data surat</a>
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
                                    <h4 class="font-weight-normal mb-3">Surat Kegiatan Mahasiswa<i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratKegiatan > 0 ? $countAllSuratKegiatan.' Surat' : 'Data Surat Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('operator/surat-kegiatan-mahasiswa') }}" class="text-white">Lihat data surat</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->bagian == 'subbagian kemahasiswaan')
                            <div class="col-md-4 stretch-card grid-margin">
                                <div class="card bg-gradient-success card-img-holder text-white">
                                    <div class="card-body">
                                        <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                            alt="circle-image" />
                                        <h4 class="font-weight-normal mb-3">Waktu Cuti <i
                                                class="mdi mdi-clock mdi-24px float-right"></i>
                                        </h4>
                                        <h2 class="mb-5">
                                            {{ $countAllWaktuCuti > 0 ? $countAllWaktuCuti.' Waktu Cuti' : 'Data Waktu Cuti Kosong' }}
                                        </h2>
                                        <h6 class="card-text">
                                            <a href="{{ url('operator/waktu-cuti') }}" class="text-white">Lihat data waktu cuti</a>
                                        </h6>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                    @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian pendidikan dan pengajaran')
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
                                        <a href="{{ url('operator/surat-keterangan-lulus') }}" class="text-white">Lihat data surat</a>
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
                                        <a href="{{ url('operator/surat-permohonan-pengambilan-material') }}" class="text-white">Lihat data surat</a>
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
                                        {{ $countAllSuratPenelitian > 0 ? $countAllSuratPenelitian.' Surat' : 'Data Surat Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('operator/surat-permohonan-survei') }}" class="text-white">Lihat data surat</a>
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
                                        <a href="{{ url('operator/surat-rekomendasi-penelitian') }}" class="text-white">Lihat data surat</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Permohonan Pengambilan Data Awal<i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratDataAwal > 0 ? $countAllSuratDataAwal.' Surat' : 'Data Surat Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('operator/surat-permohonan-pengambilan-data-awal') }}" class="text-white">Lihat data surat</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian kemahasiswaan')
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
                                        <a href="{{ url('operator/pendaftaran-cuti') }}" class="text-white">Lihat data pendaftaran</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Surat Masuk</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratMasuk > 0)
                                <div class="table-responsive dashboard">
                                    <table class="table display no-warp" id='datatables' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nomor Surat</th>
                                                <th data-priority="2"> Instansi</th>
                                                <th> Perihal</th>
                                                <th> Tanggal Surat Masuk</th>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat masuk belum ada.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian kemahasiswaan')
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
                                        <table class="table display no-warp" id='datatables1' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama</th>
                                                    <th> Nomor Surat</th>
                                                    <th> Tahun Akademik</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                        <table class="table display no-warp" id='datatables2' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                        <table class="table display no-warp" id='datatables3' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama Kegiatan</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                        <table class="table display no-warp" id='datatables4' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama Kegiatan</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                        <table class="table display no-warp" id='datatables5' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama Kegiatan</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                        <table class="table display no-warp" id='datatables6' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                    </div>  
                    @if(Auth::user()->bagian == 'subbagian kemahasiswaan')
                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-12 col-md-6">
                                                <h4>Surat Pengantar Cuti</h4>
                                            </div>
                                        </div>
                                        <hr class="mb-4">
                                        @if ($countAllSuratCuti > 0)
                                        <div class="table-responsive dashboard">
                                            <table class="table display no-warp" id='datatables7' width="100%">
                                                <thead>
                                                    <tr>
                                                        <th data-priority="1"> Nomor Surat</th>
                                                        <th> Tahun Akademik</th>
                                                        <th> Status</th>
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
                                                    {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Surat Kosong!' }}
                                                </h4>
                                                <p class="text-muted">
                                                    {{ (Session::has('search')) ? Session::get('search') : '  Data surat pengantar cuti terlebih dahulu.' }}
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
                                                <h4>Surat Pengantar Beasiswa</h4>
                                            </div>
                                        </div>
                                        <hr class="mb-4">
                                        @if ($countAllSuratBeasiswa > 0)
                                        <div class="table-responsive dashboard">
                                            <table class="table display no-warp" id='datatables8' width="100%">
                                                <thead>
                                                    <tr>
                                                        <th data-priority="1"> Nomor Surat</th>
                                                        <th> Hal</th>
                                                        <th> Status</th>
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
                                                    {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Surat Kosong!' }}
                                                </h4>
                                                <p class="text-muted">
                                                    {{ (Session::has('search')) ? Session::get('search') : '  Data surat pengantar beasiswa terlebih dahulu.' }}
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
                                            <h4>Surat Kegiatan Mahasiswa</h4>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllSuratPindah > 0)
                                    <div class="table-responsive dashboard">
                                        <table class="table display no-warp" id='datatables9' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama Kegiatan</th>
                                                    <th> Nomor Surat</th>
                                                    <th> Ormawa</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                    </div>
                    @if(Auth::user()->bagian == 'subbagian kemahasiswaan')
                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row mb-3">
                                            <div class="col-12 col-md-6">
                                                <h4>Waktu Cuti</h4>
                                            </div>
                                        </div>
                                        <hr class="mb-4">
                                        @if ($countAllWaktuCuti > 0)
                                        <div class="table-responsive dashboard">
                                            <table class="table display no-warp" id='datatables10' width="100%">
                                                <thead>
                                                    <tr>
                                                        <th data-priority="1"> Tahun Akademik</th>
                                                        <th> Tanggal Awal Cuti</th>
                                                        <th> Tanggal Akhir Cuti</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                        @else
                                        <div class="row">
                                            <div class="col text-center">
                                                <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                                <h4 class="display-4 mt-3">
                                                    {{ (Session::has('search-title')) ? Session::get('search-title') : ' Waktu Cuti Kosong!' }}
                                                </h4>
                                                <p class="text-muted">
                                                    {{ (Session::has('search')) ? Session::get('search') : ' Data waktu cuti belum ada.' }}
                                                </p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div> 
                    @endif
                @endif
                @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian pendidikan dan pengajaran')
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
                                        <table class="table display no-warp" id='datatables11' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                        <table class="table display no-warp" id='datatables12' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama Kegiatan</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                        <table class="table display no-warp" id='datatables13' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                        <table class="table display no-warp" id='datatables14' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                    <div class="table-responsive dashboard">
                                        <table class="table display no-warp" id='datatables15' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama</th>
                                                    <th> Nomor Surat</th>
                                                    <th data-priority="2"> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                @endif

                @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian kemahasiswaan')
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
                                        <table class="table display no-warp" id='datatables16' width="100%">
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pendaftaran Cuti Kosong!' }}
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
                @endif
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

<div class="modal fade" id="suratMasuk" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-masuk-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
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

<div class="modal fade" id="disposisi" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Disposisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='disposisi-detail-content'></div>
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
@endsection

@section('datatables-javascript')
    <script>
        let linkMhs = "{{ url('operator/detail/mahasiswa') }}";
        let linkSuratMasuk = "{{ url('operator/surat-masuk') }}";

        @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian kemahasiswaan')
            let linkSuratAktif = "{{ url('operator/surat-keterangan-aktif-kuliah') }}";
            let linkSuratBaik = "{{ url('operator/surat-keterangan-kelakuan-baik') }}";
            let linkSuratDispensasi = "{{ url('operator/surat-dispensasi') }}";
            let linkSuratRekomendasi = "{{ url('operator/surat-rekomendasi') }}";
            let linkSuratTugas = "{{ url('operator/surat-tugas') }}";
            let linkSuratPindah = "{{ url('operator/surat-persetujuan-pindah') }}";
            let linkSuratCuti = "{{ url('operator/surat-pengantar-cuti') }}";
            let linkSuratBeasiswa = "{{ url('operator/surat-pengantar-beasiswa') }}";
            let linkSuratKegiatan = "{{ url('operator/surat-kegiatan-mahasiswa') }}";
            let linkWaktuCuti = "{{ url('operator/waktu-cuti') }}";
            let linkPendaftaran = "{{ url('operator/pendaftaran-cuti') }}";
        @endif

        @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian pendidikan dan pengajaran')
            let linkSuratLulus = "{{ url('operator/surat-keterangan-lulus') }}";
            let linkSuratMaterial = "{{ url('operator/surat-permohonan-pengambilan-material') }}";
            let linkSuratSurvei = "{{ url('operator/surat-permohonan-survei') }}";
            let linkSuratPenelitian = "{{ url('operator/surat-rekomendasi-penelitian') }}";
            let linkSuratDataAwal = "{{ url('operator/surat-permohonan-pengambilan-data-awal') }}";
        @endif

        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                <a href="${linkSuratMasuk+'/'+row.id}" class="dropdown-item btn-surat-masuk-detail" data-toggle="modal" data-target="#suratMasuk">Detail</a>
                                            </div>
                                        </div>`;
                        }
                    }],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('operator/surat-masuk/all') }}',
            columns: [{
                    data: 'nomor_surat',
                },
                {
                    data: 'instansi',
                },
                {
                    data: 'perihal',
                },
                {
                    data: 'tanggal_surat_masuk',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            "pageLength": {{ $perPageDashboard }},
            "order": [[ 3, 'desc' ]],
        });

        @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian kemahasiswaan')
            $('#datatables1').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 0,
                                "data": "mahasiswa.nama",
                                "render": function ( data, type, row, meta ) {
                                    return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                                <div class="mb-1">${row.mahasiswa.nama}</div>
                                                <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                            </a>`;
                                }
                            },
                            {
                                "targets": 1,
                                "data": "surat_keterangan.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_keterangan.nomor_surat}/${row.surat_keterangan.kode_surat.kode_surat}`;
                                }
                            },
                            {
                                "targets": 2,
                                "data": "tahun_akademik",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.tahun_akademik.tahun_akademik} - ${row.semester}`;
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
                                "targets": 5,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratAktif+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#suratKeteranganDetail">
                                                                Detail</a>
                                                        <a href="${linkSuratAktif+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratAktif+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#suratKeteranganDetail">
                                                        Detail</a>`;

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
                                    @endif
                                },
                            },
                            {
                                "targets": [6,7],
                                "visible": false,
                            }
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-keterangan-aktif-kuliah/all') }}',
                columns: [{
                        data: 'mahasiswa.nama',
                    },
                    {
                        data: 'surat_keterangan.nomor_surat',
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
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                    {
                        data: 'mahasiswa.nim',
                    }, 
                    {
                        data: 'tahun_akademik.semester',
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            $('#datatables2').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 0,
                                "data": "mahasiswa.nama",
                                "render": function ( data, type, row, meta ) {
                                    return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                                <div class="mb-1">${row.mahasiswa.nama}</div>
                                                <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                            </a>`;
                                }
                            },
                            {
                                "targets": 1,
                                "data": "surat_keterangan.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_keterangan.nomor_surat}/${row.surat_keterangan.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratBaik+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#suratKeteranganDetail">
                                                                Detail</a>
                                                        <a href="${linkSuratBaik+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratBaik+'/'+row.id}" class="dropdown-item btn-surat-detail" data-toggle="modal" data-target="#suratKeteranganDetail">
                                                        Detail</a>`;

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
                                    @endif
                                },
                            },
                            {
                                "targets": [5],
                                "visible": false,
                            }
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-keterangan-kelakuan-baik/all') }}',
                columns: [{
                        data: 'mahasiswa.nama',
                    },
                    {
                        data: 'surat_keterangan.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                    {
                        data: 'nim',
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            $('#datatables3').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 1,
                                "data": "surat_dispensasi.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_dispensasi.nomor_surat}/${row.surat_dispensasi.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratDispensasi+'/'+row.id_surat_masuk}" class="dropdown-item btn-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>
                                                        <a href="${linkSuratDispensasi+'/'+row.id_surat_masuk+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratDispensasi+'/'+row.id_surat_masuk}" class="dropdown-item btn-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>`;

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
                                    @endif
                                },
                            },
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-dispensasi/all') }}',
                columns: [{
                        data: 'nama_kegiatan',
                    },
                    {
                        data: 'surat_dispensasi.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            $('#datatables4').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 1,
                                "data": "surat_rekomendasi.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_rekomendasi.nomor_surat}/${row.surat_rekomendasi.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratRekomendasi+'/'+row.id}" class="dropdown-item btn-surat-rekomendasi-detail" data-toggle="modal" data-target="#suratRekomendasi">Detail</a>
                                                        <a href="${linkSuratRekomendasi+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratRekomendasi+'/'+row.id}" class="dropdown-item btn-surat-rekomendasi-detail" data-toggle="modal" data-target="#suratRekomendasi">Detail</a>`;

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
                                    @endif
                                },
                            },
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-rekomendasi/all') }}',
                columns: [{
                        data: 'nama_kegiatan',
                    },
                    {
                        data: 'surat_rekomendasi.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });
            
            $('#datatables5').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 1,
                                "data": "surat_tugas.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_tugas.nomor_surat}/${row.surat_tugas.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratTugas+'/'+row.id}" class="dropdown-item btn-surat-tugas-detail" data-toggle="modal" data-target="#suratTugas">Detail</a>
                                                        <a href="${linkSuratTugas+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratTugas+'/'+row.id}" class="dropdown-item btn-surat-tugas-detail" data-toggle="modal" data-target="#suratTugas">Detail</a>`;

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
                                    @endif
                                },
                            },
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-tugas/all') }}',
                columns: [{
                        data: 'nama_kegiatan',
                    },
                    {
                        data: 'surat_tugas.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            $('#datatables6').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 0,
                                "data": "mahasiswa.nama",
                                "render": function ( data, type, row, meta ) {
                                    return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                                <div class="mb-1">${row.mahasiswa.nama}</div>
                                                <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                            </a>`;
                                }
                            },
                            {
                                "targets": 1,
                                "data": "surat_persetujuan_pindah.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_persetujuan_pindah.nomor_surat}/${row.surat_persetujuan_pindah.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratPindah+'/'+row.id}" class="dropdown-item btn-surat-pindah-detail" data-toggle="modal" data-target="#suratPindah">
                                                                Detail</a>
                                                        <a href="${linkSuratPindah+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratPindah+'/'+row.id}" class="dropdown-item btn-surat-pindah-detail" data-toggle="modal" data-target="#suratPindah">
                                                        Detail</a>`;

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
                                    @endif
                                },
                            },
                            {
                                "targets": [5],
                                "visible": false,
                            }
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-persetujuan-pindah/all') }}',
                columns: [{
                        data: 'mahasiswa.nama',
                    },
                    {
                        data: 'surat_persetujuan_pindah.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                    {
                        data: 'nim',
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            @if(Auth::user()->bagian == 'subbagian kemahasiswaan')
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
                                                        <a href="${linkSuratCuti+'/'+row.id}" class="dropdown-item btn-surat-pengantar-cuti-detail" data-toggle="modal" data-target="#suratCuti">Detail</a>
                                                        <a href="${linkSuratCuti+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
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
                    ajax: '{{ url('operator/surat-pengantar-cuti/all') }}',
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
                    "pageLength": {{ $perPageDashboard }},
                    "order": [[ 0, 'desc' ]],
                });

                $('#datatables8').DataTable({
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
                                                        <a href="${linkSuratBeasiswa+'/'+row.id}" class="dropdown-item btn-surat-pengantar-beasiswa-detail" data-toggle="modal" data-target="#suratBeasiswa">Detail</a>
                                                        <a href="${linkSuratBeasiswa+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    }
                                },
                            ],
                    autoWidth: false,
                    language: bahasa,
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('operator/surat-pengantar-beasiswa/all') }}',
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
                    "pageLength": {{ $perPageDashboard }},
                    "order": [[ 0, 'desc' ]],
                });
            @endif

            $('#datatables9').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 1,
                                "data": "surat_kegiatan_mahasiswa.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_kegiatan_mahasiswa.nomor_surat}/${row.surat_kegiatan_mahasiswa.kode_surat.kode_surat}`;
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
                                "targets": 5,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratKegiatan+'/'+row.id}" class="dropdown-item">Detail</a>
                                                        <a href="${linkSuratKegiatan+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratKegiatan+'/'+row.id}" class="dropdown-item">Detail</a>
                                                    <a href="${linkSuratKegiatan+'/pengajuan/disposisi/'+row.id}" class="dropdown-item btn-disposisi-detail" data-toggle="modal" data-target="#disposisi">Lihat Disposisi</a>`;

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
                                    @endif
                                },
                            },
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-kegiatan-mahasiswa/all') }}',
                columns: [{
                        data: 'nama_kegiatan',
                    },
                    {
                        data: 'surat_kegiatan_mahasiswa.nomor_surat',
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
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            @if(Auth::user()->bagian == 'subbagian kemahasiswaan')
                $('#datatables10').DataTable({
                    responsive: true,
                    columnDefs: [{
                                    "targets": 0,
                                    "data": "tahun_akademik.tahun_akademik",
                                    "render": function ( data, type, row, meta ) {
                                        return `${row.tahun_akademik.tahun_akademik} - ${row.semester}`;
                                    }
                                },
                                {
                                    "targets": [3],
                                    "visible": false,
                                },
                            ],
                    autoWidth: false,
                    language: bahasa,
                    processing: true,
                    serverSide: true,
                    ajax: '{{ url('operator/waktu-cuti/all') }}',
                    columns: [{
                            data: 'tahun_akademik.tahun_akademik',
                        },
                        {
                            data: 'tanggal_awal_cuti',
                        },
                        {
                            data: 'tanggal_akhir_cuti',
                        },
                        {
                            data: 'tahun_akademik.semester',
                        },
                    ],
                    "pageLength": {{ $perPageDashboard }},
                    "order": [[ 0, 'desc' ]],
                });
            @endif

            $('#datatables16').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 0,
                                "data": "nim",
                                "render": function ( data, type, row, meta ) {
                                    return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                                <div class="mb-1">${row.mahasiswa.nama}</div>
                                                <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                            </a>`;
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
                ajax: '{{ url('operator/pendaftaran-cuti/all') }}',
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
                "order": [[ 4, 'desc' ]],
            });
        @endif

        @if(Auth::user()->bagian == 'front office' || Auth::user()->bagian == 'subbagian pendidikan dan pengajaran')
            $('#datatables11').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 0,
                                "data": "mahasiswa.nama",
                                "render": function ( data, type, row, meta ) {
                                    return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                                <div class="mb-1">${row.mahasiswa.nama}</div>
                                                <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                            </a>`;
                                }
                            },
                            {
                                "targets": 1,
                                "data": "surat_keterangan_lulus.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_keterangan_lulus.nomor_surat}/${row.surat_keterangan_lulus.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratLulus+'/'+row.id}" class="dropdown-item btn-surat-lulus-detail" data-toggle="modal" data-target="#suratKeteranganLulus">
                                                                Detail</a>
                                                        <a href="${linkSuratLulus+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratLulus+'/'+row.id}" class="dropdown-item btn-surat-lulus-detail" data-toggle="modal" data-target="#suratKeteranganLulus">
                                                        Detail</a>`;

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
                                    @endif
                                },
                            },
                            {
                                "targets": [5],
                                "visible": false,
                            }
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-keterangan-lulus/all') }}',
                columns: [{
                        data: 'mahasiswa.nama',
                    },
                    {
                        data: 'surat_keterangan_lulus.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                    {
                        data: 'nim',
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            $('#datatables12').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 1,
                                "data": "surat_permohonan_pengambilan_material.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_permohonan_pengambilan_material.nomor_surat}/${row.surat_permohonan_pengambilan_material.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratMaterial+'/'+row.id}" class="dropdown-item btn-surat-material-detail" data-toggle="modal" data-target="#suratMaterial">
                                                                Detail</a>
                                                        <a href="${linkSuratMaterial+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratMaterial+'/'+row.id}" class="dropdown-item btn-surat-material-detail" data-toggle="modal" data-target="#suratMaterial">
                                                        Detail</a>`;

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
                                    @endif
                                },
                            },
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-permohonan-pengambilan-material/all') }}',
                columns: [{
                        data: 'nama_kegiatan',
                    },
                    {
                        data: 'surat_permohonan_pengambilan_material.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            $('#datatables13').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 0,
                                "data": "mahasiswa.nama",
                                "render": function ( data, type, row, meta ) {
                                    return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                                <div class="mb-1">${row.mahasiswa.nama}</div>
                                                <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                            </a>`;
                                }
                            },
                            {
                                "targets": 1,
                                "data": "surat_permohonan_survei.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_permohonan_survei.nomor_surat}/${row.surat_permohonan_survei.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratSurvei+'/'+row.id}" class="dropdown-item btn-surat-survei-detail" data-toggle="modal" data-target="#suratSurvei">
                                                                Detail</a>
                                                        <a href="${linkSuratSurvei+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratSurvei+'/'+row.id}" class="dropdown-item btn-surat-survei-detail" data-toggle="modal" data-target="#suratSurvei">
                                                        Detail</a>`;

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
                                    @endif
                                },
                            },
                            {
                                "targets": [5],
                                "visible": false,
                            }
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-permohonan-survei/all') }}',
                columns: [{
                        data: 'mahasiswa.nama',
                    },
                    {
                        data: 'surat_permohonan_survei.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                    {
                        data: 'nim',
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            $('#datatables14').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 0,
                                "data": "mahasiswa.nama",
                                "render": function ( data, type, row, meta ) {
                                    return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                                <div class="mb-1">${row.mahasiswa.nama}</div>
                                                <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                            </a>`;
                                }
                            },
                            {
                                "targets": 1,
                                "data": "surat_rekomendasi_penelitian.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_rekomendasi_penelitian.nomor_surat}/${row.surat_rekomendasi_penelitian.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratPenelitian+'/'+row.id}" class="dropdown-item btn-surat-penelitian-detail" data-toggle="modal" data-target="#suratPenelitian">
                                                                Detail</a>
                                                        <a href="${linkSuratPenelitian+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratPenelitian+'/'+row.id}" class="dropdown-item btn-surat-penelitian-detail" data-toggle="modal" data-target="#suratPenelitian">
                                                        Detail</a>`;

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
                                    @endif
                                },
                            },
                            {
                                "targets": [5],
                                "visible": false,
                            }
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-rekomendasi-penelitian/all') }}',
                columns: [{
                        data: 'mahasiswa.nama',
                    },
                    {
                        data: 'surat_rekomendasi_penelitian.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                    {
                        data: 'nim',
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });

            $('#datatables15').DataTable({
                responsive: true,
                columnDefs: [{
                                "targets": 0,
                                "data": "mahasiswa.nama",
                                "render": function ( data, type, row, meta ) {
                                    return `<a href="${linkMhs}/${row.mahasiswa.nim}" class="btn-detail text-dark" data-toggle="modal" data-target="#mahasiswa">
                                                <div class="mb-1">${row.mahasiswa.nama}</div>
                                                <span class="text-muted small">NIM. ${row.mahasiswa.nim}</span>
                                            </a>`;
                                }
                            },
                            {
                                "targets": 1,
                                "data": "surat_permohonan_pengambilan_data_awal.nomor_surat",
                                "render": function ( data, type, row, meta ) {
                                    return `${row.surat_permohonan_pengambilan_data_awal.nomor_surat}/${row.surat_permohonan_pengambilan_data_awal.kode_surat.kode_surat}`;
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
                                "targets": 4,
                                "data": "aksi",
                                "render": function ( data, type, row, meta ) {
                                    @if(Auth::user()->bagian != 'front office')
                                        return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        <a href="${linkSuratDataAwal+'/'+row.id}" class="dropdown-item btn-surat-data-awal-detail" data-toggle="modal" data-target="#suratDataAwal">
                                                                Detail</a>
                                                        <a href="${linkSuratDataAwal+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    </div>
                                                </div>`;
                                    @else
                                        let action = `<a href="${linkSuratDataAwal+'/'+row.id}" class="dropdown-item btn-surat-data-awal-detail" data-toggle="modal" data-target="#suratDataAwal">
                                                        Detail</a>`;

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
                                    @endif
                                },
                            },
                            {
                                "targets": [5],
                                "visible": false,
                            }
                ],
                autoWidth: false,
                language: bahasa,
                processing: true,
                serverSide: true,
                ajax: '{{ url('operator/surat-permohonan-pengambilan-data-awal/all') }}',
                columns: [{
                        data: 'mahasiswa.nama',
                    },
                    {
                        data: 'surat_permohonan_pengambilan_data_awal.nomor_surat',
                    },
                    {
                        data: 'status',
                    },
                    {
                        data: 'created_at',
                    },
                    {
                        data: 'aksi', name: 'aksi', orderable: false, searchable: false
                    },
                    {
                        data: 'nim',
                    },
                ],
                "pageLength": {{ $perPageDashboard }},
                "order": [[1,'desc']],
            });
        @endif
    </script>
@endsection