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
                            <i class="mdi mdi-file-document-box"></i>
                        </span> Surat Kegiatan Mahasiswa </h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Pengajuan Surat Kegiatan Mahasiswa<i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllPengajuanKegiatan > 0 ? $countAllPengajuanKegiatan.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
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
                                <h4 class="font-weight-normal mb-3">Surat Kegiatan Mahasiswa <i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllSuratKegiatan > 0 ? $countAllSuratKegiatan.' Surat Kegiatan Mahasiswa' : 'Surat Kegiatan Mahasiswa Kosong' }}
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
                                        <h4>Pengajuan Surat Kegiatan Mahasiswa</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('pegawai/surat-kegiatan-mahasiswa/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Pengajuan Surat Kegiatan Mahasiswa</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countPengajuanKegiatan > 0)
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
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pengajuanKegiatanList as $pengajuanKegiatan)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($pengajuanKegiatanList->currentPage() - 1)  }}</td>
                                                <td> {{ $pengajuanKegiatan->nama_kegiatan }}</td>
                                                <td> {{ $pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->nama }}</td>
                                                <td>
                                                 @if($pengajuanKegiatan->status == 'selesai')
                                                    <label class="badge badge-gradient-info">{{ ucwords($pengajuanKegiatan->status) }}</td></label>
                                                @elseif($pengajuanKegiatan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($pengajuanKegiatan->status) }}</td></label>
                                                @elseif($pengajuanKegiatan->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($pengajuanKegiatan->status) }}</td></label>
                                                @else
                                                    <label class="badge badge-gradient-success">{{ ucwords($pengajuanKegiatan->status) }}</td></label>
                                                @endif 
                                                <td> {{ $pengajuanKegiatan->keterangan }}</td>
                                                <td> {{ $pengajuanKegiatan->created_at->diffForHumans() }}</td>
                                                <td> {{ $pengajuanKegiatan->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('pegawai/surat-kegiatan-mahasiswa/pengajuan/'.$pengajuanKegiatan->id) }}" class="btn btn-outline-info btn-sm">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>

                                                    @if($pengajuanKegiatan->status == 'diajukan')
                                                    {{ Form::open(['method'=>'PATCH','action'=>['PengajuanSuratKegiatanMahasiswaController@terima',$pengajuanKegiatan->id],'class'=>'d-inline-block']) }}
                                                    <button type="submit" class="btn btn-info btn-sm btn-terima-kegiatan">
                                                        <i class="mdi mdi mdi-check btn-icon-prepend"></i>
                                                        Terima
                                                    </button>
                                                    {{ Form::close() }}

                                                    {{ Form::open(['method'=>'PATCH','action'=>['PengajuanSuratKegiatanMahasiswaController@tolak',$pengajuanKegiatan->id],'class'=>'d-inline-block']) }}
                                                    {{ Form::hidden('keterangan','-',['id'=>'keterangan_surat']) }}
                                                    <button type="submit" class="btn btn-danger btn-sm tolak-surat">
                                                        <i class="mdi mdi mdi-close btn-icon-prepend"></i>
                                                        Tolak
                                                    </button>
                                                    {{ Form::close() }}
                                                     @endif

                                                     @if($pengajuanKegiatan->status == 'disposisi wakil dekan III')
                                                     <a href="{{ url('pegawai/surat-kegiatan-mahasiswa/pengajuan/'.$pengajuanKegiatan->id.'/create') }}" class="btn btn-info btn-sm">
                                                        <i class="mdi mdi-plus btn-icon-prepend"></i>
                                                        Buat Surat
                                                    </a>
                                                     @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $pengajuanKegiatanList->appends(['page' => $suratKegiatanList->currentPage()])->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            Pengajuan Surat Kosong!
                                        </h4>
                                        <p class="text-muted">
                                            Pengajuan surat kegiatan mahasiswa belum ada.
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Surat Kegiatan Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-sm-12">
                                        {{ Form::open(['url'=>'pegawai/surat-kegiatan-mahasiswa/search','method'=>'get']) }}
                                        <div class="form-row">
                                            <div class="col-sm-4 col-md-4 mt-1">
                                                {{ Form::select('keywords',$nomorSurat,(request()->get('keywords') != null) ? request()->get('keywords'):null,['class'=>'form-control search','placeholder'=> 'Cari kode surat...']) }}
                                            </div>
                                            <div class="col-sm-12 col-md">
                                                <button class="btn btn-success btn-sm btn-tambah" type="submit">
                                                    <i class="mdi mdi-magnify btn-icon-prepend"></i>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                                @if ($countSuratKegiatan > 0)
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
                                                <td> {{ $loop->iteration + $perPage * ($suratKegiatanList->currentPage() - 1) }}</td>
                                                <td> {{ $suratKegiatan->nomor_surat.'/'.$suratKegiatan->kodeSurat->kode_surat.'/'.$suratKegiatan->created_at->year }}</td>
                                                 <td> {{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->nama_kegiatan }}</td>
                                                <td> {{ $suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->ormawa->nama }}</td>
                                                <td> 
                                                    <label class="badge badge-gradient-info">
                                                        {{ ucwords($suratKegiatan->status) }}
                                                    </label>
                                                </td>
                                                <td>
                                                    <a href="{{ url('pegawai/surat-kegiatan-mahasiswa/'.$suratKegiatan->id_pengajuan_kegiatan) }}" class="btn btn-outline-info btn-sm">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail</a>
                                                    <a href="{{ url('pegawai/surat-kegiatan-mahasiswa/'.$suratKegiatan->id_pengajuan_kegiatan.'/cetak') }}" class="btn btn-info btn-sm" target="_blank">
                                                        <i class="mdi mdi mdi-printer btn-icon-prepend"></i>
                                                        Cetak</a>
                                                    <a href="{{ url('pegawai/surat-kegiatan-mahasiswa/'.$suratKegiatan->id_pengajuan_kegiatan.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['SuratKegiatanMahasiswaController@destroy',$suratKegiatan->id_pengajuan_kegiatan],'class'=>'d-inline-block']) }}
                                                    <button type="submit" class="btn btn-danger btn-sm sweet-delete">
                                                        <i class="mdi mdi-delete-forever btn-icon-prepend"></i>
                                                        Hapus
                                                    </button>
                                                    {{ Form::close() }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $suratKegiatanList->links() }}
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat kegiatan mahasiswa terlebih dahulu.' }}
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
@endsection