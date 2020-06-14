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
                @if(Session::get('jabatan') == 'kasubag kemahasiswaan')
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
                        @endif
                        @if($tgl->lessThanOrEqualTo($waktuCuti->tanggal_awal_cuti)) 
                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card mb-2 bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h1>Pendaftaran Cuti Pada Semester Ini Belum Di Buka</h1>
                                    </div>     
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($tgl->greaterThanOrEqualTo($waktuCuti->tanggal_akhir_cuti)) 
                        <div class="row">
                            <div class="col-12 grid-margin">
                                <div class="card mb-2 bg-danger text-white">
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
                                <div class="card mb-2 bg-danger text-white">
                                    <div class="card-body text-center">
                                        <h1>Pendaftaran Cuti Pada Semester Ini Belum Di Buka</h1>
                                    </div>     
                                </div>
                            </div>
                        </div>
                    @endif
                
                @endif
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
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
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Kode Surat <i
                                        class="mdi mdi-format-list-numbered mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllKodeSurat > 0 ? $countAllKodeSurat.' Kode Surat' : 'Kode Surat Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('pegawai/kode-surat') }}" class="text-white">Lihat kode surat</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                    @if(Session::get('jabatan') == 'kasubag kemahasiswaan')
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Masuk<i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratMasuk > 0 ? $countAllSuratMasuk.' Surat Masuk' : 'Surat Masuk Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-masuk') }}" class="text-white">Lihat surat masuk</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Keterangan Aktif Kuliah<i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratKeteranganAktif > 0 ? $countAllSuratKeteranganAktif.' Surat Keterangan Aktif Kuliah' : 'Surat Keterangan Aktif Kuliah Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-keterangan-aktif-kuliah') }}" class="text-white">Lihat surat keterangan aktif kuliah</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Keterangan Kelakuan Baik <i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratKeteranganKelakuan > 0 ? $countAllSuratKeteranganKelakuan.' Surat Keterangan Kelakuan Baik' : 'Surat Keterangan Kelakuan Baik Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-keterangan-kelakuan-baik') }}" class="text-white">Lihat surat keterangan kelakuan baik</a></h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Dispensasi <i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratDispensasi > 0 ? $countAllSuratDispensasi.' Surat Dispensasi' : 'Surat Dispensasi Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-dispensasi') }}" class="text-white">Lihat surat dispensasi</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Rekomendasi <i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratRekomendasi > 0 ? $countAllSuratRekomendasi.' Surat Rekomendasi' : 'Surat Rekomendasi Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-rekomendasi') }}" class="text-white">Lihat surat rekomendasi</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Tugas <i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratTugas > 0 ? $countAllSuratTugas.' Surat Tugas' : 'Surat Tugas Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-tugas') }}" class="text-white">Lihat surat tugas</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Persetujuan Pindah <i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratPersetujuanPindah > 0 ? $countAllSuratPersetujuanPindah.' Surat Persetujuan Pindah' : 'Surat Persetujuan Pindah Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-persetujuan-pindah') }}" class="text-white">Lihat surat persetujuan pindah</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Pengantar Cuti <i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratCuti > 0 ? $countAllSuratCuti.' Surat Pengantar Cuti' : 'Surat Pengantar Cuti Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-pengantar-cuti') }}" class="text-white">Lihat surat pengantar cuti</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Pengantar Beasiswa <i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratBeasiswa > 0 ? $countAllSuratBeasiswa.' Surat Pengantar Beasiswa' : 'Surat Pengantar Beasiswa Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-pengantar-beasiswa') }}" class="text-white">Lihat surat pengantar beasiswa</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Kegiatan Mahasiswa <i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratKegiatan > 0 ? $countAllSuratKegiatan.' Surat Kegiatan Mahasiswa' : 'Surat Kegiatan Mahasiswa Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-kegiatan-mahasiswa') }}" class="text-white">Lihat surat kegiatan mahasiswa</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-success card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Waktu Cuti <i
                                            class="mdi mdi-clock mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllWaktuCuti > 0 ? $countAllWaktuCuti.' Waktu Cuti' : 'Waktu Cuti Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/waktu-cuti') }}" class="text-white">Lihat waktu cuti</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-dark card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Pendaftaran Cuti <i
                                            class="mdi mdi-playlist-check menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllPendaftaranCuti > 0 ? $countAllPendaftaranCuti.' Pendaftaran Cuti' : 'Pendaftaran Cuti Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/pendaftaran-cuti') }}" class="text-white">Lihat pendaftaran cuti</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Keterangan Lulus <i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllsuratLulus > 0 ? $countAllsuratLulus.' Surat Keterangan Lulus' : 'Surat Keterangan Lulus Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-keterangan-lulus') }}" class="text-white">Lihat surat keterangan lulus</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">surat permohonan pengambilan material <i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratMaterial > 0 ? $countAllSuratMaterial.' Surat Permohonan Pengambilan Material' : 'Surat Permohonan Pengambilan Material Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-permohonan-pengambilan-material') }}" class="text-white">Lihat surat permohonan pengambilan material</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Permohonan Survei <i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratSurvei > 0 ? $countAllSuratSurvei.' Surat Permohonan Survei' : 'Surat Permohonan Survei Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-permohonan-survei') }}" class="text-white">Lihat surat permohonan survei</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Rekomendasi Penelitian <i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratPenelitian > 0 ? $countAllSuratPenelitian.' Surat Rekomendasi Penelitian' : 'Surat Rekomendasi Penelitian Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-rekomendasi-penelitian') }}" class="text-white">Lihat surat rekomendasi penelitian</a>
                                    </h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Permohonan Pengambilan Data Awal <i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSuratDataAwal > 0 ? $countAllSuratDataAwal.' Surat Permohonan Pengambilan Data Awal' : 'Surat Permohonan Pengambilan Data Awal Kosong' }}
                                    </h2>
                                    <h6 class="card-text">
                                        <a href="{{ url('pegawai/surat-permohonan-pengambilan-data-awal') }}" class="text-white">Lihat surat permohonan pengambilan data awal</a>
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
                                        <h4>Kode Surat</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllKodeSurat > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Kode Surat</th>
                                                <th> Jenis Surat</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($kodeSuratList as $kodeSurat)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $kodeSurat->kode_surat }}</td>
                                                <td> {{ ucwords($kodeSurat->jenis_surat) }}</td>
                                                <td> 
                                                    @if ($kodeSurat->status_aktif == 'aktif')
                                                    <label
                                                        class="badge badge-gradient-info">{{ ucwords($kodeSurat->status_aktif) }}</label>
                                                    @else
                                                    <label
                                                        class="badge badge-gradient-dark">{{ ucwords($kodeSurat->status_aktif) }}</label>
                                                    @endif
                                                </td>
                                                <td> {{ $kodeSurat->created_at->diffForHumans() }}</td>
                                                <td> {{ $kodeSurat->updated_at->diffForHumans() }}</td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Kode Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data kode surat terlebih dahulu.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(Session::get('jabatan') == 'kasubag kemahasiswaan')
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
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Instansi</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratMasukList as $suratMasuk)
                                                <tr>
                                                    <td> {{ $loop->iteration }}</td>
                                                    <td> {{ $suratMasuk->nomor_surat }}</td>
                                                    <td> {{ $suratMasuk->instansi }}</td>
                                                    <td> {{ $suratMasuk->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratMasuk->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi surat masuk terlebih dahulu.' }}
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
                                            <h4>Surat Keterangan Aktif Kuliah</h4>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllSuratKeteranganAktif > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Nama Mahasiswa</th>
                                                    <th> Semester</th>
                                                    <th> Status</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratKeteranganAktifList as $suratKeteranganAktif)
                                                @php
                                                    $kode = explode('/',$suratKeteranganAktif->kodeSurat->kode_surat);
                                                @endphp
                                                <tr>
                                                    <td> {{ $loop->iteration }}</td>
                                                    <td> {{ 'B/'.$suratKeteranganAktif->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratKeteranganAktif->created_at->year }}</td>
                                                    <td> {{ $suratKeteranganAktif->pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                                                    <td> {{ $suratKeteranganAktif->pengajuanSuratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($suratKeteranganAktif->pengajuanSuratKeterangan->tahunAkademik->semester) }}</td>
                                                    <td> 
                                                        <label class="badge badge-gradient-info">
                                                            {{ ucwords($suratKeteranganAktif->status) }}
                                                        </label>
                                                    </td>
                                                    <td> {{ $suratKeteranganAktif->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratKeteranganAktif->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat keterangan aktif kuliah terlebih dahulu.' }}
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
                                    @if ($countAllSuratKeteranganKelakuan > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Nama Mahasiswa</th>
                                                    <th> Semester</th>
                                                    <th> Status</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratKeteranganKelakuanList as $suratKeterangan)
                                                <tr>
                                                    @php
                                                        $kode = explode('/',$suratKeterangan->kodeSurat->kode_surat);
                                                    @endphp
                                                    <td> {{ $loop->iteration }}</td>
                                                    <td> {{ 'B/'.$suratKeterangan->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratKeterangan->created_at->year }}</td>
                                                    <td> {{ $suratKeterangan->pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                                                    <td> {{ $suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->tahun_akademik.' - '.ucwords($suratKeterangan->pengajuanSuratKeterangan->tahunAkademik->semester) }}</td>
                                                    <td> 
                                                        <label class="badge badge-gradient-info">
                                                            {{ ucwords($suratKeterangan->status) }}
                                                        </label>
                                                    </td>
                                                    <td> {{ $suratKeterangan->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratKeterangan->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat keterangan kelakuan baik terlebih dahulu.' }}
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
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Nama kegiatan</th>
                                                    <th> Status</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratDispensasiList as $suratDispensasi)
                                                @php
                                                    $kode = explode('/',$suratDispensasi->kodeSurat->kode_surat);
                                                @endphp
                                                <tr>
                                                    <td> {{ $loop->iteration }}</td>
                                                    @if($suratDispensasi->user->jabatan == 'dekan')
                                                        <td> {{ $suratDispensasi->nomor_surat.'/'.$suratDispensasi->kodeSurat->kode_surat.'/'.$suratDispensasi->created_at->format('Y') }}</td>
                                                    @else
                                                        <td> {{ $suratDispensasi->nomor_surat.'/'.$kode[0].'.3/.'.$kode[1].'/'.$suratDispensasi->created_at->format('Y') }}</td>
                                                    @endif
                                                    <td> {{ $suratDispensasi->nama_kegiatan }}</td>
                                                    <td> 
                                                    @if($suratDispensasi->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratDispensasi->status) }}</td></label>
                                                    @elseif($suratDispensasi->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($suratDispensasi->status) }}</td></label>
                                                    @else
                                                    <label class="badge badge-gradient-info">{{ ucwords($suratDispensasi->status) }}</td></label>
                                                    @endif
                                                    <td> {{ $suratDispensasi->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratDispensasi->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat dispensasi terlebih dahulu.' }}
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
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Nama kegiatan</th>
                                                    <th> Status</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratRekomendasiList as $suratRekomendasi)
                                                @php
                                                    $kode = explode('/',$suratRekomendasi->kodeSurat->kode_surat);
                                                @endphp
                                                <tr>
                                                    <td> {{ $loop->iteration  }}</td>
                                                    @if($suratRekomendasi->user->jabatan == 'wd3')
                                                        <td> {{ $suratRekomendasi->nomor_surat.'/'.$kode[0].'.3/'.$kode[1].'/'.$suratRekomendasi->created_at->format('Y') }}</td>
                                                    @else
                                                        <td> {{ $suratRekomendasi->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratRekomendasi->created_at->format('Y') }}</td>
                                                    @endif
                                                    <td> {{ $suratRekomendasi->nama_kegiatan }}</td>
                                                    <td> 
                                                    @if($suratRekomendasi->status == 'menunggu tanda tangan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratRekomendasi->status) }}</td></label>
                                                    @else
                                                    <label class="badge badge-gradient-info">{{ ucwords($suratRekomendasi->status) }}</td></label>
                                                    @endif
                                                    <td> {{ $suratRekomendasi->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratRekomendasi->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat rekomendasi terlebih dahulu.' }}
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
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Nama kegiatan</th>
                                                    <th> Status</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratTugasList as $suratTugas)
                                                <tr>
                                                    <td> {{ $loop->iteration  }}</td>
                                                    <td> {{ 'B/'.$suratTugas->nomor_surat.'/'.$suratTugas->kodeSurat->kode_surat.'/'.$suratTugas->created_at->format('Y') }}</td>
                                                    <td> {{ $suratTugas->nama_kegiatan }}</td>
                                                    <td> 
                                                    @if($suratTugas->status == 'menunggu tanda tangan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratTugas->status) }}</td></label>
                                                    @else
                                                    <label class="badge badge-gradient-info">{{ ucwords($suratTugas->status) }}</td></label>
                                                    @endif
                                                    <td> {{ $suratTugas->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratTugas->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat tugas terlebih dahulu.' }}
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
                                    @if ($countAllSuratPersetujuanPindah > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Nama Mahasiswa</th>
                                                    <th> Status</th>
                                                    <th> Di Buat</th>
                                                    <th> Du Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratPersetujuanPindahList as $suratPersetujuanPindah)
                                                <tr>
                                                    <td> {{ $loop->iteration }}</td>
                                                    <td> {{ 'B/'.$suratPersetujuanPindah->nomor_surat.'/'.$suratPersetujuanPindah->kodeSurat->kode_surat.'/'.$suratPersetujuanPindah->created_at->year }}</td>
                                                    <td> {{ $suratPersetujuanPindah->pengajuanSuratPersetujuanPindah->mahasiswa->nama }}</td>
                                                    <td> 
                                                        @if($suratPersetujuanPindah->status == 'menunggu tanda tangan')
                                                        <label class="badge badge-gradient-warning text-dark">
                                                            {{ ucwords($suratPersetujuanPindah->status) }}
                                                        </label>
                                                        @else
                                                        <label class="badge badge-gradient-info">
                                                            {{ ucwords($suratPersetujuanPindah->status) }}
                                                        </label>
                                                        @endif
                                                    </td>
                                                    <td> {{ $suratPersetujuanPindah->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratPersetujuanPindah->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat persetujuan pindah terlebih dahulu.' }}
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
                                            <h4>Surat Pengantar Cuti</h4>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllSuratCuti > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Tahun Akademik</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratCutiList as $suratCuti)
                                                @php
                                                    $kode = explode('/',$suratCuti->kodeSurat->kode_surat);
                                                @endphp
                                                <tr>
                                                    <td> {{ $loop->iteration  }}</td>
                                                    <td> {{ $suratCuti->nomor_surat.'/'.$kode[0].'.4/.'.$kode[1].'/'.$suratCuti->created_at->format('Y') }}</td>
                                                    <td> {{ $suratCuti->waktuCuti->tahunAkademik->tahun_akademik }} - {{ ucwords($suratCuti->waktuCuti->tahunAkademik->semester) }}</td>
                                                    <td> {{ $suratCuti->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratCuti->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat pengantar cuti terlebih dahulu.' }}
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
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Status</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratBeasiswaList as $suratBeasiswa)
                                                <tr>
                                                    <td> {{ $loop->iteration  }}</td>
                                                    <td> {{ 'B/'.$suratBeasiswa->nomor_surat.'/'.$suratBeasiswa->kodeSurat->kode_surat.'/'.$suratBeasiswa->created_at->format('Y') }}</td>
                                                    <td> 
                                                    @if($suratBeasiswa->status == 'menunggu tanda tangan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratBeasiswa->status) }}</td></label>
                                                    @else
                                                    <label class="badge badge-gradient-info">{{ ucwords($suratBeasiswa->status) }}</td></label>
                                                    @endif
                                                    <td> {{ $suratBeasiswa->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratBeasiswa->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat pengantar beasiswa terlebih dahulu.' }}
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
                                            <h4>Surat Kegiatan Mahasiswa</h4>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllSuratKegiatan > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nomor Surat</th>
                                                    <th> Nama Kegiatan</th>
                                                    <th> Ormawa</th>
                                                    <th> Status</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suratKegiatanList as $suratKegiatan)
                                                <tr>
                                                    <td> {{ $loop->iteration }}</td>
                                                    <td> {{ $suratKegiatan->nomor_surat.'/'.$suratKegiatan->kodeSurat->kode_surat.'/'.$suratKegiatan->created_at->year }}</td>
                                                    <td> {{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->nama_kegiatan }}</td>
                                                    <td> {{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->ormawa->nama }}</td>
                                                    <td> 
                                                        <label class="badge badge-gradient-info">
                                                            {{ ucwords($suratKegiatan->status) }}
                                                        </label>
                                                    </td>
                                                    <td> {{ $suratKegiatan->created_at->diffForHumans() }}</td>
                                                    <td> {{ $suratKegiatan->updated_at->diffForHumans() }}</td>

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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat kegiatan mahasiswa terlebih dahulu.' }}
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
                                            <h4>Waktu Cuti</h4>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllWaktuCuti > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Tahun Akademik</th>
                                                    <th> Tanggal Awal Cuti</th>
                                                    <th> Tanggal Akhir Cuti</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($waktuCutiList as $waktuCuti)
                                                <tr>
                                                    <td> {{ $loop->iteration  }}</td>
                                                    <td> {{ $waktuCuti->tahunAkademik->tahun_akademik }} - {{ ucwords($waktuCuti->tahunAkademik->semester) }}</td>
                                                    <td> {{ $waktuCuti->tanggal_awal_cuti->isoFormat('D MMMM Y') }}</td>
                                                    <td> {{ $waktuCuti->tanggal_akhir_cuti->isoFormat('D MMMM Y') }}</td>
                                                    <td> {{ $waktuCuti->created_at->diffForHumans() }}</td>
                                                    <td> {{ $waktuCuti->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Waktu Cuti Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi waktu cuti terlebih dahulu.' }}
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
                                    @if ($countAllPendaftaranCuti > 0)
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th> No. </th>
                                                    <th> Nama Mahasiswa</th>
                                                    <th> Tahun Akademik</th>
                                                    <th> Alasan Cuti</th>
                                                    <th> Status</th>
                                                    <th> Keterangan</th>
                                                    <th> Di Buat</th>
                                                    <th> Di Ubah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($pendaftaranCutiList as $pendaftaranCuti)
                                                <tr>
                                                    <td> {{ $loop->iteration }}</td>
                                                    <td> {{ $pendaftaranCuti->mahasiswa->nama }}</td>
                                                    <td> {{ $pendaftaranCuti->waktuCuti->tahunAkademik->tahun_akademik }} - {{ ucwords($pendaftaranCuti->waktuCuti->tahunAkademik->semester) }}</td>
                                                    <td> {{ $pendaftaranCuti->alasan_cuti }}</td>
                                                    <td> 
                                                        <label class="badge badge-gradient-info">
                                                            {{ ucwords($pendaftaranCuti->status) }}
                                                        </label>
                                                    </td>
                                                    <td> {{ $pendaftaranCuti->keterangan }}</td>
                                                    <td> {{ $pendaftaranCuti->created_at->diffForHumans() }}</td>
                                                    <td> {{ $pendaftaranCuti->updated_at->diffForHumans() }}</td>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pendaftaran Cuti Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi pendaftaran cuti terlebih dahulu.' }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>               
                
                @else
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
                                @if ($countAllsuratLulus > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratLulusList as $suratLulus)
                                            <tr>
                                                @php
                                                    $kode = explode('/',$suratLulus->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ 'B/'.$suratLulus->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratLulus->created_at->year }}</td>
                                                <td> {{ $suratLulus->pengajuanSuratKeteranganLulus->mahasiswa->nama }}</td>
                                                <td> 
                                                    @if($suratLulus->status == 'menunggu tanda tangan')
                                                        <label class="badge badge-gradient-warning text-dark">
                                                            {{ ucwords($suratLulus->status) }}
                                                        </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($suratLulus->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $suratLulus->created_at->diffForHumans() }}</td>
                                                <td> {{ $suratLulus->updated_at->diffForHumans() }}</td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat keterangan lulus terlebih dahulu.' }}
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
                                        <h4>Surat permohonan pengambilan material</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSuratMaterial > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Kegiatan</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratMaterialList as $suratMaterial)
                                            <tr>
                                                @php
                                                    $kode = explode('/',$suratMaterial->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ 'B/'.$suratMaterial->nomor_surat.'/'.$kode[0].'.4/'.$kode[1].'/'.$suratMaterial->created_at->year }}</td>
                                                <td> {{ $suratMaterial->pengajuanSuratPermohonanPengambilanMaterial->nama_kegiatan }}</td>
                                                <td> 
                                                    @if($suratMaterial->status == 'menunggu tanda tangan')
                                                         <label class="badge badge-gradient-warning text-dark">
                                                            {{ ucwords($suratMaterial->status) }}
                                                        </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($suratMaterial->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $suratMaterial->created_at->diffForHumans() }}</td>
                                                <td> {{ $suratMaterial->updated_at->diffForHumans() }}</td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat permohonan pengambilan material terlebih dahulu.' }}
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
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratSurveiList as $suratSurvei)
                                            <tr>
                                                @php
                                                    $kode = explode('/',$suratSurvei->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ 'B/'.$suratSurvei->nomor_surat.'/'.$kode[0].'.1/'.$kode[1].'/'.$suratSurvei->created_at->year }}</td>
                                                <td> {{ $suratSurvei->pengajuanSuratPermohonanSurvei->mahasiswa->nama }}</td>
                                                <td> 
                                                    @if($suratSurvei->status == 'menunggu tanda tangan')
                                                         <label class="badge badge-gradient-warning text-dark">
                                                            {{ ucwords($suratSurvei->status) }}
                                                        </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($suratSurvei->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $suratSurvei->created_at->diffForHumans() }}</td>
                                                <td> {{ $suratSurvei->updated_at->diffForHumans() }}</td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat permohonan survei terlebih dahulu.' }}
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
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratPenelitianList as $suratPenelitian)
                                            <tr>
                                                @php
                                                    $kode = explode('/',$suratPenelitian->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ 'B/'.$suratPenelitian->nomor_surat.'/'.$kode[0].'.1/'.$kode[1].'/'.$suratPenelitian->created_at->year }}</td>
                                                <td> {{ $suratPenelitian->pengajuanSuratRekomendasiPenelitian->mahasiswa->nama }}</td>
                                                <td> 
                                                    @if($suratPenelitian->status == 'menunggu tanda tangan')
                                                         <label class="badge badge-gradient-warning text-dark">
                                                            {{ ucwords($suratPenelitian->status) }}
                                                        </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($suratPenelitian->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $suratPenelitian->created_at->diffForHumans() }}</td>
                                                <td> {{ $suratPenelitian->updated_at->diffForHumans() }}</td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat rekomendasi penelitian terlebih dahulu.' }}
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
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($suratDataAwalList as $suratDataAwal)
                                            <tr>
                                                @php
                                                    $kode = explode('/',$suratDataAwal->kodeSurat->kode_surat);
                                                @endphp
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ 'B/'.$suratDataAwal->nomor_surat.'/'.$kode[0].'.1/'.$kode[1].'/'.$suratDataAwal->created_at->year }}</td>
                                                <td> {{ $suratDataAwal->pengajuanSuratPermohonanPengambilanDataAwal->mahasiswa->nama }}</td>
                                                <td> 
                                                    @if($suratDataAwal->status == 'menunggu tanda tangan')
                                                         <label class="badge badge-gradient-warning text-dark">
                                                            {{ ucwords($suratDataAwal->status) }}
                                                        </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($suratDataAwal->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $suratDataAwal->created_at->diffForHumans() }}</td>
                                                <td> {{ $suratDataAwal->updated_at->diffForHumans() }}</td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat permohonan pengambilan data awal terlebih dahulu.' }}
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
@endsection

@section('timer-javascript')
<script>
    $(document).ready(function() {
        $('.flip-3').on('done', function() {
            console.log('doooooonnnnnee!');
        });
        $('.flip-3').on('beforeFlipping', function(e, data) {
            console.log('beforeFlipping:', data);
        });
        $('.flip-3').on('afterFlipping', function(e, data) {
            console.log('afterFlipping:', data);
        });
    });
</script>
@endsection