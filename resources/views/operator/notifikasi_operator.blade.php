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
                            <i class="mdi mdi-bell-outline"></i>
                        </span> Notifikasi </h3>
                </div>
                <div class="row">
                    <div class="col-12 grid-margin">
                        <div class="card">
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-12 col-md-4">
                                        <h4>Notifikasi</h4>
                                    </div>
                                     <div class="col-12 col-md-8 text-right mt-4 mt-md-0 mt-lg-0">
                                        {{ Form::open(['url'=>'operator/notifikasi/allread','class'=>'d-inline-block btn-tambah']) }}
                                        <button type="submit" class="btn-sm btn btn-tambah mt-3 mt-md-0 mt-lg-0 btn-margin btn-outline-dark">
                                            <i class="mdi mdi-check btn-icon-prepend"></i>
                                            Tandai Semua Dilihat</button>
                                        {{ Form::close() }}

                                        {{ Form::open(['url'=>'operator/notifikasi/alldelete','class'=>'d-inline-block btn-tambah']) }}
                                        <button type="submit" class="sweet-delete btn-sm btn btn-danger btn-tambah mt-3 mt-md-0 mt-lg-0 btn-margin>
                                            <i class="mdi mdi-delete-forever btn-icon-prepend"></i>
                                            Hapus Semua Notifikasi</button>
                                        {{ Form::close() }}
                                     </div>
                                </div>
                                <hr class="mb-4">
                                @if ($countAllNotifikasi > 0)
                                <div class="table-responsive">
                                    <table class="table display warp" id='datatables' width="100%">
                                        <thead>
                                            <tr>
                                                <th data-priority="1"> Judul</th>
                                                <th> Isi</th>
                                                <th data-priority="2"> Status</th>
                                                <th> Tanggal Notifikasi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                @else
                                <div class="row">
                                    <div class="col text-center">
                                        <img src="{{ asset('image/no_notifikasi.svg')}}" class="illustration-no-data">
                                        <h4 class="display-4 mt-4">
                                            Notifikasi Kosong!
                                        </h4>
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
        let link = "{{ url('operator/notifikasi/') }}";

        $('#datatables').DataTable({
            responsive: true,
            columnDefs: [{
                            "targets": 0,
                            "data": "judul",
                            "render": function ( data, type, row, meta ) {
                                return `<a href="${link}/${row.id}" class="text-dark">
                                            <div class="mb-1">${row.judul_notifikasi}</div>
                                        </a>`;
                            }
                        },
                        {
                            "targets": 2,
                            "data": "status",
                            "render": function ( data, type, row, meta ) {
                                if(row.status == 'Belum Dilihat'){
                                    return `<label class="badge badge-gradient-info">${row.status}</label>`;
                                }
                                return `<label class="badge badge-gradient-success">${row.status}</label>`;
                            }
                        }
            ],
            autoWidth: false,
            language: bahasa,
            processing: true,
            serverSide: true,
            ajax: '{{ url('operator/notifikasi/all') }}',
            columns: [{
                    data: 'judul_notifikasi',
                },
                {
                    data: 'isi_notifikasi',
                },
                {
                    data: 'status',
                },
                {
                    data: 'tanggal_notifikasi',
                }
            ],
            order: [[ 2, "desc" ]],
            pageLength: {{ $perPage }}
        });
    </script>
@endsection