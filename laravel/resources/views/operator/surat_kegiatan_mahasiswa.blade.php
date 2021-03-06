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
                        </span> Surat Kegiatan Mahasiswa</h3>
                </div>
                <div class="row">
                    @if(Auth::user()->bagian != 'sespri dekan')
                        <div class="@if(Auth::user()->bagian == 'front office') col-md-6 @else col-md-4 @endif stretch-card grid-margin">
                            <div class="card bg-gradient-warning card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Pengajuan Surat<i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllPengajuan > 0 ? $countAllPengajuan.' Pengajuan Surat' : 'Pengajuan Surat Kosong' }}
                                    </h2>
                                    <h6 class="card-text"></h6>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(Auth::user()->bagian != 'front office')
                        <div class="col-md-4 stretch-card grid-margin">
                            <div class="card bg-gradient-orange card-img-holder text-white">
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
                    @if(Auth::user()->bagian != 'sespri dekan')
                        <div class="@if(Auth::user()->bagian == 'front office') col-md-6 @else col-md-4 @endif stretch-card grid-margin">
                            <div class="card bg-gradient-info card-img-holder text-white">
                                <div class="card-body">
                                    <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                        alt="circle-image" />
                                    <h4 class="font-weight-normal mb-3">Surat Kegiatan Mahasiswa<i
                                            class="mdi mdi-file-document-box mdi-24px float-right"></i>
                                    </h4>
                                    <h2 class="mb-5">
                                        {{ $countAllSurat > 0 ? $countAllSurat.' Surat' : 'Data Surat Kosong' }}
                                    </h2>
                                    <h6 class="card-text"></h6>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                @if(Auth::user()->bagian != 'sespri dekan')
                    <div class="row">
                        <div class="col-12 grid-margin">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row mb-3">
                                        <div class="col-12 col-md-6">
                                            <h4>Pengajuan Surat Kegiatan Mahasiswa</h4>
                                        </div>
                                        @if(Auth::user()->bagian == 'front office')
                                        <div class="col-12 col-md-6 text-right">
                                            <a href="{{ url('operator/surat-kegiatan-mahasiswa/pengajuan/create')}}"
                                                class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">
                                                <i class="mdi mdi mdi-plus btn-icon-prepend"></i>
                                                Tambah Pengajuan</a>
                                        </div>
                                        @endif
                                    </div>
                                    <hr class="mb-4">
                                    @if ($countAllPengajuan > 0)
                                    <div class="table-responsive">
                                        <table class="table display no-warp" id='datatables' width="100%">
                                            <thead>
                                                <tr>
                                                    <th data-priority="1"> Nama Kegiatan</th>
                                                    <th> Ormawa</th>
                                                    <th> Status</th>
                                                    <th> Waktu Pengajuan</th>
                                                    <th> Keterangan</th>
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
                                                {{ (Session::has('search-title')) ? Session::get('search-title') : ' Pengajuan Surat Kosong!' }}
                                            </h4>
                                            <p class="text-muted">
                                                {{ (Session::has('search')) ? Session::get('search') : ' Pengajuan surat kegiatan mahasiswa belum ada.' }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(Auth::user()->bagian != 'front office')
                    <div class="row">
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
                                                    <th> Keterangan</th>
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
                                                {{ (Session::has('search')) ? Session::get('search') : ' Disposisi pengajuan surat kegiatan mahasiswa belum ada.' }}
                                            </p>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if(Auth::user()->bagian != 'sespri dekan')
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
                                    @if ($countAllSurat > 0)
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
                @endif     
            </div>
            @include('layout.footer')
        </div>
    </div>
</div>

@if(Auth::user()->bagian == 'front office')
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Progress Surat</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='surat-progress-content'></div>
        </div>
    </div>
</div>
@endif

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

<div class="modal fade" id="disposisiPengajuan" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Disposisi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='disposisi-pengajuan-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('datatables-javascript')
    <script>
        let link = "{{ url('operator/surat-kegiatan-mahasiswa/pengajuan') }}";
        let linkSurat = "{{ url('operator/surat-kegiatan-mahasiswa') }}";
        let linkMhs = "{{ url('operator/detail/mahasiswa') }}";

         @if(Auth::user()->bagian == 'front office')
            const order = [[ 3, 'desc' ]];
        @else
            const order = [[2 , 'asc'],[ 3, 'desc' ]];
        @endif

        $('#datatables').DataTable({
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
                            "targets": 5,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                 @if(Auth::user()->bagian == 'front office')
                                    return `<div class="d-inline-block">
                                                <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                    <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                </a>
                                                <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                    <a href="${link+'/'+row.id}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>
                                                    <a href="${link+'/'+row.id}" class="dropdown-item">Detail</a>
                                                    <a href="${link+'/'+row.id}/edit" class="dropdown-item">Edit</a>
                                                    <form action="${link+'/'+row.id}" method="post">
                                                        <input name="_method" type="hidden" value="DELETE">
                                                        <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                        <button type="submit" class="dropdown-item sweet-delete">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>`;           
                                @else
                                    let aksi = '';
                                    if(row.status == 'Diajukan'){
                                        aksi = `<a href="${link+'/'+row.id}" class="dropdown-item">Detail</a>
                                                 <form action="${link+'/verifikasi/'+row.id}" method="post">
                                                    <input name="_method" type="hidden" value="PATCH">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <input name="id" type="hidden" value="${row.id}">
                                                    <button type="submit" class="dropdown-item btn-verification">
                                                        Verifikasi
                                                    </button>
                                                </form>
                                                <form action="${link+'/tolak-pengajuan/'+row.id}" method="post">
                                                    <input name="_method" type="hidden" value="PATCH">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <input name="keterangan" type="hidden" value="-" id="keterangan_surat">
                                                    <button type="submit" class="dropdown-item tolak-surat">
                                                        Tolak Pengajuan
                                                    </button>
                                                </form>`;
                                    }else{
                                        aksi = `<a href="${link+'/'+row.id}" class="dropdown-item">Detail</a>`;
                                    }
                                    
                                    return `<div class="d-inline-block">
                                                    <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                        <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                    </a>
                                                    <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                        ${aksi}
                                                    </div>
                                                </div>`;
                                                
                                @endif
                            }
                        },
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('operator/surat-kegiatan-mahasiswa/pengajuan/all') }}',
            columns: [{
                    data: 'nama_kegiatan',
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
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            "pageLength": {{ $perPage }},
            "order": order,
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
                            "targets": 5,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                @if(Auth::user()->bagian == 'subbagian kemahasiswaan')
                                    let aksi = `<a href="${link+'/'+row.id}" class="dropdown-item">Detail</a>`;
                                    if(row.status != 'Disposisi'){
                                        aksi += `<a href="${linkSurat+'/disposisi/'+row.id+'/cetak'}" class="dropdown-item">Cetak Disposisi</a>
                                                <a href="${linkSurat+'/disposisi/'+row.id}" class="dropdown-item btn-disposisi-pengajuan-detail" data-toggle="modal" data-target="#disposisiPengajuan">Detail Disposisi</a>`;
                                    }
                                    if(row.status == 'Disposisi Selesai'){
                                        aksi += `<a href="${linkSurat+'/create/'+row.id}" class="dropdown-item">Buat Surat</a>`;
                                    }
                                @elseif(Auth::user()->bagian == 'sespri dekan')
                                    let aksi = `<a href="${link+'/'+row.id}" class="dropdown-item">Detail</a>`;
                                    if(row.status == 'Disposisi'){
                                        aksi += `<a href="${linkSurat+'/disposisi/create/'+row.id}" class="dropdown-item">Buat Disposisi</a>`;
                                    }else{
                                        aksi += `<a href="${linkSurat+'/disposisi/'+row.id+'/cetak'}" class="dropdown-item">Cetak Disposisi</a>
                                                <a href="${linkSurat+'/disposisi/'+row.id}" class="dropdown-item btn-disposisi-pengajuan-detail" data-toggle="modal" data-target="#disposisiPengajuan">Detail Disposisi</a>`;
                                    }
                                @endif

                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                ${aksi}
                                            </div>
                                        </div>`;           
                            }
                        },
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('operator/surat-kegiatan-mahasiswa/disposisi/all') }}',
            columns: [{
                    data: 'nama_kegiatan',
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
                    data: 'keterangan',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
            ],
            "pageLength": {{ $perPage }},
            "order": order,
        });

        $('#datatables2').DataTable({
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
                            @if(Auth::user()->bagian != 'front office')
                                    return `<div class="d-inline-block">
                                                <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                    <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                </a>
                                                <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                    <a href="${linkSurat+'/'+row.id}" class="dropdown-item">Detail</a>
                                                    <a href="${linkSurat+'/pengajuan/disposisi/'+row.id}" class="dropdown-item btn-disposisi-detail" data-toggle="modal" data-target="#disposisi">Lihat Disposisi</a>
                                                    <a href="${linkSurat+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>
                                                    <a href="${linkSurat+'/disposisi/'+row.id+'/cetak'}" class="dropdown-item">Cetak Disposisi</a>
                                                </div>
                                            </div>`;
                                @else
                                    let action = `<a href="${linkSurat+'/'+row.id}" class="dropdown-item">Detail</a>`;

                                    if(row.status == 'Selesai'){
                                        action += `<a href="${linkSurat+'/'+row.id+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                    }
                                    
                                    return `<div class="d-inline-block">
                                                <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                    <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                </a>
                                                <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                    ${action}
                                                </div>
                                            </div>`;
                                @endif
                            },
                        },
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('operator/surat-kegiatan-mahasiswa/all') }}',
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