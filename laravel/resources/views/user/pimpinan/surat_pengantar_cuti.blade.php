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
                        </span> Surat Pengantar Cuti </h3>
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
                    @endif
                    <div class="@if(Auth::user()->jabatan == 'kabag tata usaha') col-md-4 @else col-md-6 @endif stretch-card grid-margin">
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
                    <div class="@if(Auth::user()->jabatan == 'kabag tata usaha') col-md-4 @else col-md-6 @endif stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Pengantar Cuti <i
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
                                            <h4>Verifikasi Surat Pengantar Cuti</h4>
                                        </div>
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllVerifikasi > 0)
                                    <div class="table-responsive">
                                        <table class="table display no-warp" id='datatables' width="100%">
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Verifikasi Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Verifikasi surat pengantar cuti belum ada.' }}
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
                                        <h4>Tanda Tangan Surat Pengantar Cuti</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllTandaTangan > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables1' width="100%">
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Tanda Tangan Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Tanda tangan surat pengantar cuti belum ada.' }}
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
                                        <h4>Surat Pengantar Cuti</h4>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllSurat > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables2' width="100%">
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Data Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat pengantar cuti belum ada.' }}
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
@endsection

@section('datatables-javascript')
    <script>
        let link = "{{ url('pimpinan/surat-pengantar-cuti') }}";
        let linkMhs = "{{ url('pimpinan/detail/mahasiswa') }}";

        $('#datatables').DataTable({
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
                                                <a href="${link+'/'+row.id}" class="dropdown-item btn-surat-pengantar-cuti-detail" data-toggle="modal" data-target="#exampleModal">Detail</a>
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
            ajax: '{{ url('pimpinan/surat-pengantar-cuti/verifikasi/all') }}',
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
            pageLength: {{ $perPage }},
            order: [[ 0, 'desc' ]],
        });

        $('#datatables1').DataTable({
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
                                                <a href="${link+'/'+row.id}" class="dropdown-item btn-surat-pengantar-cuti-detail" data-toggle="modal" data-target="#exampleModal">
                                                    Detail</a>
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
                        {
                            "targets": [4],
                            "visible": false,
                        },
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('pimpinan/surat-pengantar-cuti/tanda-tangan/all') }}',
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
            pageLength: {{ $perPage }},
            order: [[ 0, 'desc' ]],
        });

        $('#datatables2').DataTable({
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
                                                <a href="${link+'/'+row.id}" class="dropdown-item btn-surat-pengantar-cuti-detail" data-toggle="modal" data-target="#exampleModal">
                                                    Detail</a>
                                                <a href="${link+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                            </div>
                                        </div>`;
                            },
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
            ajax: '{{ url('pimpinan/surat-pengantar-cuti/all') }}',
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
            "pageLength": {{ $perPage }},
            "order": [[0,'desc']],
        });
    </script>
@endsection