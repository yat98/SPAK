<div class="row">
    <div class="col-md-10">
        <div class="form-group">
            {{ Form::label('nim','NIM') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nim'))
            {{ Form::text('nim',Session::get('nim'),['class'=>'form-control form-control-lg is-invalid','id'=>'nim','readonly'=>'readonly']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::text('nim',Session::get('nim'),['class'=>'form-control form-control-lg','id'=>'nim','readonly'=>'readonly']) }}
            @endif
            @else
            {{ Form::text('nim',Session::get('nim'),['class'=>'form-control form-control-lg','id'=>'nim','readonly'=>'readonly']) }}
            @endif
        </div>  
        <div class="form-group">
            {{ Form::label('nama','Nama') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nama'))
            {{ Form::text('nama',Session::get('username'),['class'=>'form-control form-control-lg is-invalid','id'=>'nama','disabled'=>'disabled']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nim') }}</small></div>
            @else
            {{ Form::text('nama',Session::get('username'),['class'=>'form-control form-control-lg','id'=>'nama','disabled'=>'disabled']) }}
            @endif
            @else
            {{ Form::text('nama',Session::get('username'),['class'=>'form-control form-control-lg','id'=>'nama','disabled'=>'disabled']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('nama_kampus','Nama Kampus') }}
            @if ($errors->any())
            @if ($errors->has('nama_kampus'))
            {{ Form::text('nama_kampus',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nama_kampus']) }}
            <div class="invalid-feedback">{{ $errors->first('nama_kampus') }}</div>
            @else
            {{ Form::text('nama_kampus',null,['class'=>'form-control form-control-lg is-valid','id'=>'nama_kampus']) }}
            @endif
            @else
            {{ Form::text('nama_kampus',null,['class'=>'form-control form-control-lg','id'=>'nama_kampus']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('strata','Strata') }}
            @if ($errors->any())
            @if ($errors->has('strata'))
            {{ Form::select('strata',['D3'=>'D3','S1'=>'S1','S2'=>'S2','S3'=>'S3'],null,['class'=>'form-control form-control-lg is-invalid','id'=>'strata','placeholder'=> '-- Pilih Strata --']) }}
            <div class="invalid-feedback">{{ $errors->first('strata') }}</div>
            @else
            {{ Form::select('strata',['D3'=>'D3','S1'=>'S1','S2'=>'S2','S3'=>'S3'],null,['class'=>'form-control form-control-lg is-valid','id'=>'strata','placeholder'=> '-- Pilih Strata --']) }}
            @endif
            @else
            {{ Form::select('strata',['D3'=>'D3','S1'=>'S1','S2'=>'S2','S3'=>'S3'],null,['class'=>'form-control form-control-lg','id'=>'strata','placeholder'=> '-- Pilih Strata --']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('nama_prodi','Nama Program Studi') }}
            @if ($errors->any())
            @if ($errors->has('nama_prodi'))
            {{ Form::text('nama_prodi',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nama_prodi']) }}
            <div class="invalid-feedback">{{ $errors->first('nama_prodi') }}</div>
            @else
            {{ Form::text('nama_prodi',null,['class'=>'form-control form-control-lg is-valid','id'=>'nama_prodi']) }}
            @endif
            @else
            {{ Form::text('nama_prodi',null,['class'=>'form-control form-control-lg','id'=>'nama_prodi']) }}
            @endif
        </div>
        <div class="form-group">
            @if(isset($pengajuanSuratPersetujuanPindah))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_surat_keterangan_lulus_butuh','File Surat Keterangan Lulus Butuh *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_persetujuan_pindah/'.$pengajuanSuratPersetujuanPindah->file_surat_keterangan_lulus_butuh) }}"  data-lightbox="{{ explode('.',$pengajuanSuratPersetujuanPindah->file_surat_keterangan_lulus_butuh)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_keterangan_lulus_butuh',['class'=>'file-upload-default','id'=>'file_surat_keterangan_lulus_butuh']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Lulus Butuh','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_keterangan_lulus_butuh','File Surat Keterangan Lulus Butuh *(Ukuran File < 1MB)') }}
                {{ Form::file('file_surat_keterangan_lulus_butuh',['class'=>'file-upload-default','id'=>'file_surat_keterangan_lulus_butuh']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Lulus Butuh','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_keterangan_lulus_butuh') }}</small></div>
            @endif
        </div>  
        <div class="form-group">
            @if(isset($pengajuanSuratPersetujuanPindah))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                       {{ Form::label('file_ijazah_terakhir','File Ijazah Terakhir *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_persetujuan_pindah/'.$pengajuanSuratPersetujuanPindah->file_ijazah_terakhir) }}"  data-lightbox="{{ explode('.',$pengajuanSuratPersetujuanPindah->file_ijazah_terakhir)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_ijazah_terakhir',['class'=>'file-upload-default','id'=>'file_ijazah_terakhir']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Ijazah Terakhir','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_ijazah_terakhir','File Ijazah Terakhir *(Ukuran File < 1MB)') }}
                {{ Form::file('file_ijazah_terakhir',['class'=>'file-upload-default','id'=>'file_ijazah_terakhir']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Ijazah Terakhir','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_ijazah_terakhir') }}</small></div>
            @endif
        </div>  
        <div class="form-group">
            @if(isset($pengajuanSuratPersetujuanPindah))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                       {{ Form::label('file_surat_rekomendasi_jurusan','File Surat Rekomendasi Jurusan *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_persetujuan_pindah/'.$pengajuanSuratPersetujuanPindah->file_surat_rekomendasi_jurusan) }}"  data-lightbox="{{ explode('.',$pengajuanSuratPersetujuanPindah->file_surat_rekomendasi_jurusan)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_rekomendasi_jurusan',['class'=>'d-none file-upload-default','id'=>'file_surat_rekomendasi_jurusan']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Rekomendasi Jurusan','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_rekomendasi_jurusan','File Surat Rekomendasi Jurusan *(Ukuran File < 1MB)') }}
                {{ Form::file('file_surat_rekomendasi_jurusan',['class'=>'file-upload-default','id'=>'file_surat_rekomendasi_jurusan']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Rekomendasi Jurusan','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_rekomendasi_jurusan') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            @if(isset($pengajuanSuratPersetujuanPindah))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_surat_keterangan_bebas_perlengkapan_universitas','File Surat Keterangan Bebas Perlengkapan Universitas *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_persetujuan_pindah/'.$pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_universitas) }}"  data-lightbox="{{ explode('.',$pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_universitas)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_keterangan_bebas_perlengkapan_universitas',['class'=>'file-upload-default','id'=>'file_surat_keterangan_bebas_perlengkapan_universitas']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Bebas Perlengkapan Universitas','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_keterangan_bebas_perlengkapan_universitas','File Surat Keterangan Bebas Perlengkapan Universitas *(Ukuran File < 1MB)') }}
                {{ Form::file('file_surat_keterangan_bebas_perlengkapan_universitas',['class'=>'file-upload-default','id'=>'file_surat_keterangan_bebas_perlengkapan_universitas']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Bebas Perlengkapan Universitas','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_keterangan_bebas_perlengkapan_universitas') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            @if(isset($pengajuanSuratPersetujuanPindah))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_surat_keterangan_bebas_perlengkapan_fakultas','File Surat Keterangan Bebas Perlengkapan Fakultas *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_persetujuan_pindah/'.$pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_fakultas) }}"  data-lightbox="{{ explode('.',$pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perlengkapan_fakultas)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_keterangan_bebas_perlengkapan_fakultas',['class'=>'d-none file-upload-default','id'=>'file_surat_keterangan_bebas_perlengkapan_fakultas']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Bebas Perlengkapan Fakultas','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_keterangan_bebas_perlengkapan_fakultas','File Surat Keterangan Bebas Perlengkapan Fakultas *(Ukuran File < 1MB)') }}
                {{ Form::file('file_surat_keterangan_bebas_perlengkapan_fakultas',['class'=>'file-upload-default','id'=>'file_surat_keterangan_bebas_perlengkapan_fakultas']) }}           
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Bebas Perlengkapan Fakultas','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_keterangan_bebas_perpustakaan_fakultas') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            @if(isset($pengajuanSuratPersetujuanPindah))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_surat_keterangan_bebas_perpustakaan_universitas','File Surat Keterangan Bebas Perpustakaan Universitas *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_persetujuan_pindah/'.$pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_universitas) }}"  data-lightbox="{{ explode('.',$pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_universitas)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_keterangan_bebas_perpustakaan_universitas',['class'=>'d-none file-upload-default','id'=>'file_surat_keterangan_bebas_perpustakaan_universitas']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Bebas Perpustakaan Universitas','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_keterangan_bebas_perpustakaan_universitas','File Surat Keterangan Bebas Perpustakaan Universitas *(Ukuran File < 1MB)') }}
                {{ Form::file('file_surat_keterangan_bebas_perpustakaan_universitas',['class'=>'file-upload-default','id'=>'file_surat_keterangan_bebas_perpustakaan_universitas']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Bebas Perpustakaan Universitas','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_keterangan_bebas_perpustakaan_universitas') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            @if(isset($pengajuanSuratPersetujuanPindah))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_surat_keterangan_bebas_perpustakaan_fakultas','File Surat Keterangan Bebas Perpustakaan Fakultas *(Ukuran File < 1MB)') }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_persetujuan_pindah/'.$pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_fakultas) }}"  data-lightbox="{{ explode('.',$pengajuanSuratPersetujuanPindah->file_surat_keterangan_bebas_perpustakaan_fakultas)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_keterangan_bebas_perpustakaan_fakultas',['class'=>'file-upload-default','id'=>'file_surat_keterangan_bebas_perpustakaan_fakultas']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Bebas Perpustakaan Fakultas','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_keterangan_bebas_perpustakaan_fakultas','File Surat Keterangan Bebas Perpustakaan Fakultas *(Ukuran File < 1MB)') }}
                {{ Form::file('file_surat_keterangan_bebas_perpustakaan_fakultas',['class'=>'file-upload-default','id'=>'file_surat_keterangan_bebas_perpustakaan_fakultas']) }}
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Surat Keterangan Bebas Perpustakaan Fakultas','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>    
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_keterangan_bebas_perpustakaan_fakultas') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>