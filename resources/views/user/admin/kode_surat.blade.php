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
                            <i class="mdi mdi-format-list-numbered"></i>
                        </span> Kode Surat </h3>
                </div>
                <div class="row">
                    <div class="col-md-4 stretch-card grid-margin">
                        <div class="card bg-gradient-orange card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Kode Surat <i
                                        class="mdi mdi-format-list-numbered mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllKodeSurat > 0 ? $countAllKodeSurat.' Kode Surat' : 'Kode Surat Kosong' }}
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
                                        <h4>Kode Surat</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('admin/kode-surat/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">+
                                            Tambah Kode Surat</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllKodeSurat > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Kode Surat</th>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : 'Kode Surat Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Silahkan mengisi data kode surat terlebih dahulu.' }}
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
            <div class="modal-body" id='kode-surat-detail-content'></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('datatables-javascript')
<script>
    let link = "{{ url('admin/kode-surat/') }}";

    $('#datatables').DataTable({
        responsive: true,
        columnDefs: [{
                        "targets": 1,
                        "data": "status_aktif",
                        "render": function ( data, type, row, meta ) {
                            if(data == 'Aktif'){
                                return '<label class="badge badge-gradient-info">'+data+'</label>';
                            }else{
                                return '<label class="badge badge-gradient-dark">'+data+'</label>';
                            }
                        }
                    },
                    {
                        "targets": 0,
                        "data": "kode_surat",
                        "render": function ( data, type, row, meta ) {
                            return `<a href="${link}/${row.id}" class="kode-surat-detail text-dark" data-toggle="modal" data-target="#exampleModal">
                                        <div class="mb-1">${row.kode_surat}</div>
                                    </a>`;
                        }
                    },
                    {
                        "targets": 2,
                        "data": "aksi",
                        "render": function ( data, type, row, meta ) {
                            return `<div class="d-inline-block">
                                        <a href="#" class="nav-link" id="aksi" data-toggle="dropdown" aria-expanded="true">    
                                            <i class="mdi mdi mdi-arrow-down-drop-circle mdi-24px text-dark"></i>
                                        </a>
                                        <div class="dropdown-menu navbar-dropdown border border-dark" aria-labelledby="aksi">
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
                    },
        }],
        autoWidth: false,
        language: bahasa,
        processing: true,
        serverSide: true,
        ajax: '{{ url('admin/kode-surat/all') }}',
        columns: [{
                data: 'kode_surat',
            },
            {
                data: 'status_aktif',
            },
            {
                data: 'aksi', name: 'aksi', orderable: false, searchable: false
            },
        ],
        "pageLength": {{ $perPage }}
    });
</script>
@endsection