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
                        </span> Surat Dispensasi</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
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

                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-info card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Surat Dispensasi<i
                                        class="mdi mdi-file-document-box mdi-24px float-right"></i>
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
                                        <h4>Pengajuan Surat Dispensasi</h4>
                                    </div>
                                    @if(Auth::user()->bagian == 'front office')
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('operator/pengajuan/surat-dispensasi/create')}}"
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
                                            {{ (Session::has('search')) ? Session::get('search') : ' Pengajuan surat dispensasi belum ada.' }}
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
                                @if ($countAllSurat > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables1' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Nama Kegiatan</th>
                                                <th> Nomor Surat</th>
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
                                            @if(Auth::user()->bagian == 'front office')
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data surat dispensasi belum ada.' }}
                                            @else
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data surat dispensasi terlebih dahulu.' }}
                                            @endif
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

@if(Auth::user()->bagian == 'front office')
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
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

<div class="modal fade" id="mahasiswa" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content bg-white">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id='mahasiswa-detail-content'></div>
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
            <div class="modal-body" id='surat-dispensasi-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('datatables-javascript')
    <script>
        let link = "{{ url('operator/pengajuan/surat-dispensasi') }}";
        let linkPengajuan = "{{ url('operator/surat-dispensasi/pengajuan') }}";
        let linkSurat = "{{ url('operator/surat-dispensasi') }}";
        let linkMhs = "{{ url('operator/detail/mahasiswa') }}";
        let linkOperator = "{{ url('operator/surat-dispensasi') }}";

        <?php if(Auth::user()->bagian == 'front office') { ?>
            const order = [[ 2, 'desc' ]];
            const aksi = {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                return `<div class="d-inline-block">
                                            <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                            </a>
                                            <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                <a href="${link+'/'+row.id_surat_masuk}/progress" class="dropdown-item btn-surat-progress" data-toggle="modal" data-target="#exampleModal">Progres Surat</a>

                                                <a href="${link+'/'+row.id_surat_masuk}" class="dropdown-item btn-pengajuan-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>

                                                <a href="${link+'/'+row.id_surat_masuk}/edit" class="dropdown-item">Edit</a>
                                                <form action="${link+'/'+row.id_surat_masuk}" method="post">
                                                    <input name="_method" type="hidden" value="DELETE">
                                                    <input name="_token" type="hidden" value="{{ @csrf_token() }}">
                                                    <button type="submit" class="dropdown-item sweet-delete">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>`;
                            },
                        };
        <?php } else { ?>
            const order = [[2 , 'asc']];
            const aksi = {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                let aksi = '';
                                if(row.status == 'Diajukan'){
                                    aksi = `<a href="${link+'/'+row.id_surat_masuk}" class="dropdown-item btn-pengajuan-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>
                                            <a href="${linkPengajuan+'/create/'+row.id_surat_masuk}" class="dropdown-item">Buat Surat</a>`;
                                }else{
                                    aksi = `<a href="${link+'/'+row.id_surat_masuk}" class="dropdown-item btn-pengajuan-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>`;
                                }

                                return `<div class="d-inline-block">
                                                <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                    <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                </a>
                                                <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                    ${aksi}
                                                </div>
                                            </div>`;
                            }
                        };
        <?php } ?>

        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 1,
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
                            "targets": 2,
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        aksi,
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('operator/pengajuan/surat-dispensasi/all') }}',
            columns: [{
                    data: 'nama_kegiatan',
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
                            "targets": 1,
                            "data": "surat_dispensasi.nomor_surat",
                            "render": function ( data, type, row, meta ) {
                                return `${row.surat_dispensasi.nomor_surat}/${row.surat_dispensasi.kode_surat.kode_surat}`;
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
                            "data": "created_at",
                            "render": function ( data, type, row, meta ) {
                                return row.waktu_pengajuan;
                            }
                        },
                        {
                            "targets": 4,
                            "data": "aksi",
                            "render": function ( data, type, row, meta ) {
                                <?php if(Auth::user()->bagian != 'front office'){ ?>
                                    return `<div class="d-inline-block">
                                                <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                    <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                </a>
                                                <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                    <a href="${linkOperator+'/'+row.id_surat_masuk}" class="dropdown-item btn-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>
                                                    <a href="${link+'/'+row.id_surat_masuk+'/cetak'}" class="dropdown-item">Cetak</a>
                                                </div>
                                            </div>`;
                                <?php }else{ ?>
                                    let action = `<a href="${linkOperator+'/'+row.id_surat_masuk}" class="dropdown-item btn-surat-dispensasi-detail" data-toggle="modal" data-target="#suratDispensasi">Detail</a>`;

                                    if(row.status == 'Selesai'){
                                        action += `<a href="${link+'/'+row.id_surat_masuk+'/cetak'}" class="dropdown-item">Cetak</a>`;
                                    }
                                    
                                    return `<div class="d-inline-block">
                                                <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                                    <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                                </a>
                                                <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
                                                    ${action}
                                                </div>
                                            </div>`;
                                <?php } ?>
                            },
                        },
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('operator/surat-dispensasi/all') }}',
            columns: [{
                    data: 'nama_kegiatan',
                },
                {
                    data: 'surat_dispensasi.nomor_surat',
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