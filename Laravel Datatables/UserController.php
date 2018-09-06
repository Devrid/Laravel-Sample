<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Datatables;
use Carbon\Carbon;

use App\Suppliers;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $date    = Carbon::now();
        $periode = $date->format('d/m/Y')." - ".$date->addDays(30)->format('d/m/Y');
        
        return view('datatables', [
            'periode' => $periode
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Suppliers = Suppliers::find($id);
        return $Suppliers;
    }

    public function getData(Request $request){
        $periode  = collect(explode('-',$request->periode));
        
        $start    = Carbon::createFromFormat('d/m/Y', trim($periode->first()))->format('Y-m-d');
        $end      = Carbon::createFromFormat('d/m/Y', trim($periode->last()))->format('Y-m-d');

        $supplier = Suppliers::where(function ($query) use ($start, $end, $request) {
                        if ($request->name == 'all') {
                            $query->whereBetween('created_at', [$start, $end])->get();
                        }else{
                           $query->whereBetween('created_at', [$start, $end])->where('name', $request->name)->get(); 
                        };
                    });

        return Datatables::of($supplier)->make(true);
    }
    
    public function getDataPeriode(Request $request){

        $periode  = collect(explode('-',$request->periode));
        
        $start    = Carbon::createFromFormat('d/m/Y H:i:s', trim($periode->first())." 00:00:00")->toDateTimeString();
        $end      = Carbon::createFromFormat('d/m/Y H:i:s', trim($periode->last())." 23:59:59")->toDateTimeString();

        $table = Table::select(['id','name','phone'])->where(function ($query) use ($start, $end, $request) {
                            if ($request->type == 'all') {
                                $query->whereBetween('created_at', [$start, $end])->whereNull('deleted_at');
                            }else{
                                $query->where('type', $request->type_id)->whereBetween('created_at', [$start, $end])->whereNull('deleted_at'); 
                            };
                        })->get();

        if (count($table) > 0 ) {
            $table = collect($table)->map(function ($item, $key) {
                $item['action'] =  \Form::open(['method' => 'DELETE', 'route' => ['user.destroy', Crypt::encrypt($item['id'])]]).
                                        '<a data-widget="set" data-toggle="tooltip" title="Edit" href="'.route('user.edit', Crypt::encrypt($item['id'])).'" class="btn btn-info btn-sm" style="margin-right:2px;">'.
                                            '<i class="fa fa-edit"></i>'.
                                        '</a>'.
                                        '<button class="btn btn-danger btn-sm" type="button" data-toggle="modal" data-target="#confirmDelete" data-title="Hapus" data-message="Apakah Anda Yakin Untuk Menghapus Data Ini ?">'.
                                            '<i data-widget="delete" data-toggle="tooltip" title="Hapus" class="fa fa-trash-o"></i>'.
                                        '</button>'.
                                    \Form::close();
                return $item;
            });
        }

        return Datatables::of($accurate_coa)->make(true);
    }
}
