<?php

namespace App\Http\Controllers;

use DataTables;
use App\Operator;
use Illuminate\Http\Request;
use App\Http\Requests\OperatorRequest;

class OperatorController extends Controller
{
    public function  index(){
        $perPage = $this->perPage;
        $countAllOperator = Operator::count();
        return view('user.'.$this->segmentUser.'.operator',compact('countAllOperator','perPage'));
    }

    public function getAllOperator(){
        return DataTables::of(Operator::select(['*']))
                ->editColumn("bagian", function ($data) {
                    return ucwords($data->bagian);
                })        
                ->editColumn("status_aktif", function ($data) {
                    return ucwords($data->status_aktif);
                })
                ->addColumn('aksi', function ($data) {
                    return $data->id;
                })
                ->make(true);
    }

    public function getLimitOperator(){
        return DataTables::collection(Operator::all()->take(5)->sortByDesc('updated_at'))
                    ->editColumn("bagian", function ($data) {
                        return ucwords($data->bagian);
                    })        
                    ->editColumn("status_aktif", function ($data) {
                        return ucwords($data->status_aktif);
                    })
                    ->editColumn("created_at", function ($data) {
                        return $data->created_at->diffForHumans();
                    })
                    ->editColumn("updated_at", function ($data) {
                        return $data->updated_at->diffForHumans();
                    })
                    ->toJson();
    }

    public function create(){
        $formPassword = true;
        return view('user.'.$this->segmentUser.'.tambah_operator',compact('formPassword'));
    }

    public function store(OperatorRequest $request){
        $input = $request->all();
        $this->setFlashData('success','Berhasil','Data operator dengan nama '.strtolower($input['nama']).' berhasil ditambahkan');
        Operator::create($input);
        return redirect($this->segmentUser.'/operator');
    }

    public function show(Operator $operator){
        $data = collect($operator);
        $data->put('created_at',$operator->created_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('updated_at',$operator->updated_at->isoFormat('D MMMM Y H:m:ss'));
        $data->put('status_aktif',ucwords($operator->status_aktif));
        $data->put('bagian',ucwords($operator->bagian));

        return $data->toJson();
    }

    public function edit(Operator $operator){
        $formPassword = false;
        return view('user.'.$this->segmentUser.'.edit_operator',compact('operator','formPassword'));
    }

    public function update(OperatorRequest $request, Operator $operator){
        $input = $request->all();
        $operator->update($input);
        $this->setFlashData('success','Berhasil','Data operator '.strtolower($operator->nama).' berhasil diubah');
        return redirect($this->segmentUser.'/operator');
    }

    public function destroy(Operator $operator){
        $operator->delete();
        $this->setFlashData('success','Berhasil','Data operator '.$operator->nama.' berhasil dihapus');
        return redirect($this->segmentUser.'/operator');
    }
}
