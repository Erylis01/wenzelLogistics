<?php

namespace App\Http\Controllers;



use App\Loading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use LoadingTableSeeder;
use Maatwebsite\Excel\Facades\Excel;

class ListLoadingsController extends Controller
{
    /**
     * Display the content.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $this->importData();
        $currentDate = Carbon::now();
        $limitDate=$currentDate->subDays(60)->format('Y-m-d');
//        $listLoadings = DB::table('loadings')->where([
//            ['pt', '=', 'test'],
//            ['ladedatum', '>=', $limitDate],
//])->distinct()->get();
        $listLoadings = DB::table('loadings')->get();

//        $order = $request->get('order'); // Order by what column?
//        $dir = $request->get('dir'); // Order direction: asc or desc
//        if ($order && $dir) {
//            $listLoadings = $listLoadings->orderBy($order, $dir);
//        }
        return view('loadings', compact('listLoadings'));
    }

    /**
     * Import data from an excel file
     */
    public function importData(){
        Artisan::call('db:seed');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }
}
