@if (isset($pengajuanSurat))
{{ Form::hidden('id_pengajuan',$pengajuanSurat->id) }}
@endif
@php
    $kewajiban = "
                <p>Mahasiswa yang namanya tersebut diatas dinyatakan telah melunasi kewajibannya berupa:</p>
                <ol>
                    <li>Mengembalikan buku peminjaman</li>
                    <li>Memberikan partisipasi uang tunai pengganti sumbangan buku senilai 50.000.-(Lima Puluh Ribu Rupiah)</li>
                  <ol>";
@endphp
{{ Form::hidden('id_operator',Auth::user()->id) }}
<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            {{ Form::label('nomor_surat','Nomor Surat') }}
            <div class="form-row">
                <div class="col">
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
                <div class="col">
                @if ($errors->any())
                @if ($errors->has('kode_surat'))
                {{ Form::text('kode_surat',null,['class'=>'form-control form-control-lg is-invalid','id'=>'kode_surat','placeholder'=>'Kode Surat']) }}
                <div class="text-danger-red mt-1"><small>{{ $errors->first('kode_surat') }}</small></div>
                @else
                {{ Form::text('kode_surat',null,['class'=>'form-control form-control-lg is-valid','id'=>'kode_surat','placeholder'=>'Kode Surat']) }}
                @endif
                @else
                {{ Form::text('kode_surat',null,['class'=>'form-control form-control-lg','id'=>'kode_surat','placeholder'=>'Kode Surat']) }}
                @endif
                </div>
            </div>    
        </div> 
        <div class="form-group">
            {{ Form::label('nokta','No. KTA') }}
            @if ($errors->any())
            @if ($errors->has('nokta'))
            {{ Form::text('nokta',null,['class'=>'form-control form-control-lg is-invalid','id'=>'nokta']) }}
            <div class="text-danger-red mt-1"><small>{{ $errors->first('nokta') }}</small></div>
            @else
            {{ Form::text('nokta',null,['class'=>'form-control form-control-lg is-valid','id'=>'nokta']) }}
            @endif
            @else
            {{ Form::text('nokta',null,['class'=>'form-control form-control-lg','id'=>'nokta']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('kewajiban','Kewajiban') }}
            @if ($errors->any())
            @if ($errors->has('kewajiban'))
            {{ Form::textarea('kewajiban',isset($suratPerpustakaan) ? $suratPerpustakaan->kewajiban :$kewajiban,['class'=>'replace form-control form-control-lg is-invalid','id'=>'froala-editor','rows'=>'15']) }}
            <div class="invalid-feedback">{{ $errors->first('kewajiban') }}</div>
            @else
            {{ Form::textarea('kewajiban',isset($suratPerpustakaan) ? $suratPerpustakaan->kewajiban :$kewajiban,['class'=>'replace form-control form-control-lg ','id'=>'froala-editor','rows'=>'15']) }}
            @endif
            @else
            {{ Form::textarea('kewajiban',isset($suratPerpustakaan) ? $suratPerpustakaan->kewajiban :$kewajiban,['class'=>'replace form-control form-control-lg','id'=>'froala-editor','rows'=>'15']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::label('nip','Tanda Tangan') }}
            @if ($errors->any())
            @if ($errors->has('nip'))
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-invalid','id'=>'nip']) }}
            <div class="invalid-feedback">{{ $errors->first('nip') }}</div>
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg is-valid','id'=>'nip']) }}
            @endif
            @else
            {{ Form::select('nip',$userList,null,['class'=>'form-control form-control-lg','id'=>'nip']) }}
            @endif
        </div>
        <div class="form-group">
            {{ Form::submit('Tambah',['class'=>'btn btn-info btn-sm font-weight-medium auth-form-btn']) }}
            <input type="reset" value="Reset" class="btn btn-danger btn-sm">
        </div>
    </div>
</div>