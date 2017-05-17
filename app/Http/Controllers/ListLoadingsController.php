<?php

namespace App\Http\Controllers;



use App\Loading;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
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
        if (Auth::check()) {
        $this->importData();
        $currentDate = Carbon::now();
        $limitDate=$currentDate->subDays(60)->format('Y-m-d');
//        $listLoadings = DB::table('loadings')->where([
//            ['paltauschvereinbart', '=', 'ja'],
//            ['ladedatum', '>=', $limitDate],
//])->distinct()->get();

        if (request()->has('sortby') && request()->has('order')) {
            $sortby = $request->get('sortby'); // Order by what column?
            $order = $request->get('order'); // Order direction: asc or desc

//            $listLoadings =DB::table('loadings')->orderBy($sortby, $order)->paginate(5);
            $listLoadings=DB::table('loadings')->where('paltauschvereinbart', '=','ja')->orderBy($sortby, $order)->paginate(5);
            $links=$listLoadings->appends(['sortby'=>$sortby, 'order'=>$order])->render();
        }
        else{

            $listLoadings = DB::table('loadings')->where('paltauschvereinbart', '=','ja')->paginate(5);
            $links='';

        }
    $count=count(DB::table('loadings')->where('paltauschvereinbart', '=','ja')->get());


        return view('loadings', compact('listLoadings','sortby', 'order', 'links', 'count'));
    }else{
            return view('auth.login');
        }}

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
