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
                    @if(Auth::user()->jabatan == 'kabag tata usaha')   
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-warning card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Verifikasi Surat<i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllVerifikasi > 0 ? $countAllVerifikasi.' Verifikasi Surat' : 'Verifikasi Surat Kosong' }}
                                    </h2>
                                    <h6 class="card-text"></h6>
                                </div>
                            </div>
                        </div>     
                    @else      
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-warning card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Disposisi Surat<i
                                            class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllDisposisi > 0 ? $countAllDisposisi.' Disposisi Surat' : 'Disposisi Kosong' }}
                                    </h2>
                                    <h6 class="card-text"></h6>
                                </div>
                            </div>
                        </div>                               
                    @endif
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-orange card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Tanda Tangan Surat <i
                                        class="mdi mdi-file-document-box menu-icon mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllTandaTangan > 0 ? $countAllTandaTangan.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
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
                                    {{ $countAllSurat > 0 ? $countAllSurat.' Surat' : 'Data Surat Kosong' }}
                                </h2>
                                <h6 class="card-text"></h6>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    @if(Auth::user()->jabatan == 'kabag tata usaha')
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
                                                    <th data-priority="1"> Nomor Surat</th>
                                                    <th> Nama Kegiatan</th>
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
                    @else
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-12 col-md-6">
                                            <h4>Disposisi Surat Kegiatan Mahasiswa</h4>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllDisposisi > 0)
                                    <div class="table-responsive">
                                        <table class="table display no-warp" id='datatables1' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama Kegiatan</th>
                                                    <th> Ormawa</th>
                                                    <th> Status</th>
                                                    <th> Waktu Pengajuan</th>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Disposisi Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Disposisi surat kegiatan mahasiswa belum ada.' }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-6">
                                        <h4>Tanda Tangan Surat Kegiatan Mahasiswa</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllTandaTangan > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables2' width="100%">
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Tanda Tangan Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Tanda tangan surat kegiatan mahasiswa belum ada.' }}
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
                                    <table class="table display no-warp" id='datatables3' width="100%">
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
@endsection

@section('datatables-javascript')
    <script>
        let link = "{{ url('pimpinan/surat-kegiatan-mahasiswa') }}";
        @if(Auth::user()->jabatan == 'dekan')
            let jabatan = "{{ ucwords(Auth::user()->jabatan) }}";
        @else
            let jabatan = "{{ strtoupper(Auth::user()->jabatan) }}";
        @endif;

        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
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
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-kegiatan-mahasiswa/verifikasi/all') }}',
            columns: [{
                    data: 'surat_kegiatan_mahasiswa.nomor_surat',
                },
                {
                    data: 'nama_kegiatan',
                },
                {
                    data: 'ormawa.nama',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'status',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            pageLength: {{ $perPage }},
            order: [[ 4, 'desc' ]],
        });

        $('#datatables1').DataTable({
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
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let aksi = `<a href="${link+'/pengajuan/'+row.id}" class="dropdown-item">Detail</a>`;

                                if(row.status == 'Disposisi '+jabatan){
                                    aksi += `<a href="${link+'/disposisi/'+row.id}" class="dropdown-item">Disposisi</a>`;
                                }

                                if(row.status != 'Disposisi Dekan'){
                                    aksi += `<a href="${link+'/pengajuan/disposisi/'+row.id}" class="dropdown-item btn-disposisi-detail" data-toggle="modal" data-target="#exampleModal">Lihat Disposisi</a>`;
                                }

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${aksi}
                                            </div>
                                        </div>`;
                            },
                        },
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-kegiatan-mahasiswa/disposisi/all') }}',
            columns: [{
                    data: 'nama_kegiatan',
                },
                {
                    data: 'ormawa.nama',
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'status',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            pageLength: {{ $perPage }},
            order: [[ 3, 'desc' ]],
        });

        $('#datatables2').DataTable({
            responsive: true,
            columnDefs: [
                        {
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
                                                <a href="${link+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                <form action="${link+'/tanda-tangan'}" method="post">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <input name="id" type="hidden" value="${row.id}">
                                                    <button type="submit" class="dropdown-item simpan-tanda-tangan">
                                                        Tanda Tangan
                                                    </button>
                                                </form>
                                            </div>
                                        </div>`;
                            },
                        },
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-kegiatan-mahasiswa/tanda-tangan/all') }}',
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

        $('#datatables3').DataTable({
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
                                                <a href="${link+'/pengajuan/disposisi/'+row.id}" class="dropdown-item btn-disposisi-detail" data-toggle="modal" data-target="#disposisi">Lihat Disposisi</a>
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
            ajax: '{{ url('pimpinan/surat-kegiatan-mahasiswa/all') }}',
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
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'created_at',
                },
                {
                    data: 'nim',
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[1,'desc']],
        });
    </script>
@endsection