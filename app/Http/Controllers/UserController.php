<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware('admin');
    }

    public function index()
    {
        $perPage = $this->perPage;
        $userList = User::paginate($perPage);
        $countUser = $userList->count();
        $countAllUser = $countUser;        
        return view('user.'.$this->segmentUser.'.user',compact('countUser','userList','countAllUser','perPage'));
    }

    public function create()
    {
        $formPassword = true;
        return view('user.'.$this->segmentUser.'.tambah_user',compact('formPassword'));
    }

    public function search(Request $request){
        $perPage = $this->perPage;
        $keyword = $request->all();
        if(isset($keyword['keyword'])){
            $nama = $keyword['keyword'] != null ? $keyword['keyword'] : '';
            $userList = User::where('nama','like','%'.$nama.'%')
                            ->paginate($perPage)
                            ->appends($request
                            ->except('page'));
            $countUser = count($userList);
            $countAllUser = User::all()->count();
            if($countUser < 1){
                $this->setFlashData('search','Hasil Pencarian','Data user tidak ditemukan!');
            }
            return view('user.'.$this->segmentUser.'.user',compact('countUser','userList','countAllUser','perPage'));
        }else{
            return redirect($this->segmentUser.'/user');
        }
    }

    public function store(UserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($request->password);
        $input['tanda_tangan'] = null;
        $this->setFlashData('success','Berhasil','Data user dengan nama '.strtolower($input['nama']).' berhasil ditambahkan');
        User::create($input);
        return redirect($this->segmentUser.'/user');
    }

    public function edit(User $user)
    {
        $formPassword = false;
        return view('user.'.$this->segmentUser.'.edit_user',compact('user','formPassword'));
    }

    public function update(UserRequest $request,User $user)
    {
        $input = $request->all();
        if(isset($input['password'])){
            $input['password'] = Hash::make($request->password);
        }else{
            $input['password'] = $user->password;
        }
        $input['tanda_tangan'] = null;
        $user->update($input);
        $this->setFlashData('success','Berhasil','Data user '.strtolower($user->nama).' berhasil diubah');
        return redirect($this->segmentUser.'/user');
    }

    public function destroy(User $user)
    {
        $user->delete();
        $this->setFlashData('success','Berhasil','Data user '.strtolower($user->nama).' berhasil dihapus');
        return redirect($this->segmentUser.'/user');
    }
}
