@if (isset($suratKegiatan))
{{ Form::hidden('id_pengajuan_kegiatan',$suratKegiatan->id_pengajuan_kegiatan) }}
@else
@php
    $menimbang = "<ol type=\"a\">
                    <li>Bahwa untuk melancarkan pelaksaaan { NAMA KEGIATAN }, maka perlu dibentuk panitia;</li>
                    <li>Bahwa nama-nama yang tercantum dalam lampiran surat keputusan ini, merupakan panitia kegiatan { NAMA KEGIATAN } { ORMAWA } Jurusan { JURUSAN } yang dipandang mampu mengemban tugas dan memiliki tanggung jawab dalam kegiatan yang dimaksud;</li>
                    <li>Bahwa untuk kepentingan butir a dan b di atas, perlu penetapan Surat Keputusan Dekan Fakultas Teknik Universitas Negeri Gorontalo.</li>
                  <ol>";
    $mengingat = "<ol type=\"1\">
                        <li>Undang-undang Republik Indonesia Nomor: 20 Tahun 2003 tentang Sistem Pendidikan Nasional (Lembaran Negara Republik Indonesia Tahun 2003 Nomor 78, tambahan Lembaran Negara Republik Indonesia nomor 4301);</li>
                        <li>Undang-Undang Nomor 12 Tahun 2012 tentang Pendidikan Tinggi (Lembar Negara Tahun 2012 Nomor 158, Tambahan Lembaran Negara Nomor 5336);</li>
                        <li>Peraturan Menteri Riset, Teknologi dan Pendidikan Tinggi Republik Indonesia Nomor 44 Tahun 2015 tentang Standar Nasional Pendidikan Tinggi;</li>
                        <li>Keputusan Presiden Republik Indonesia Nomor 54 Tahun 2004 tentang perubahan IKIP Gorontalo menjadi Universitas Negeri Gorontalo;</li>
                        <li>Peraturan Menteri Riset, Teknologi, dan Pendidikan Tinggi Nomor:11 Tahun 2015 tentang Organisasi dan Tata Kerja (OTK) Universitas Negeri Gorontalo;</li>
                        <li>Peraturan Menteri Riset, Teknologi, dan Pendidikan Tinggi Nomor:82 Tahun 2017 tentang STATUTA Universitas Negeri Gorontalo;</li>
                        <li>Keputusan Menteri Riset, Teknologi dan Pendidikan Tinggi Republik Indonesia Nomor : 32029/M/KP/2019 tentang Pengangkatan Rektor Universitas Negeri Gorontalo periode Tahun 2019-2023</li>
                        <li>Keputusan Rektor Universitas Negeri Gorontalo Nomor: 775/UN47/KP/2019 tanggal 22 November 2019 tentang Pengangkatan Dekan Fakultas Teknik Universitas Negeri Gorontalo Periode Tahun 2019-2023</li>
                  </ol>";
    $memperhatikan = "Surat Permohonan dari { ORMAWA } Jurusan { JURUSAN } Fakultas Teknik Universitas Negeri Gorontalo, Nomor:{ NOMOR SURAT PERMOHONAN } tanggal { TANGGAL SURAT }";
    $menetapkan = "KEPUTUSAN DEKAN FAKULTAS TEKNIK UNIVERSITAS NEGERI GORONTALO TENTANG PANITIA KEGIATAN { NAMA KEGIATAN } JURUSAN { NAMA JURUSAN }";
    $kesatu = "Mengesahkan panitia kegiatan { NAMA KEGIATAN }  { ORMAWA } Jurusan { JURUSAN } Fakultas Teknik Universitas Negeri Gorontalo sebagaimana terlampir;";
    $kedua = "panitia kegiatan { NAMA KEGIATAN } bertugas mengkoordinir/melaksanakan kegiatan, pertanggungjawaban kegiatan dan keuangan diakhir kegiatan";
    $ketiga = "Biaya yang timbul akibat pelaksanaan surat keputusan ini dibebankan pada mata anggaran yang tersedia;";
    $keempat = "Keputusan Dekan ini berlaku mulai tanggal ditetapkan dengan ketentuan apabila terdapat kekeliruan dalam keputusan ini akan ditinjau dan diperbaiki kembali sebagaimana mestinya.";
@endphp
@endif
<div class="row">
    <div class="col-md-10">
        <div class="form-group">
            {{ Form::label('nomor_surat','Nomor Surat') }}
            <div class="form-row">
                <div class="col-md-4 col-sm-3 mt-1">
                @if ($errors->any())
                @if ($errors->has('nomor_surat'))
                {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_surat']) }}
                <div class="text-danger-red mt-1"><small>{{ $errors->first('nomor_surat') }}</small></div>
                @else
                {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg is-valid','id'=>'nomor_surat']) }}
                @endif
                @else
                {{ Form::text('nomor_surat',(isset($nomorSuratBaru)) ? $nomorSuratBaru : null ,['class'=>'form-control form-control-lg','id'=>'nomor_surat']) }}
                @endif
                </div>
                <div class="col-md col-sm-3 mt-1">
                 @if ($errors->any())
                @if ($errors->has('id_kode_surat'))
                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_surat','readonly'=>'readonly']) }}
                <div class="text-danger-red mt-1"><small>{{ $errors->first('id_kode_surat') }}</small></div>
                @else
                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg is-valid','id'=>'nomor_surat','readonly'=>'readonly']) }}
                @endif
                @else
                {{ Form::select('id_kode_surat',$kodeSurat,null,['class'=>'form-control form-control-lg','id'=>'nomor_surat','readonly'=>'readonly']) }}
                @endif
                </div>
                <div class="col-md-3 col-sm-3 mt-1">
                {{ Form::text('tahun',isset($pengajuanKegiatan)?$pengajuanKegiatan->created_at->format('Y'):date('Y') ,['class'=>'form-control form-control-lg','disabled'=>'disabled']) }}
                </div>
            </div>    
        </div>
         <div class="form-group">
            {{ Form::label('ormawa','Ormawa') }}
            @if ($errors->any())
            @if ($errors->has('ormawa'))
            {{ Form::select('ormawa',$ormawaList,isset($suratKegiatan)?$suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->id_ormawa:null,['class'=>'form-control form-control-lg is-invalid ormawa-list','id'=>'nip','placeholder'=> '-- Pilih Ormawa --']) }}
            <div class="invalid-feedback">{{ $errors->first('ormawa') }}</div>
            @else
            {{ Form::select('ormawa',$ormawaList,isset($suratKegiatan)?$suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->id_ormawa:null,['class'=>'form-control form-control-lg is-valid ormawa-list','id'=>'nip','placeholder'=> '-- Pilih Ormawa --']) }}
            @endif
            @else
            {{ Form::select('ormawa',$ormawaList,isset($suratKegiatan)?$suratKegiatan->pengajuanSuratKegiatanMahasiswa->mahasiswa->pimpinanOrmawa->id_ormawa:null,['class'=>'form-control form-control-lg ormawa-list','id'=>'nip','placeholder'=> '-- Pilih Ormawa --']) }}
            @endif
        </div>
         <div class="form-group">
            {{ Form::label('nomor_surat_permohonan_kegiatan','Nomor Surat Permohonan Kegiatan') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nomor_surat_permohonan_kegiatan'))
            {{ Form::text('nomor_surat_permohonan_kegiatan',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nomor_surat_permohonan_kegiatan']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nomor_surat_permohonan_kegiatan') }}</small></div>
            @else
            {{ Form::text('nomor_surat_permohonan_kegiatan',null,['class'=>'form-control form-control-lg','id'=>'nomor_surat_permohonan_kegiatan']) }}
            @endif
            @else
            {{ Form::text('nomor_surat_permohonan_kegiatan',null,['class'=>'form-control form-control-lg','id'=>'nomor_surat_permohonan_kegiatan']) }}
            @endif
        </div>    
         <div class="form-group">
            {{ Form::label('nama_kegiatan','Nama Kegiatan') }}
            <br>
            @if ($errors->any())
            @if ($errors->has('nama_kegiatan'))
            {{ Form::text('nama_kegiatan',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nama_kegiatan']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nama_kegiatan') }}</small></div>
            @else
            {{ Form::text('nama_kegiatan',null,['class'=>'form-control form-control-lg','id'=>'nama_kegiatan']) }}
            @endif
            @else
            {{ Form::text('nama_kegiatan',null,['class'=>'form-control form-control-lg','id'=>'nama_kegiatan']) }}
            @endif
        </div>
        <div class="form-group">
            @if(isset($suratKegiatan))
                <div class="form-row">
                    <div class="col-md-8 col-12">
                        {{ Form::label('file_surat_permohonan_kegiatan','File Surat Permohonan Kegiatan *(Ukuran File < 1MB)',['class'=>'mt-2']) }}
                    </div>
                    <div class="col-md-4 col-12 text-right">
                        <a href="{{ asset('upload_surat_permohonan_kegiatan/'.$suratKegiatan->pengajuanSuratKegiatanMahasiswa->file_surat_permohonan_kegiatan) }}"  data-lightbox="{{ explode('.',$suratKegiatan->pengajuanSuratKegiatanMahasiswa->file_surat_permohonan_kegiatan)[0] }}" class="btn btn-sm btn-info ml-4">Lihat File</a>
                        <a href="" class="btn btn-sm btn-warning btn-ubah-file-pindah">Ubah File</a>    
                    </div>
                </div>
                {{ Form::file('file_surat_permohonan_kegiatan',['class'=>'d-none file-upload-default','id'=>'file_surat_permohonan_kegiatan']) }}
                <div class="input-group col-xs-12 d-none">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Permohonan Surat Kegiatan Mahasiswa','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @else
                {{ Form::label('file_surat_permohonan_kegiatan','File Surat Permohonan Kegiatan Mahasiswa *(Ukuran File < 1MB)') }}
                {{ Form::file('file_surat_permohonan_kegiatan',['class'=>'file-upload-default','id'=>'file_surat_permohonan_kegiatan']) }}           
                <div class="input-group col-xs-12">
                    {{ Form::text('',null,['class'=>'form-control file-upload-info','placeholder'=>'Upload File Permohonan Surat Kegiatan Mahasiswa','disabled'=>'disabled']) }}
                    <span class="input-group-append">
                        <button class="file-upload-browse btn btn-gradient-success"
                            type="button">Upload</button>
                    </span>
                </div>
            @endif
            @if ($errors->any())
            <div class="text-danger-red mt-1"><small>{{ $errors->first('file_surat_permohonan_kegiatan') }}</small></div>
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('menimbang','Menimbang') }}
            @if ($errors->any())
            @if ($errors->has('menimbang'))
            {{ Form::textarea('menimbang',isset($suratKegiatan) ? $suratKegiatan->menimbang :$menimbang,['class'=>'replace form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'20']) }}
            <div class="invalid-feedback">{{ $errors->first('menimbang') }}</div>
            @else
            {{ Form::textarea('menimbang',isset($suratKegiatan) ? $suratKegiatan->menimbang :$menimbang,['class'=>'replace form-control form-control-lg ','id'=>'froala-editor','rows'=>'20']) }}
            @endif
            @else
            {{ Form::textarea('menimbang',isset($suratKegiatan) ? $suratKegiatan->menimbang :$menimbang,['class'=>'replace form-control form-control-lg','id'=>'froala-editor','rows'=>'20']) }}
            @endif
        </div>
         <div class="form-group">
            {{ Form::label('mengingat','Mengingat') }}
            @if ($errors->any())
            @if ($errors->has('mengingat'))
            {{ Form::textarea('mengingat',isset($suratKegiatan) ? $suratKegiatan->mengingat :$mengingat,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'15']) }}
            <div class="invalid-feedback">{{ $errors->first('mengingat') }}</div>
            @else
            {{ Form::textarea('mengingat',isset($suratKegiatan) ? $suratKegiatan->mengingat :$mengingat,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'15']) }}
            @endif
            @else
            {{ Form::textarea('mengingat',isset($suratKegiatan) ? $suratKegiatan->mengingat :$mengingat,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'15']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('memperhatikan','Memperhatikan') }}
            @if ($errors->any())
            @if ($errors->has('memperhatikan'))
            {{ Form::textarea('memperhatikan',isset($suratKegiatan) ? $suratKegiatan->memperhatikan :$memperhatikan,['class'=>'replace form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'15']) }}
            <div class="invalid-feedback">{{ $errors->first('memperhatikan') }}</div>
            @else
            {{ Form::textarea('memperhatikan',isset($suratKegiatan) ? $suratKegiatan->memperhatikan :$memperhatikan,['class'=>'replace form-control form-control-lg ','id'=>'froala-editor','rows'=>'15']) }}
            @endif
            @else
            {{ Form::textarea('memperhatikan',isset($suratKegiatan) ? $suratKegiatan->memperhatikan :$memperhatikan,['class'=>'replace form-control form-control-lg','id'=>'froala-editor','rows'=>'15']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('menetapkan','Menetapkan') }}
            @if ($errors->any())
            @if ($errors->has('menetapkan'))
            {{ Form::textarea('menetapkan',isset($suratKegiatan) ? $suratKegiatan->menetapkan :$menetapkan,['class'=>'menetapkan-string replace form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'15']) }}
            <div class="invalid-feedback">{{ $errors->first('menetapkan') }}</div>
            @else
            {{ Form::textarea('menetapkan',isset($suratKegiatan) ? $suratKegiatan->menetapkan :$menetapkan,['class'=>'menetapkan-string replace form-control form-control-lg ','id'=>'froala-editor','rows'=>'15']) }}
            @endif
            @else
            {{ Form::textarea('menetapkan',isset($suratKegiatan) ? $suratKegiatan->menetapkan :$menetapkan,['class'=>'menetapkan-string replace form-control form-control-lg','id'=>'froala-editor','rows'=>'15']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('kesatu','Kesatu') }}
            @if ($errors->any())
            @if ($errors->has('kesatu'))
            {{ Form::textarea('kesatu',isset($suratKegiatan) ? $suratKegiatan->kesatu :$kesatu,['class'=>'replace form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'5']) }}
            <div class="invalid-feedback">{{ $errors->first('kesatu') }}</div>
            @else
            {{ Form::textarea('kesatu',isset($suratKegiatan) ? $suratKegiatan->kesatu :$kesatu,['class'=>'replace form-control form-control-lg ','id'=>'froala-editor','rows'=>'5']) }}
            @endif
            @else
            {{ Form::textarea('kesatu',isset($suratKegiatan) ? $suratKegiatan->kesatu :$kesatu,['class'=>'replace form-control form-control-lg','id'=>'froala-editor','rows'=>'5']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('kedua','Kedua') }}
            @if ($errors->any())
            @if ($errors->has('kedua'))
            {{ Form::textarea('kedua',isset($suratKegiatan) ? $suratKegiatan->kedua :$kedua,['class'=>'replace form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'5']) }}
            <div class="invalid-feedback">{{ $errors->first('kedua') }}</div>
            @else
            {{ Form::textarea('kedua',isset($suratKegiatan) ? $suratKegiatan->kedua :$kedua,['class'=>'replace form-control form-control-lg ','id'=>'froala-editor','rows'=>'5']) }}
            @endif
            @else
            {{ Form::textarea('kedua',isset($suratKegiatan) ? $suratKegiatan->kedua :$kedua,['class'=>'replace form-control form-control-lg','id'=>'froala-editor','rows'=>'5']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('ketiga','Ketiga') }}
            @if ($errors->any())
            @if ($errors->has('ketiga'))
            {{ Form::textarea('ketiga',isset($suratKegiatan) ? $suratKegiatan->ketiga :$ketiga,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'5']) }}
            <div class="invalid-feedback">{{ $errors->first('ketiga') }}</div>
            @else
            {{ Form::textarea('ketiga',isset($suratKegiatan) ? $suratKegiatan->ketiga :$ketiga,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'5']) }}
            @endif
            @else
            {{ Form::textarea('ketiga',isset($suratKegiatan) ? $suratKegiatan->ketiga :$ketiga,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'5']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('keempat','Keempat') }}
            @if ($errors->any())
            @if ($errors->has('keempat'))
            {{ Form::textarea('keempat',isset($suratKegiatan) ? $suratKegiatan->keempat :$keempat,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'10']) }}
            <div class="invalid-feedback">{{ $errors->first('keempat') }}</div>
            @else
            {{ Form::textarea('keempat',isset($suratKegiatan) ? $suratKegiatan->keempat :$keempat,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'10']) }}
            @endif
            @else
            {{ Form::textarea('keempat',isset($suratKegiatan) ? $suratKegiatan->keempat :$keempat,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'10']) }}
            @endif
        </div>
         <div class="form-group">
            {{ Form::label('nip','Tanda Tangan') }}
            @if ($errors->any())
            @if ($errors->has('nip'))
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nip','readonly'=> 'readonly']) }}
            <div class="invalid-feedback">{{ $errors->first('nip') }}</div>
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-valid','id'=>'nip','readonly'=> 'readonly']) }}
            @endif
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg','id'=>'nip','readonly'=> 'readonly']) }}
            @endif
        </div>
         <div class="form-group">
            {{ Form::label('lampiran_panitia','Lampiran Panitia') }}
            @if ($errors->any())
            @if ($errors->has('lampiran_panitia'))
            {{ Form::textarea('lampiran_panitia',null,['class'=>'form-control form-control-lg is-invalid','id'=>'lampiran_panitia','rows'=>'100']) }}
            <div class="invalid-feedback">{{ $errors->first('lampiran_panitia') }}</div>
            @else
            {{ Form::textarea('lampiran_panitia',null,['class'=>'form-control form-control-lg ','id'=>'lampiran_panitia','rows'=>'100']) }}
            @endif
            @else
            {{ Form::textarea('lampiran_panitia',null,['class'=>'form-control form-control-lg','id'=>'lampiran_panitia','rows'=>'100']) }}
            @endif
        </div> 
        <div class="form-group">
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>