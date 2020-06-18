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
                                                    <label class="badge badge-gradient-dark text-dark">Data mahasiswa belum ada</label>   
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
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Surat Keterangan Aktif Kuliah</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($suratKeteranganAktifList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Semester</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-keterangan-aktif-kuliah/'.$suratKeteranganAktif->id_pengajuan_surat_keterangan) }}" class="btn-surat-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
                                                    {{ Form::close() }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $suratKeteranganAktifList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan aktif kuliah kosong.' }}
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
                                @if ($suratKeteranganKelakuanList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Semester</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                     <a href="{{ url('pimpinan/detail/mahasiswa/'.$suratKeterangan->pengajuanSuratKeterangan->nim) }}" class="btn-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-account btn-icon-prepend"></i>
                                                        Detail</a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan kelakuan baik kosong.' }}
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
                                @if ($suratDispensasiList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama kegiatan</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-dispensasi/'.$suratDispensasi->id_surat_masuk) }}" class="btn btn-outline-info btn-sm btn-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat dispensasi kosong.' }}
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
                                @if ($suratRekomendasiList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama kegiatan</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-rekomendasi/'.$suratRekomendasi->id) }}" class="btn btn-outline-info btn-sm btn-surat-rekomendasi-detail" data-toggle="modal" data-target="#suratRekomendasi">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat rekomendasi kosong.' }}
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
                                @if ($suratTugasList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama kegiatan</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-tugas/'.$suratTugas->id) }}" class="btn btn-outline-info btn-sm btn-surat-tugas-detail" data-toggle="modal" data-target="#suratTugas">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat tugas kosong.' }}
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
                                @if ($suratPersetujuanPindahList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                     <a href="{{ url('pimpinan/surat-persetujuan-pindah/'.$suratPersetujuanPindah->id_pengajuan_persetujuan_pindah) }}" class="btn-surat-pindah-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#pindahDetail">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat persetujuan pindah kosong.' }}
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
                                @if ($suratCutiList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Tahun Akademik</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                     <a href="{{ url('pimpinan/surat-pengantar-cuti/'.$suratCuti->id_waktu_cuti) }}" class="btn btn-outline-info btn-sm btn-surat-pengantar-cuti-detail" data-toggle="modal" data-target="#suratCuti">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat pengantar cuti kosong.' }}
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
                                @if ($suratBeasiswaList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-pengantar-beasiswa/'.$suratBeasiswa->id) }}" class="btn btn-outline-info btn-sm btn-surat-pengantar-beasiswa-detail" data-toggle="modal" data-target="#suratBeasiswa">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat pengantar beasiswa kosong.' }}
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div> 
                @if(isset($mahasiswa->pimpinanOrmawa))
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
                                @if ($suratKegiatanList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Kegiatan</th>
                                                <th> Ormawa</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-kegiatan-mahasiswa/'.$suratKegiatan->id_pengajuan_kegiatan) }}" class="btn btn-outline-info btn-sm">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat kegiatan mahasiswa kosong.' }}
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
                                @if ($suratLulusList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-keterangan-lulus/'.$suratLulus->id_pengajuan_surat_lulus) }}" class="btn-surat-lulus-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratLulus">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat keterangan lulus kosong.' }}
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
                                @if ($suratMaterialList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Kegiatan</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-permohonan-pengambilan-material/'.$suratMaterial->id_pengajuan) }}" class="btn-surat-material-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratMaterial">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat persetujuan pindah kosong.' }}
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
                                @if ($suratSurveiList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-permohonan-survei/'.$suratSurvei->id_pengajuan) }}" class="btn-surat-survei-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratSurvei">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat permohonan survei kosong.' }}
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
                                @if ($suratPenelitianList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-rekomendasi-penelitian/'.$suratPenelitian->id_pengajuan) }}" class="btn-surat-penelitian-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratPenelitian">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : 'Data surat rekomendasi penelitian kosong.' }}
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
                                @if ($suratDataAwalList->count() > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nomor Surat</th>
                                                <th> Nama Mahasiswa</th>
                                                <th> Status</th>
                                                <th> Aksi</th>
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
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-permohonan-pengambilan-data-awal/'.$suratDataAwal->id_pengajuan) }}" class="btn-surat-data-awal-detail btn btn-outline-info btn-sm" data-toggle="modal" data-target="#suratDataAwal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat permohonan pengambilan data awal kosong.' }}
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
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
            <div class="modal-body" id='surat-dispensasi-detail-content'>
                
            </div>
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
            <div class="modal-body" id='surat-rekomendasi-detail-content'>
                
            </div>
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
            <div class="modal-body" id='surat-tugas-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pindahDetail" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-pindah-detail-content'>
            
            </div>
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
            <div class="modal-body" id='surat-pengantar-cuti-detail-content'>
                
            </div>
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
            <div class="modal-body" id='surat-pengantar-beasiswa-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="suratLulus" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-keterangan-lulus-detail-content'>
                
            </div>
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
            <div class="modal-body" id='surat-material-detail-content'>
                
            </div>
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
            <div class="modal-body" id='surat-penelitian-detail-content'>
                
            </div>
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
            <div class="modal-body" id='surat-survei-detail-content'>
                
            </div>
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
            <div class="modal-body" id='surat-data-awal-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection