<?php

namespace App\Http\Controllers;

use App\Jurusan;
use Illuminate\Http\Request;
use App\Http\Requests\JurusanRequest;

class JurusanController extends Controller
{
    public function index()
    {
        $jurusanList = Jurusan::all();
        $countJurusan = $jurusanList->count();
        return view('user.admin.jurusan',compact('jurusanList','countJurusan'));
    }

    public function create()
    {
        return view('user.admin.tambah_jurusan');
    }

    public function store(JurusanRequest $request)
    {
        $input = $request->all();
        Jurusan::create($input);
        return redirect('admin/jurusan');
    }

    public function edit(Jurusan $jurusan)
    {   
        return view('user.admin.edit_jurusan',compact('jurusan'));
    }

    public function update(JurusanRequest $request, Jurusan $jurusan)
    {
        $jurusan->update($request->all());
        return redirect('admin/jurusan');
    }

    public function destroy(Jurusan $jurusan)
    {
        $jurusan->delete();
        return redirect('admin/jurusan');
    }
}
