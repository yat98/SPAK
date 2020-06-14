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
                            <i class="mdi mdi-calendar-text"></i>
                        </span> Tahun Akademik </h3>
                </div>
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
                                    {{ isset($tahunAkademikAktif) ? $tahunAkademikAktif->tahun_akademik.' - '.ucwords($tahunAkademikAktif->semester) : 'Tidak Ada Tahun Akademik Aktif' }}
                                </h2>
                                <h6 class="card-text">
                                </h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Data Tahun Akademik</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('admin/tahun-akademik/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Tahun Akademik</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                <div class="row mb-3">
                                    <div class="col-12">
                                        {{ Form::open(['url'=>'admin/tahun-akademik/search','method'=>'GET']) }}
                                        <div class="form-row">
                                            <div class="col-sm-12 col-md-3">
                                                {{ Form::select('tahun_akademik',$tahun,(request()->get('tahun_akademik') != null) ? request()->get('tahun_akademik'):null,['class'=>'btn-margin form-control','placeholder'=> '-- Pilih Tahun Akademik --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-3">
                                                {{ Form::select('semester',['genap'=>'Genap','ganjil'=>'Ganjil'],(request()->get('semester')!= null) ? request()->get('semester'):null,['class'=>'form-control','placeholder'=> '-- Pilih Semester --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md-3">
                                                {{ Form::select('status_aktif',['aktif'=>'Aktif','non aktif'=>'Non Aktif'],(request()->get('status_aktif') != null) ? request()->get('status_aktif'):null,['class'=>'form-control','placeholder'=> '-- Pilih Status Aktif --']) }}
                                            </div>
                                            <div class="col-sm-12 col-md">
                                                <button class="btn btn-success btn-tambah" type="submit">
                                                    <i class="mdi mdi-magnify btn-icon-prepend"></i>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                        {{ Form::close() }}
                                    </div>
                                </div>
                                @if ($countTahunAkademik > 0)
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th> No. </th>
                                                <th> Tahun Akademik</th>
                                                <th> Semester</th>
                                                <th> Status</th>
                                                <th> Di Buat</th>
                                                <th> Di Ubah</th>
                                                <th> Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($tahunAkademikList as $tahunAkademik)
                                            <tr>
                                                <td> {{ $loop->iteration + $perPage * ($tahunAkademikList->currentPage() - 1) }}</td>
                                                <td> {{ $tahunAkademik->tahun_akademik  }}</td>
                                                <td> {{ ucwords($tahunAkademik->semester)  }}</td>
                                                <td>
                                                    @if ($tahunAkademik->status_aktif == 'aktif')
                                                    <label
                                                        class="badge badge-gradient-info">{{ ucwords($tahunAkademik->status_aktif) }}</label>
                                                    @else
                                                    <label
                                                        class="badge badge-gradient-dark">{{ ucwords($tahunAkademik->status_aktif) }}</label>
                                                    @endif
                                                </td>
                                                <td>{{ $tahunAkademik->created_at->diffForHumans() }}</td>
                                                <td>{{ $tahunAkademik->updated_at->diffForHumans() }}</td>
                                                <td>
                                                    <a href="{{ url('admin/tahun-akademik/'.$tahunAkademik->id.'/edit') }}"
                                                        class="btn btn-warning btn-sm text-dark">
                                                        <i class="mdi mdi-tooltip-edit btn-icon-prepend"></i>
                                                        Edit
                                                    </a>
                                                    {{ Form::open(['method'=>'DELETE','action'=>['TahunAkademikController@destroy',$tahunAkademik->id],'class'=>'d-inline-block']) }}
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
                                        {{ $tahunAkademikList->links() }}
                                    </div>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_data.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-3">
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Data Tahun Akademik Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : 'Silahkan mengisi data tahun Akademik terlebih dahulu.' }}
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