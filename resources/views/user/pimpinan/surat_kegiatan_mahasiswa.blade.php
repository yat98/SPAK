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
                            <i class="mdi mdi-file-document-box "></i>
                        </span> Surat Kegiatan Mahasiswa</h3>
                </div>
                <div class="row">
                    <div class="@if(Session::get('jabatan') == 'dekan') {{ 'col-md-4' }} @else {{ 'col-md-6' }} @endif stretch-card grid-margin">
                        <div class="card bg-gradient-warning card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Disposisi Surat Kegiatan <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllDisposisi > 0 ? $countAllDisposisi.' Disposisi Surat Kegiatan Mahasiswa' : 'Disposisi Surat Kegiatan Mahasiswa Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    @if(Session::get('jabatan') == 'dekan')
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-primary card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tanda Tangan Surat<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllTandaTangan > 0 ? $countAllTandaTangan.' Tanda Tangan Surat Kegiatan Mahasiswa' : 'Tanda Tangan Surat Kegiatan Mahasiswa Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="@if(Session::get('jabatan') == 'dekan') {{ 'col-md-4' }} @else {{ 'col-md-6' }} @endif stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Kegiatan Mahasiswa <i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
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
                                        <h4>Disposisi Surat Kegiatan</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countDisposisi > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Kegiatan</th>
                                                <th> Ormawa</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($disposisiList as $disposisi)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($disposisiList->currentPage() - 1)  }}</td>
                                                <td> {{ $disposisi->nama_kegiatan }}</td>
                                                <td> {{ $disposisi->mahasiswa->pimpinanOrmawa->ormawa->nama }}</td>
                                                <td>
                                                @if($disposisi->status == 'selesai')
                                                    <label class="badge badge-gradient-info">{{ ucwords($disposisi->status) }}</label>
                                                @elseif($disposisi->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($disposisi->status) }}</label>
                                                @elseif($disposisi->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($disposisi->status) }}</label>
                                                @else
                                                    <label class="badge badge-gradient-success">{{ ucwords($disposisi->status) }}</label>
                                                @endif
                                                <td> {{ $disposisi->created_at->diffForHumans() }}</td>
                                                <td> {{ $disposisi->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-kegiatan-mahasiswa/'.$disposisi->id) }}" class="btn btn-outline-info btn-sm">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                    @if($disposisi->status != 'diterima')
                                                    <a href="{{ url('pimpinan/surat-kegiatan-mahasiswa/'.$disposisi->id.'/disposisi') }}" class="btn btn-outline-info btn-sm btn-disposisi-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Lihat Disposisi
                                                    </a>
                                                    @endif
                                                    @if(($disposisi->status == 'diterima' && Session::get('jabatan') == 'dekan') || ($disposisi->status == 'disposisi dekan' && Session::get('jabatan') == 'wd1') || ($disposisi->status == 'disposisi wakil dekan I' && Session::get('jabatan') == 'wd2') || ($disposisi->status == 'disposisi wakil dekan II' && Session::get('jabatan') == 'wd3'))
                                                    {{ Form::open(['url'=>'pimpinan/surat-kegiatan-mahasiswa/disposisi','class'=>'d-inline-block']) }}
                                                    {{ Form::hidden('id',$disposisi->id)}}
                                                    {{ Form::hidden('catatan','-',['id'=>'catatan_disposisi']) }}
                                                    <button type="submit" class="btn btn-info btn-sm disposisi">
                                                        <i class="mdi mdi mdi-check btn-icon-prepend"></i>
                                                        Disposisi
                                                    </button>
                                                    {{ Form::close() }}
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $disposisiList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            Disposisi Surat Kegiatan Mahasiswa Kosong!
                                        </h4>
                                        <p class="text-muted">
                                            Disposisi surat kegiatan mahasiswa belum ada.
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if(Session::get('jabatan') == 'dekan')
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Tanda Tangan Surat Kegiatan</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countTandaTangan > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Nama Kegiatan</th>
                                                <th> Ormawa</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tandaTanganList as $tandaTangan)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($tandaTanganList->currentPage() - 1)  }}</td>
                                                <td> {{ $tandaTangan->nama_kegiatan }}</td>
                                                <td> {{ $tandaTangan->mahasiswa->pimpinanOrmawa->ormawa->nama }}</td>
                                                <td>
                                                @if($tandaTangan->status == 'selesai')
                                                    <label class="badge badge-gradient-info">{{ ucwords($tandaTangan->status) }}</label>
                                                @elseif($tandaTangan->status == 'ditolak')
                                                    <label class="badge badge-gradient-danger">{{ ucwords($tandaTangan->status) }}</label>
                                                @elseif($tandaTangan->status == 'diajukan')
                                                    <label class="badge badge-gradient-warning text-dark">{{ ucwords($tandaTangan->status) }}</label>
                                                @else
                                                    <label class="badge badge-gradient-success">{{ ucwords($tandaTangan->status) }}</label>
                                                @endif
                                                <td> {{ $tandaTangan->created_at->diffForHumans() }}</td>
                                                <td> {{ $tandaTangan->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('pimpinan/surat-kegiatan-mahasiswa/'.$tandaTangan->id) }}" class="btn btn-outline-info btn-sm">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Detail
                                                    </a>
                                                    @if($tandaTangan->status != 'diterima')
                                                    <a href="{{ url('pimpinan/surat-kegiatan-mahasiswa/'.$tandaTangan->id.'/disposisi') }}" class="btn btn-outline-info btn-sm btn-disposisi-detail" data-toggle="modal" data-target="#exampleModal">
                                                        <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                        Lihat Disposisi
                                                    </a>
                                                    @endif
                                                   
                                                    {{ Form::open(['url'=>'pimpinan/surat-kegiatan-mahasiswa/pengajuan/tanda-tangan','class'=>'d-inline-block']) }}
                                                    {{ Form::hidden('id',$tandaTangan->id)}}
                                                    <button type="submit" class="btn btn-info btn-sm simpan-tanda-tangan">
                                                        <i class="mdi mdi mdi-border-color btn-icon-prepend"></i>
                                                        Tanda Tangan
                                                    </button>
                                                    {{ Form::close() }}
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col">
                                        {{ $tandaTanganList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            Tanda Tangan Surat Kegiatan Mahasiswa Kosong!
                                        </h4>
                                        <p class="text-muted">
                                           Tanda tangan surat kegiatan mahasiswa belum ada.
                                        </p>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-12 grid-margin">
                    <div class="card">
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                    <h4>Data Surat Kegiatan Mahasiswa</h4>
                                </div>
                            </div>
                            <hr class="mb-4">
                            <div class="row mb-3">
                                <div class="col-sm-12">
                                    {{ Form::open(['url'=>'pimpinan/surat-kegiatan-mahasiswa/search','method'=>'get']) }}
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
                                                <a href="{{ url('pimpinan/surat-kegiatan-mahasiswa/'.$suratKegiatan->id_pengajuan_kegiatan) }}" class="btn btn-outline-info btn-sm">
                                                    <i class="mdi mdi-file-document-box btn-icon-prepend"></i>
                                                    Detail</a>
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
                                        {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kegiatan Mahasiswa Kosong!' }}
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
            @include('layout.footer')
        </div>
    </div>
</div>
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Disposisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='disposisi-detail-content'>
                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection