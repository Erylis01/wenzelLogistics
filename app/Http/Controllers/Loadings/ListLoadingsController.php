<?php

namespace App\Http\Controllers;

use App\Loading;
use App\Palletsaccount;
use App\Truck;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;

;

class ListLoadingsController extends Controller
{
    /**
     * Display the content - load new data when asked - display only search fields - display sorting table
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $refresh)
    {
        if (Auth::check()) {
            //to import all new loadings in excel files
            if ($refresh == 'true') {
                $this->importData();
                $refresh = 'false';
            }

            //search key and search columns
            $searchQuery = $request->get('search');
            $searchQueryArray = explode(' ', $searchQuery);
            $searchColumns = $request->get('searchColumns');
            $listColumns = ['atrnr', 'ladedatum', 'entladedatum', 'auftraggeber', 'landb', 'plzb', 'ortb', 'lande', 'plze', 'orte', 'anz', 'art', 'subfrachter', 'kennzeichen', 'zusladestellen', 'state'];

            $query = DB::table('loadings')
                ->where('pt', 'ja');

            //if the user wan to sort the table
            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                $searchColumnsString = $request->get('searchColumnsString');;
                $searchColumns = explode('-', $searchColumnsString);
                //if sorting + search
                if (isset($searchQuery) && $searchQuery <> '') {
                    //if search in all columns
                    if (in_array('ALL', explode('-', $searchColumnsString))) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        //if search in specific columns
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                }
                $count = count($query->get());
                $listLoadings = $query->orderBy($sortby, $order)->paginate(10);
                $links = $listLoadings->appends(['sortby' => $sortby, 'order' => $order, 'search' => $searchQuery, 'searchColumnsString' => $searchColumnsString])->render();
            } else {
                //if not sorting but search
                if (isset($searchQuery) && $searchQuery <> '') {
                    $searchColumnsString = implode('-', $searchColumns);
                    if (in_array('ALL', $searchColumns)) {
                        $query->where(function ($q) use ($searchQueryArray, $listColumns) {
                            foreach ($listColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    } else {
                        $query->where(function ($q) use ($searchQueryArray, $searchColumns) {
                            foreach ($searchColumns as $column) {
                                foreach ($searchQueryArray as $searchQ) {
                                    $q->orWhere($column, 'LIKE', '%' . $searchQ . '%');
                                }
                            }
                        });
                    }
                    $count = count($query->get());
                    $listLoadings = $query->orderBy('ladedatum', 'asc')->paginate(10);
                    $links = $listLoadings->appends(['search' => $searchQuery, 'searchColumns' => $searchColumns])->render();
                } else {
                    //if not sorting and not search
                    $count = count($query->get());
                    $listLoadings = $query->orderBy('ladedatum', 'desc')->paginate(10);
                    $links = '';
                }
            }
            return view('loadings.loadings', compact('refresh', 'listLoadings', 'sortby', 'order', 'links', 'count', 'searchQuery', 'searchColumns', 'searchColumnsString', 'listColumns'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * Import data from an excel file in the directory Hypertrans
     */
    public function importData()
    {
        $path = '../resources/assets/excel/Hypertrans';
        $files = File::allFiles($path);
        foreach ($files as $file) {
            if (strpos((string)$file, '.xls') !== false) {

                Excel::load($file, function ($reader) {
                    if (!empty($reader)) {
                        $reader->noHeading();
                        $sheet = $reader->getSheet(0)->toArray();
                        $nbrows = count($sheet);

                        for ($r = 4; $r < $nbrows; $r++) {
                            $loadingsTest = DB::table('loadings')->where('atrnr', '=', trim($sheet[$r][3]))->first();
                            if ($loadingsTest == null) {
                                //not double
                                $datel_parse = date_parse_from_format('m-d-y', trim($sheet[$r][0]));
                                $datel = new DateTime();
                                $datel->setDate($datel_parse['year'], $datel_parse['month'], $datel_parse['day']);

                                $datee_parse = date_parse_from_format('m-d-y', trim($sheet[$r][1]));
                                $datee = new DateTime();
                                $datee->setDate($datee_parse['year'], $datee_parse['month'], $datee_parse['day']);

                                $k = count(Loading::get()) + 1;
                                Loading::firstOrCreate([
                                    'id' => $k,
                                    'entladedatum' => $datee,
                                    'disp' => trim($sheet[$r][2]),
                                    'atrnr' => trim($sheet[$r][3]),
                                    'referenz' => trim($sheet[$r][4]),
                                    'auftraggeber' => trim($sheet[$r][5]),
                                    'beladestelle' => trim($sheet[$r][6]),
                                    'landb' => trim($sheet[$r][7]),
                                    'plzb' => trim($sheet[$r][8]),
                                    'ortb' => trim($sheet[$r][9]),
                                    'entladestelle' => trim($sheet[$r][10]),
                                    'lande' => trim($sheet[$r][11]),
                                    'plze' => trim($sheet[$r][12]),
                                    'orte' => trim($sheet[$r][13]),
                                    'anz' => trim($sheet[$r][14]),
                                    'art' => trim($sheet[$r][15]),
                                    'ware' => trim($sheet[$r][16]),
                                    'gewicht' => trim($sheet[$r][17]),
                                    'vol' => trim($sheet[$r][18]),
                                    'ldm' => trim($sheet[$r][19]),
                                    'umsatz' => trim($sheet[$r][20]),
                                    'aufwand' => trim($sheet[$r][21]),
                                    'db' => trim($sheet[$r][22]),
                                    'trp' => trim($sheet[$r][23]),
                                    'pt' => trim($sheet[$r][24]),
                                    'subfrachter' => trim($sheet[$r][25]),
                                    'kennzeichen' => trim($sheet[$r][26]),
                                    'zusladestellen' => trim($sheet[$r][27]),
                                ]);
                            }
                            if (trim($sheet[$r][26]) <> '') {
                                $licensePlate = trim($sheet[$r][26]);
                            } else {
                                $licensePlate = 'OTHER';
                            }
                            if ($sheet[$r][25] <> null) {
                                $name = trim(explode(',', $sheet[$r][25])[0]);
                                $adress = trim(explode(',', $sheet[$r][25])[1]);

                                $testAccount = Palletsaccount::where('type', 'Carrier')->where('name', $name)->first();
                                if ($testAccount == null) {
                                    Palletsaccount::firstOrCreate([
                                        'name' => $name,
                                            'nickname' => $name,
                                        'adress' => $adress,
                                        'type' => 'Carrier',
                                    ]);
                                }

                                $testTruckStock = Truck::where('licensePlate', '=', 'STOCK')->where('name', $name)->first();

                                if ($testTruckStock == null) {
                                    Truck::firstOrCreate([
                                        'name' => $name,
                                        'nickname' => $name,
                                        'licensePlate' => 'STOCK',
                                        'palletsaccount_name' => $name,
                                    ]);
                                }
                                $testTruck = Truck::where('licensePlate', '=', $licensePlate)->where('name', $name)->first();

                                if ($testTruck == null) {
                                    //not double
                                    Truck::firstOrCreate([
                                        'name' => $name,
                                        'nickname' => $name,
                                        'licensePlate' => $licensePlate,
                                        'palletsaccount_name' => $name,
                                    ]);
                                }

                            }
                        }
                    }
                });
            }
        }
    }

}
