@if (isset($pengajuanKegiatan))
{{ Form::hidden('id_pengajuan_kegiatan',$pengajuanKegiatan->id) }}
@php
    $menimbang = "<ol type=\"a\">
                    <li>Bahwa untuk melancarkan pelaksaaan $pengajuanKegiatan->nama_kegiatan, maka perlu dibentuk panitia;</li>
                    <li>Bahwa nama-nama yang tercantum dalam lampiran surat keputusan ini, merupakan panitia kegiatan ".$pengajuanKegiatan->nama_kegiatan." ".$pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->nama." Jurusan ".$pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->jurusan->nama_jurusan." yang dipandang mampu mengemban tugas dan memiliki tanggung jawab dalam kegiatan yang dimaksud;</li>
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
    $memperhatikan = "Surat Permohonan dari ".$pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->nama." Jurusan ".$pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->jurusan->nama_jurusan." Fakultas Teknik Universitas Negeri Gorontalo, Nomor:".$pengajuanKegiatan->nomor_surat_permohonan_kegiatan." tanggal ".$pengajuanKegiatan->created_at->isoFormat('D MMMM Y');
    $menetapkan = "KEPUTUSAN DEKAN FAKULTAS TEKNIK UNIVERSITAS NEGERI GORONTALO TENTANG PANITIA KEGIATAN ".strtoupper($pengajuanKegiatan->nama_kegiatan)." JURUSAN ".strtoupper($pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->jurusan->nama_jurusan);
    $kesatu = "Mengesahkan panitia kegiatan ".$pengajuanKegiatan->nama_kegiatan."  ".$pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->nama." Jurusan ".$pengajuanKegiatan->mahasiswa->pimpinanOrmawa->ormawa->jurusan->nama_jurusan." Fakultas Teknik Universitas Negeri Gorontalo sebagaimana terlampir;";
    $kedua = "panitia kegiatan ".$pengajuanKegiatan->nama_kegiatan." bertugas mengkoordinir/melaksanakan kegiatan, pertanggungjawaban kegiatan dan keuangan diakhir kegiatan";
    $ketiga = "Biaya yang timbul akibat pelaksanaan surat keputusan ini dibebankan pada mata anggaran yang tersedia;";
    $keempat = "Keputusan Dekan ini berlaku mulai tanggal ditetapkan dengan ketentuan apabila terdapat kekeliruan dalam keputusan ini akan ditinjau dan diperbaiki kembali sebagaimana mestinya.";
@endphp
@endif
<div class="row">
    <div class="col-md-12">
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
            {{ Form::label('menimbang','Menimbang') }}
            @if ($errors->any())
            @if ($errors->has('menimbang'))
            {{ Form::textarea('menimbang',$menimbang,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'20']) }}
            <div class="invalid-feedback">{{ $errors->first('menimbang') }}</div>
            @else
            {{ Form::textarea('menimbang',$menimbang,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'20']) }}
            @endif
            @else
            {{ Form::textarea('menimbang',$menimbang,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'20']) }}
            @endif
        </div>
         <div class="form-group">
            {{ Form::label('mengingat','Mengingat') }}
            @if ($errors->any())
            @if ($errors->has('mengingat'))
            {{ Form::textarea('mengingat',$mengingat,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'15']) }}
            <div class="invalid-feedback">{{ $errors->first('mengingat') }}</div>
            @else
            {{ Form::textarea('mengingat',$mengingat,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'15']) }}
            @endif
            @else
            {{ Form::textarea('mengingat',$mengingat,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'15']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('memperhatikan','Memperhatikan') }}
            @if ($errors->any())
            @if ($errors->has('memperhatikan'))
            {{ Form::textarea('memperhatikan',$memperhatikan,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'15']) }}
            <div class="invalid-feedback">{{ $errors->first('memperhatikan') }}</div>
            @else
            {{ Form::textarea('memperhatikan',$memperhatikan,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'15']) }}
            @endif
            @else
            {{ Form::textarea('memperhatikan',$memperhatikan,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'15']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('menetapkan','Menetapkan') }}
            @if ($errors->any())
            @if ($errors->has('menetapkan'))
            {{ Form::textarea('menetapkan',$menetapkan,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'15']) }}
            <div class="invalid-feedback">{{ $errors->first('menetapkan') }}</div>
            @else
            {{ Form::textarea('menetapkan',$menetapkan,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'15']) }}
            @endif
            @else
            {{ Form::textarea('menetapkan',$menetapkan,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'15']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('kesatu','Kesatu') }}
            @if ($errors->any())
            @if ($errors->has('kesatu'))
            {{ Form::textarea('kesatu',$kesatu,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'5']) }}
            <div class="invalid-feedback">{{ $errors->first('kesatu') }}</div>
            @else
            {{ Form::textarea('kesatu',$kesatu,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'5']) }}
            @endif
            @else
            {{ Form::textarea('kesatu',$kesatu,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'5']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('kedua','Kedua') }}
            @if ($errors->any())
            @if ($errors->has('kedua'))
            {{ Form::textarea('kedua',$kedua,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'5']) }}
            <div class="invalid-feedback">{{ $errors->first('kedua') }}</div>
            @else
            {{ Form::textarea('kedua',$kedua,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'5']) }}
            @endif
            @else
            {{ Form::textarea('kedua',$kedua,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'5']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('ketiga','Ketiga') }}
            @if ($errors->any())
            @if ($errors->has('ketiga'))
            {{ Form::textarea('ketiga',$ketiga,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'5']) }}
            <div class="invalid-feedback">{{ $errors->first('ketiga') }}</div>
            @else
            {{ Form::textarea('ketiga',$ketiga,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'5']) }}
            @endif
            @else
            {{ Form::textarea('ketiga',$ketiga,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'5']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('keempat','Keempat') }}
            @if ($errors->any())
            @if ($errors->has('keempat'))
            {{ Form::textarea('keempat',$keempat,['class'=>'form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'10']) }}
            <div class="invalid-feedback">{{ $errors->first('keempat') }}</div>
            @else
            {{ Form::textarea('keempat',$keempat,['class'=>'form-control form-control-lg ','id'=>'froala-editor','rows'=>'10']) }}
            @endif
            @else
            {{ Form::textarea('keempat',$keempat,['class'=>'form-control form-control-lg','id'=>'froala-editor','rows'=>'10']) }}
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
            {{ Form::submit($buttonLabel,['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>