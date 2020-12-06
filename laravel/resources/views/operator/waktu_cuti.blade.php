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
                            <i class="mdi mdi-clock "></i>
                        </span> Waktu Cuti</h3>
                </div>
                <div class="row">
                    <div class="col-md-6 stretch-card grid-margin">
                        <div class="card bg-gradient-success card-img-holder text-white">
                            <div class="card-body">
                                <img src="{{ asset('image/circle.svg') }}" class="card-img-absolute"
                                    alt="circle-image" />
                                <h4 class="font-weight-normal mb-3">Waktu Cuti <i
                                        class="mdi mdi-clock mdi-24px float-right"></i>
                                </h4>
                                <h2 class="mb-5">
                                    {{ $countAllWaktuCuti > 0 ? $countAllWaktuCuti.' Waktu Cuti' : 'Data Waktu Cuti Kosong' }}
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
                                        <h4>Waktu Cuti</h4>
                                    </div>
                                    <div class="col-12 col-md-6 text-right">
                                        <a href="{{ url('operator/waktu-cuti/create')}}"
                                            class="btn-sm btn btn-info btn-tambah mt-4 mt-md-0 mt-lg-0">
                                            <i class="mdi mdi mdi-plus btn-icon-prepend"></i>
                                            Tambah Waktu Cuti</a>
                                    </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllWaktuCuti > 0)
                                <div class="table-responsive">
                                    <table class="table display no-warp" id='datatables' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Tahun Akademik</th>
                                                <th> Tanggal Awal Cuti</th>
                                                <th> Tanggal Akhir Cuti</th>
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
                                            {{ (Session::has('search-title')) ? Session::get('search-title') : ' Waktu Cuti Kosong!' }}
                                        </h4>
                                        <p class="text-muted">
                                            {{ (Session::has('search')) ? Session::get('search') : ' Data waktu cuti belum ada.' }}
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
        let link = "{{ url('operator/waktu-cuti') }}";

        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "tahun_akademik.tahun_akademik",
                            "render": function ( data, type, row, meta ) {
                                return `${row.tahun_akademik.tahun_akademik} - ${row.semester}`;
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
            ajax: '{{ url('operator/waktu-cuti/all') }}',
            columns: [{
                    data: 'tahun_akademik.tahun_akademik',
                },
                {
                    data: 'tanggal_awal_cuti',
                },
                {
                    data: 'tanggal_akhir_cuti',
                },
                {
                    data: 'aksi', name: 'aksi', orderable: false, searchable: false
                },
                {
                    data: 'tahun_akademik.semester',
                },
            ],
            "pageLength": {{ $perPage }},
            "order": [[ 0, 'desc' ]],
        });
    </script>
@endsection