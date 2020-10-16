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
                                <h4 class="font-weight-normal mb-3">Verifikasi Surat Kegiatan<i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllVerifikasi > 0 ? $countAllVerifikasi.' Verifikasi Surat' : 'Verifikasi Surat Kosong' }}
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
                                    {{ $countAllSurat > 0 ? $countAllSurat.' Surat' : 'Data Surat Kosong' }}
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
                                        <h4>Verifikasi Surat Kegiatan Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllVerifikasi > 0)
                               <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables' width="100%">
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Verifikasi Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Verifikasi surat kegiatan mahasiswa belum ada.' }}
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
                                @if ($countAllSurat > 0)
                               <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables1' width="100%">
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
            </div>
            @include('layout.footer')
        </div>
    </div>
</div>
@endsection

@section('datatables-javascript')
    <script>
        let link = "{{ url('pegawai/surat-kegiatan-mahasiswa') }}";
        let linkMhs = "{{ url('pegawai/detail/mahasiswa') }}";

        $('#datatables').DataTable({
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
                            "targets": 5,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                <a href="${link+'/'+row.id}" class="dropdown-item">Detail</a>
                                                <a href="${linkSurat+'/pengajuan/disposisi/'+row.id}" class="dropdown-item btn-disposisi-detail" data-toggle="modal" data-target="#disposisi">Lihat Disposisi</a>
                                                <a href="${link+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                <form action="${link+'/verifikasi'}" method="post">
                                                    <input name="_method" type="hidden" value="PATCH">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <input name="id" type="hidden" value="${row.id}">
                                                    <button type="submit" class="dropdown-item btn-verification">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                            </div>
                                        </div>`;
                            },
                        },
                        {
                            "targets": 4,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        }
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pegawai/surat-kegiatan-mahasiswa/verifikasi/all') }}',
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
            pageLength: {{ $perPage }},
            order: [[ 1, 'desc' ]],
        });

        $('#datatables1').DataTable({
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
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                <a href="${link+'/'+row.id}" class="dropdown-item">Detail</a>
                                                <a href="${linkSurat+'/pengajuan/disposisi/'+row.id}" class="dropdown-item btn-disposisi-detail" data-toggle="modal" data-target="#disposisi">Lihat Disposisi</a>
                                                <a href="${link+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                            </div>
                                        </div>`;
                            },
                        },
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pegawai/surat-kegiatan-mahasiswa/all') }}',
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
            "pageLength": {{ $perPage }},
            "order": [[1,'desc']],
        });
    </script>
@endsection