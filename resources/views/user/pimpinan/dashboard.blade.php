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
                                <h4 class="font-weight-normal mb-3">Surat Keterangan Aktif Kuliah<i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratKeteranganAktif > 0 ? $countAllSuratKeteranganAktif.' Surat Keterangan Aktif Kuliah' : 'Surat Keterangan Aktif Kuliah Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('pimpinan/surat-keterangan-aktif-kuliah') }}" class="text-white">Lihat surat keterangan aktif kuliah</a>
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
                                <a href="{{ url('pimpinan/surat-keterangan-kelakuan-baik') }}" class="text-white">Lihat surat keterangan kelakuan baik</a></h6>
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
                                    {{ $countAllSuratDispensasi > 0 ? $countAllSuratDispensasi.' Surat Dispensasi' : 'Surat Dispensasi Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('pimpinan/surat-dispensasi') }}" class="text-white">Lihat surat dispensasi</a>
                                </h6>
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
                                <h6 class="card-text">
                                    <a href="{{ url('pimpinan/surat-rekomendasi') }}" class="text-white">Lihat surat rekomendasi</a>
                                </h6>
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
                                <h6 class="card-text">
                                    <a href="{{ url('pimpinan/surat-tugas') }}" class="text-white">Lihat surat tugas</a></h6>
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
                                    <a href="{{ url('pimpinan/surat-persetujuan-pindah') }}" class="text-white">Lihat surat persetujuan pindah</a></h6>
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
                                    <a href="{{ url('pimpinan/surat-pengantar-cuti') }}" class="text-white">Lihat surat pengantar cuti</a></h6>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
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
                                    <a href="{{ url('pimpinan/surat-pengantar-beasiswa') }}" class="text-white">Lihat surat pengantar beasiswa</a></h6>
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 stretch-card grid-margin">
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
                                    <a href="{{ url('pimpinan/surat-kegiatan-mahasiswa') }}" class="text-white">Lihat surat kegiatan mahasiswa</a></h6
                                </h6>
                            </div>
                        </div>
                    </div>
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
                                    <a href="{{ url('pimpinan/surat-keterangan-lulus') }}" class="text-white">Lihat surat keterangan lulus</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                     <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Permohonan Pengambilan Material <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratMaterial > 0 ? $countAllSuratMaterial.' Surat Permohonan Pengambilan Material' : 'Surat Permohonan Pengambilan Material Kosong' }}
                                </h2>
                                <h6 class="card-text">
                                    <a href="{{ url('pimpinan/surat-permohonan-pengambilan-material') }}" class="text-white">Lihat surat permohonan pengambilan material</a>
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
                                    <a href="{{ url('pimpinan/surat-permohonan-survei') }}" class="text-white">Lihat surat permohonan survei</a>
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
                                    <a href="{{ url('pimpinan/surat-rekomendasi-penelitian') }}" class="text-white">Lihat surat rekomendasi penelitian</a>
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
                                    <a href="{{ url('pimpinan/surat-permohonan-pengambilan-data-awal') }}" class="text-white">Lihat surat permohonan pengambilan data awal</a>
                                </h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body pb-5 pb-md-2">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <div class="clearfix">
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <h4>Subbagian Kemahasiswaan</h4>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        {{ Form::open(['url'=>'pimpinan/search','method'=>'get']) }}
                                            <div class="form-row">
                                                <div class="col col-md-4 mt-1">
                                                    {{ Form::select('bulan',$bulan,(isset($bln)) ? $bln:request()->get('bulan'),['class'=>'form-control search']) }}
                                                </div>
                                                <div class="col col-md-4 mt-1">
                                                    {{ Form::select('tahun',$tahun,(isset($thn)) ? $thn:request()->get('tahun'),['class'=>'form-control search']) }}
                                                </div>
                                                <div class="col-sm-12 col-md">
                                                    <button class="btn btn-success btn-sm btn-tambah mt-2 mt-md-0" type="submit">
                                                        <i class="mdi mdi-magnify btn-icon-prepend"></i>
                                                        Tampilkan
                                                    </button>
                                                </div>
                                            </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                                <canvas id="kemahasiswaan" class="mt-4 chartjs-render-monitor  mb-3 mb-md-0" style="display: block; height: 298px; width: 596px;" width="745" height="372"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body pb-5 pb-md-2">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <div class="clearfix">
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6">
                                        <h4>Subbagian Pengajaran Dan Pendidikan</h4>
                                    </div>
                                    <div class="col-12 col-md-6">
                                        {{ Form::open(['url'=>'pimpinan/search','method'=>'get']) }}
                                            <div class="form-row">
                                                <div class="col col-md-4 mt-1">
                                                    {{ Form::select('bulan',$bulan,(isset($bln)) ? $bln:request()->get('bulan'),['class'=>'form-control search']) }}
                                                </div>
                                                <div class="col col-md-4 mt-1">
                                                    {{ Form::select('tahun',$tahun,(isset($thn)) ? $thn:request()->get('tahun'),['class'=>'form-control search']) }}
                                                </div>
                                                <div class="col-sm-12 col-md">
                                                    <button class="btn btn-success btn-sm btn-tambah mt-2 mt-md-0" type="submit">
                                                        <i class="mdi mdi-magnify btn-icon-prepend"></i>
                                                        Tampilkan
                                                    </button>
                                                </div>
                                            </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                                <canvas id="pengajaran_pendidikan" class="mt-4 chartjs-render-monitor  mb-3 mb-md-0" style="display: block; height: 298px; width: 596px;" width="745" height="372"></canvas>
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
                                                <th> Di Ubah</th>
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
                                        <h4>Surat Permohonan Pengambilan Material</h4>
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
                                                    <label class="badge badge-gradient-info">{{ ucwords($suratMaterial->status) }}</td></label>
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
            </div>
            @include('layout.footer')
        </div>
    </div>
</div>
@endsection

@section('chart-javascript')
<script>
    var data = [<?php echo '"'.implode('","', $chartKemahasiswaan).'"' ?>];
    var dataPendidikan = [<?php echo '"'.implode('","', $chartPendidikanPengajaran).'"' ?>]; 
    var ctx = document.getElementById('kemahasiswaan').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Surat Keterangan Aktif Kuliah','Surat Keterangan Kelakuan Baik','Surat Dispensasi','Surat Pengantar Cuti','Surat Rekomendasi','Surat Persetujuan Pindah','Surat Tugas','Surat Pengantar Beasiswa','Surat Kegiatan Mahasiswa'],
            datasets: [{
                label: '# Data Surat',
                data: data,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(75, 103, 235, 0.2)',
                    'rgba(125, 92, 14, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(75, 103, 235, 1)',
                    'rgba(125, 92, 14, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    
    var ctx1 = document.getElementById('pengajaran_pendidikan').getContext('2d');
    var myChart = new Chart(ctx1, {
        type: 'bar',
        data: {
            labels: ['Surat Keterangan Lulus','Surat Permohonan Pengambilan Material','Surat Permohonan Survei','Surat Rekomendasi Penelitian','Surat Permohonan Pengambilan Data Awal'],
            datasets: [{
                label: '# Data Surat',
                data: dataPendidikan,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>
@endsection