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
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat Keterangan Aktif Kuliah<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuan > 0 ? $countAllPengajuan.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat Keterangan Aktif Kuliah<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanBaik > 0 ? $countAllPengajuanBaik.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Dispensasi <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllDispensasi > 0 ? $countAllDispensasi.' Surat Dispensasi' : 'Surat Dispensasi Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
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
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
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
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat Persetujuan Pindah<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanPindah > 0 ? $countAllPengajuanPindah.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    @if($pengajuanKegiatanList != null)
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute" alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat Kegiatan Mahasiswa <i class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanKegiatan > 0 ? $countAllPengajuanKegiatan.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    @endif
                     <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pendaftaran Cuti <i
                                        class="mdi mdi-playlist-check mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countPendaftaranCuti > 0 ? $countPendaftaranCuti.' Pendaftaran Cuti' : 'Pendaftaran Cuti Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
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
                                        <h4>Pengajuan Surat Keterangan Aktif Kuliah</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPengajuan > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama </th>
                                                <th> Jenis Surat</th>
                                                <th> Status</th>
                                                <th> Keterangan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanSuratKeteranganAktifList as $pengajuanSuratKeteranganAktif)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $pengajuanSuratKeteranganAktif->mahasiswa->nama }}</td>
                                                <td> {{ ucwords($pengajuanSuratKeteranganAktif->jenis_surat)  }}</td>
                                                <td>
                                                    @if ($pengajuanSuratKeteranganAktif->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($pengajuanSuratKeteranganAktif->status) }}
                                                    </label>
                                                    @elseif($pengajuanSuratKeteranganAktif->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">
                                                        {{ ucwords($pengajuanSuratKeteranganAktif->status) }}
                                                    </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($pengajuanSuratKeteranganAktif->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $pengajuanSuratKeteranganAktif->keterangan }}</td>
                                                <td> {{ $pengajuanSuratKeteranganAktif->created_at->diffForHumans() }}</td>
                                                <td> {{ $pengajuanSuratKeteranganAktif->updated_at->diffForHumans() }}</td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Pengajuan surat keterangan aktif kuliah belum ada.' }}
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
                                        <h4>Pengajuan Surat Keterangan Kelakuan Baik</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPengajuanBaik > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama </th>
                                                <th> Jenis Surat</th>
                                                <th> Status</th>
                                                <th> Keterangan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanSuratKeteranganList as $pengajuanSuratKeterangan)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $pengajuanSuratKeterangan->mahasiswa->nama }}</td>
                                                <td> {{ ucwords($pengajuanSuratKeterangan->jenis_surat)  }}</td>
                                                <td>
                                                    @if ($pengajuanSuratKeterangan->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($pengajuanSuratKeterangan->status) }}
                                                    </label>
                                                    @elseif($pengajuanSuratKeterangan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">
                                                        {{ ucwords($pengajuanSuratKeterangan->status) }}
                                                    </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($pengajuanSuratKeterangan->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $pengajuanSuratKeterangan->keterangan }}</td>
                                                <td> {{ $pengajuanSuratKeterangan->created_at->diffForHumans() }}</td>
                                                <td> {{ $pengajuanSuratKeterangan->updated_at->diffForHumans() }}</td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Pengajuan surat keterangan kelakuan baik belum ada.' }}
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
                                @if ($countAllDispensasi > 0)
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
                                                <td> {{ $loop->iteration  }}</td>
                                                @if($suratDispensasi->user->jabatan == 'dekan')
                                                    <td> {{ $suratDispensasi->nomor_surat.'/'.$suratDispensasi->kodeSurat->kode_surat.'/'.$suratDispensasi->created_at->format('Y') }}</td>
                                                @else
                                                    <td> {{ $suratDispensasi->nomor_surat.'/'.$kode[0].'.3/.'.$kode[1].'/'.$suratDispensasi->created_at->format('Y') }}</td>
                                                @endif
                                                <td> {{ $suratDispensasi->nama_kegiatan }}</td>
                                                <td> 
                                                @if($suratDispensasi->status == 'menunggu tanda tangan')
                                                <label class="badge badge-gradient-warning text-dark">{{ ucwords($suratDispensasi->status) }}</td></label>
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
                                                    <td> {{ $suratRekomendasi->nomor_surat.'/'.$kode[0].'.3/.'.$kode[1].'/'.$suratRekomendasi->created_at->format('Y') }}</td>
                                                @else
                                                    <td> {{ $suratRekomendasi->nomor_surat.'/'.$kode[0].'.4/.'.$kode[1].'/'.$suratRekomendasi->created_at->format('Y') }}</td>
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
                                        <h4>Pengajuan Surat Persetujuan Pindah</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPengajuanPindah > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama </th>
                                                <th> Status</th>
                                                <th> Keterangan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanSuratPindahList as $pengajuanSuratPindah)
                                            <tr>
                                                <td> {{ $loop->iteration }}</td>
                                                <td> {{ $pengajuanSuratPindah->mahasiswa->nama }}</td>
                                                <td>
                                                    @if ($pengajuanSuratPindah->status == 'diajukan' || $pengajuanSuratPindah->status == 'menunggu tanda tangan')
                                                    <label class="badge badge-gradient-warning text-dark">
                                                        {{ ucwords($pengajuanSuratPindah->status) }}
                                                    </label>
                                                    @elseif($pengajuanSuratPindah->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">
                                                        {{ ucwords($pengajuanSuratPindah->status) }}
                                                    </label>
                                                    @else
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($pengajuanSuratPindah->status) }}
                                                    </label>
                                                    @endif
                                                </td>
                                                <td> {{ $pengajuanSuratPindah->keterangan }}</td>
                                                <td> {{ $pengajuanSuratPindah->created_at->diffForHumans() }}</td>
                                                <td> {{ $pengajuanSuratPindah->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    @if($pengajuanSuratPindah->status == 'selesai')
                                                    <a href="{{ url('mahasiswa/surat-persetujuan-pindah/'.$pengajuanSuratPindah->id) }}" class="btn-surat-pindah-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#pindahDetail">
                                                    <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                    Detail</a>
                                                    @else
                                                    <a href="{{ url('mahasiswa/pengajuan/surat-persetujuan-pindah/'.$pengajuanSuratPindah->id) }}" class="btn-pengajuan-pindah btn btn-outline-info btn-sm" data-toggle="modal" data-target="#persetujuanDetail">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
                                                    @endif

                                                    <a href="{{ url('mahasiswa/pengajuan/surat-persetujuan-pindah/'.$pengajuanSuratPindah->id.'/progress') }}" class="btn-pengajuan-pindah-progress btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi mdi-information btn-icon-prepend"></i>
                                                        Lihat Progress Surat</a>
                                                    @if($pengajuanSuratPindah->status == 'diajukan')
                                                    <a href="{{ url('mahasiswa/pengajuan/surat-persetujuan-pindah/'.$pengajuanSuratPindah->id.'/edit') }}" class="btn btn-sm btn-warning text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit</a>
                                                    </a>
                                                    @endif
                                                    @if ($pengajuanSuratPindah->status == 'selesai')
                                                    <a href="{{ url('mahasiswa/pengajuan/surat-persetujuan-pindah/'.$pengajuanSuratPindah->id.'/cetak') }}" class="btn btn-info btn-sm">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>
                                                    @endif
                                                </td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Pengajuan surat persetujuan pindah belum ada.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if($pengajuanKegiatanList != null)
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Pengajuan Surat Kegiatan Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllPengajuanKegiatan > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Kegiatan</th>
                                                <th> Ormawa</th>
                                                <th> Status</th>
                                                <th> Keterangan</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanKegiatanList as $pengajuanKegiatan)
                                            <tr>
                                                <td> {{ $loop->iteration  }}</td>
                                                <td> {{ $pengajuanKegiatan->nama_kegiatan }}</td>
                                                <td> {{ $pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->nama }}</td>
                                                <td>
                                                @if($pengajuanKegiatan->status == 'selesai')
                                                    <label class="badge badge-gradient-info">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @elseif($pengajuanKegiatan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @elseif($pengajuanKegiatan->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @else
                                                    <label class="badge badge-gradient-success">{{ ucwords($pengajuanKegiatan->status) }}</label>
                                                @endif
                                                </td>
                                                <td> {{ $pengajuanKegiatan->keterangan }}</td>
                                                <td> {{ $pengajuanKegiatan->created_at->diffForHumans() }}</td>
                                                <td> {{ $pengajuanKegiatan->updated_at->diffForHumans() }}</td>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Pengajuan surat kegiatan mahasiswa belum ada.' }}
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
                                        <h4>Pendaftaran Cuti</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPendaftaranCuti > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama </th>
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
                                                @if($pendaftaranCuti->status == 'diajukan')
                                                <label class="badge badge-gradient-warning text-dark">{{ ucwords($pendaftaranCuti->status) }}</td></label>
                                                @elseif($pendaftaranCuti->status == 'ditolak')
                                                <label class="badge badge-gradient-danger">{{ ucwords($pendaftaranCuti->status) }}</td></label>
                                                @else
                                                <label class="badge badge-gradient-info">{{ ucwords($pendaftaranCuti->status) }}</td></label>
                                                @endif
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
                                            {{ (Session::has('search')) ? Session::get('search') : 'Pendaftaran cuti belum ada.' }}
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