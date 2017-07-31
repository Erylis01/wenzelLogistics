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
    public function show(Request $request)
    {
        if (Auth::check()) {
            $errorsAtrnrImport = $request->errorsAtrnrImport;
            $errorsColImport = $request->errorsColImport;
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
            return view('loadings.loadings', compact('errorsColImport', 'errorsAtrnrImport', 'listLoadings', 'sortby', 'order', 'links', 'count', 'searchQuery', 'searchColumns', 'searchColumnsString', 'listColumns'));
        } else {
            return view('auth.login');
        }
    }

    public function uploadImport(Request $request)
    {
        $documents = $request->file('documentsLoadings');
        if (isset($documents)) {
            foreach ($documents as $doc) {
                $filename = $doc->getClientOriginalName();
                $extension = $doc->getClientOriginalExtension();
                $size = $doc->getSize();
                //if file is an image, a pdf or an email
                if (($extension == 'xlsx' || $extension == 'xls') && $size < 2000000) {
                    Storage::putFileAs('/../../resources/assets/excel/Hypertrans', $doc, $filename);
                } else {
                    session()->flash('messageErrorUpload', 'Error ! The file type is not supported (xlsx or xls only');
                }
            }
            $data = $this->importData();
            $listLoadings = Loading::where('pt', 'ja')->get();
            if (count($data[0]) > 0) {
                $errorsAtrnrImport = $data[0];
                $errorsColImport = $data[1];
                return view('loadings.loadings', compact('errorsAtrnrImport', 'errorsColImport', 'listLoadings'));
            } else {
                return redirect('/loadings');
            }
        } else {
            return redirect('/loadings');
        }
    }

    /**
     * Import data from an excel file in the directory Hypertrans
     */
    public function importData()
    {
        $path = '../resources/assets/excel/Hypertrans';
        $files = File::allFiles($path);
        $errorsAtrnrImport = [];
        $errorsColImport = [];

        ini_set('memory_limit', '-1');
        set_time_limit(500);
        foreach ($files as $file) {
            if (strpos((string)$file, '.xls') !== false) {
                $data = Excel::load($file, function ($reader) {
                });
                $sheet = $data->getSheet(0)->toArray();
                $nbrows = count($sheet);
                for ($r = 4; $r < $nbrows; $r++) {
                    if (trim($sheet[$r][24]) == 'JA') {
                    if (trim($sheet[$r][26]) <> '') {
                        $licensePlate = trim($sheet[$r][26]);
                    } else {
                        $licensePlate = 'OTHER';
                    }
                    if ($sheet[$r][25] <> null) {
                        if (count(explode(',', $sheet[$r][25])) > 2) {
                            $adress = trim(explode(',', $sheet[$r][25])[count(explode(',', $sheet[$r][25])) - 1]);
                            $name = trim(str_replace($adress, '', $sheet[$r][25]));
                            $country = null;
                            $zipcode = null;
                            $town = null;
                        } else {
                            $name = trim(explode(',', $sheet[$r][25])[0]);
                            $adress = trim(explode(',', $sheet[$r][25])[1]);
                            $country = trim(explode('-', $adress)[0]);
                            $zipTown = trim(explode('-', $adress)[1]);
                            $zipcode = trim(explode(' ', $zipTown)[0]);
                            $town = str_replace($zipcode, '', $zipTown);
                        }

                        $testAccount = Palletsaccount::where('type', 'Carrier')->where(function ($q) use ($name) {
                            $q->where('name', $name)->orWhere('nickname', $name);
                        })->first();
                        if ($testAccount == null) {
                            Palletsaccount::firstOrCreate([
                                'name' => $name,
                                'nickname' => $name,
                                'adress' => $adress,
                                'country' => $country,
                                'zipcode' => $zipcode,
                                'town' => $town,
                                'type' => 'Carrier',
                            ]);
                        }

                        $testTruckStock = Truck::where('licensePlate', '=', 'STOCK')->where('name', $name)->first();

                        if ($testTruckStock == null) {
                            Truck::firstOrCreate([
                                'name' => $name,
                                'licensePlate' => 'STOCK',
                                'palletsaccount_name' => $name,
                            ]);
                        }
                        $testTruck = Truck::where('licensePlate', '=', $licensePlate)->where('name', $name)->first();

                        if ($testTruck == null) {
                            //not double
                            Truck::firstOrCreate([
                                'name' => $name,
                                'licensePlate' => $licensePlate,
                                'palletsaccount_name' => $name,
                            ]);
                        }
                    }
                    }

                    //check if importation is possible or not
                    $loadingTest = DB::table('loadings')->where('atrnr', '=', trim($sheet[$r][3]))->first();
                    if ($loadingTest == null) {
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
                            'ladedatum' => $datel,
                            'entladedatum' => $datee,
                            'disp' => trim($sheet[$r][2]),
                            'atrnr' => trim($sheet[$r][3]),
                            'referenz' => trim($sheet[$r][4]),
                            'auftraggeber' => trim($sheet[$r][5]),
                            'beladestelle' => trim($sheet[$r][6]),
                            'landb' => trim($sheet[$r][7]),
                            'plzb' => trim(intval(str_replace('-', '', $sheet[$r][8]))),
                            'ortb' => trim($sheet[$r][9]),
                            'entladestelle' => trim($sheet[$r][10]),
                            'lande' => trim($sheet[$r][11]),
                            'plze' => trim(intval(str_replace('-', '', $sheet[$r][12]))),
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
                    } else {
                        if (date("m-d-y", strtotime($loadingTest->ladedatum)) <> trim($sheet[$r][0])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Ladedatum';
                        }
                        if (date("m-d-y", strtotime($loadingTest->entladedatum)) <> trim($sheet[$r][1])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Entladedatum';
                        }
                        if ($loadingTest->referenz <> trim($sheet[$r][4])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Referenz';
                        }
                        if ($loadingTest->auftraggeber <> trim($sheet[$r][5])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Auftraggeber';
                        }
                        if ($loadingTest->beladestelle <> trim($sheet[$r][6])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Beladestelle';
                        }
                        if ($loadingTest->landb <> trim($sheet[$r][7])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Land beladestelle';
                        }
                        if ($loadingTest->ortb <> trim($sheet[$r][9])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Ort beladestelle';
                        }
                        if ($loadingTest->plzb <> trim(intval(str_replace('-', '', $sheet[$r][8])))) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Plz beladestelle';
                        }
                        if ($loadingTest->entladestelle <> trim($sheet[$r][10])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Entladestelle';
                        }
                        if ($loadingTest->lande <> trim($sheet[$r][11])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Land entladestelle';
                        }
                        if ($loadingTest->orte <> trim($sheet[$r][13])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Ort entladestelle';
                        }
                        if ($loadingTest->plze <> trim(intval(str_replace('-', '', $sheet[$r][12])))) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Plz entladestelle';
                        }
                        if ($loadingTest->anz <> trim($sheet[$r][14])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Anz';
                        }
                        if ($loadingTest->art <> trim($sheet[$r][15])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Art';
                        }
                        if ($loadingTest->ware <> trim($sheet[$r][16])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Ware';
                        }
                        if ($loadingTest->subfrachter <> trim($sheet[$r][25])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Subfrachter';
                        }
                        if (trim($sheet[$r][26]) == '' && ($loadingTest->kennzeichen <> 'OTHER' && $loadingTest->kennzeichen <> '')) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Kennzeichen';
                        } elseif (trim($sheet[$r][26]) <> '' && trim($sheet[$r][26]) <> $loadingTest->kennzeichen) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'kennzeichen';
                        }
                        if ($loadingTest->zusladestellen <> trim($sheet[$r][27])) {
                            $errorsAtrnrImport[] = trim($sheet[$r][3]);
                            $errorsColImport[] = 'Zus ladestellen';
                        }
                    }
                }
            }
        }
        return [$errorsAtrnrImport, $errorsColImport];
    }
}

//                $data = Excel::load($file, function ($reader) use($errorsAtrnrImport, $errorsColImport) {
//                    if (!empty($reader)) {
//                        $reader->noHeading();
//                        $sheet = $reader->getSheet(0)->toArray();
//                        $nbrows = count($sheet);
//
//                        for ($r = 4; $r < $nbrows; $r++) {
//                            if (trim($sheet[$r][26]) <> '') {
//                                $licensePlate = trim($sheet[$r][26]);
//                            } else {
//                                $licensePlate = 'OTHER';
//                            }
//                            if ($sheet[$r][25] <> null) {
//                                if (count(explode(',', $sheet[$r][25])) > 2) {
//                                    $adress = trim(explode(',', $sheet[$r][25])[count(explode(',', $sheet[$r][25])) - 1]);
//                                    $name = trim(str_replace($adress, '', $sheet[$r][25]));
//                                    $country = null;
//                                    $zipcode = null;
//                                    $town = null;
//                                } else {
//                                    $name = trim(explode(',', $sheet[$r][25])[0]);
//                                    $adress = trim(explode(',', $sheet[$r][25])[1]);
//                                    $country = trim(explode('-', $adress)[0]);
//                                    $zipTown = trim(explode('-', $adress)[1]);
//                                    $zipcode = trim(explode(' ', $zipTown)[0]);
//                                    $town = str_replace($zipcode, '', $zipTown);
//                                }
//
//                                $testAccount = Palletsaccount::where('type', 'Carrier')->where(function ($q) use ($name) {
//                                    $q->where('name', $name)->orWhere('nickname', $name);
//                                })->first();
//                                if ($testAccount == null) {
//                                    Palletsaccount::firstOrCreate([
//                                        'name' => $name,
//                                        'nickname' => $name,
//                                        'adress' => $adress,
//                                        'country' => $country,
//                                        'zipcode' => $zipcode,
//                                        'town' => $town,
//                                        'type' => 'Carrier',
//                                    ]);
//                                }
//
//                                $testTruckStock = Truck::where('licensePlate', '=', 'STOCK')->where('name', $name)->first();
//
//                                if ($testTruckStock == null) {
//                                    Truck::firstOrCreate([
//                                        'name' => $name,
//                                        'licensePlate' => 'STOCK',
//                                        'palletsaccount_name' => $name,
//                                    ]);
//                                }
//                                $testTruck = Truck::where('licensePlate', '=', $licensePlate)->where('name', $name)->first();
//
//                                if ($testTruck == null) {
//                                    //not double
//                                    Truck::firstOrCreate([
//                                        'name' => $name,
//                                        'licensePlate' => $licensePlate,
//                                        'palletsaccount_name' => $name,
//                                    ]);
//                                }
//
//                            }
//
//                            //check if importation is possible or not
//                            $loadingTest = DB::table('loadings')->where('atrnr', '=', trim($sheet[$r][3]))->first();
//                            if ($loadingTest == null) {
//                                //not double
//                                $datel_parse = date_parse_from_format('m-d-y', trim($sheet[$r][0]));
//                                $datel = new DateTime();
//                                $datel->setDate($datel_parse['year'], $datel_parse['month'], $datel_parse['day']);
//
//                                $datee_parse = date_parse_from_format('m-d-y', trim($sheet[$r][1]));
//                                $datee = new DateTime();
//                                $datee->setDate($datee_parse['year'], $datee_parse['month'], $datee_parse['day']);
//                                $k = count(Loading::get()) + 1;
//                                Loading::firstOrCreate([
//                                    'id' => $k,
//                                    'ladedatum' => $datel,
//                                    'entladedatum' => $datee,
//                                    'disp' => trim($sheet[$r][2]),
//                                    'atrnr' => trim($sheet[$r][3]),
//                                    'referenz' => trim($sheet[$r][4]),
//                                    'auftraggeber' => trim($sheet[$r][5]),
//                                    'beladestelle' => trim($sheet[$r][6]),
//                                    'landb' => trim($sheet[$r][7]),
//                                    'plzb' => trim(intval(str_replace('-', '', $sheet[$r][8]))),
//                                    'ortb' => trim($sheet[$r][9]),
//                                    'entladestelle' => trim($sheet[$r][10]),
//                                    'lande' => trim($sheet[$r][11]),
//                                    'plze' => trim(intval(str_replace('-', '', $sheet[$r][12]))),
//                                    'orte' => trim($sheet[$r][13]),
//                                    'anz' => trim($sheet[$r][14]),
//                                    'art' => trim($sheet[$r][15]),
//                                    'ware' => trim($sheet[$r][16]),
//                                    'gewicht' => trim($sheet[$r][17]),
//                                    'vol' => trim($sheet[$r][18]),
//                                    'ldm' => trim($sheet[$r][19]),
//                                    'umsatz' => trim($sheet[$r][20]),
//                                    'aufwand' => trim($sheet[$r][21]),
//                                    'db' => trim($sheet[$r][22]),
//                                    'trp' => trim($sheet[$r][23]),
//                                    'pt' => trim($sheet[$r][24]),
//                                    'subfrachter' => trim($sheet[$r][25]),
//                                    'kennzeichen' => trim($sheet[$r][26]),
//                                    'zusladestellen' => trim($sheet[$r][27]),
//                                ]);
//                            } else {
//                                if (date("m-d-y", strtotime($loadingTest->ladedatum)) <> trim($sheet[$r][0])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'ladedatum';
//                                }
//                                if (date("m-d-y", strtotime($loadingTest->entladedatum)) <> trim($sheet[$r][1])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'entladedatum';
//                                }
//                                if ($loadingTest->referenz <> trim($sheet[$r][4])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'referenz';
//                                }
//                                if ($loadingTest->auftraggeber <> trim($sheet[$r][5])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'auftraggeber';
//                                }
//                                if ($loadingTest->beladestelle <> trim($sheet[$r][6])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'beladestelle';
//                                }
//                                if ($loadingTest->landb <> trim($sheet[$r][7])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'land beladestelle';
//                                }
//                                if ($loadingTest->ortb <> trim($sheet[$r][9])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'ort beladestelle';
//                                }
//                                if ($loadingTest->plzb <> trim(intval(str_replace('-', '', $sheet[$r][8])))) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'plz beladestelle';
//                                }
//                                if ($loadingTest->entladestelle <> trim($sheet[$r][10])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'entladestelle';
//                                }
//                                if ($loadingTest->lande <> trim($sheet[$r][11])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'land entladestelle';
//                                }
//                                if ($loadingTest->orte <> trim($sheet[$r][13])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'ort entladestelle';
//                                }
//                                if ($loadingTest->plze <> trim(intval(str_replace('-', '', $sheet[$r][12])))) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'plz entladestelle';
//                                }
//                                if ($loadingTest->anz <> trim($sheet[$r][14])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'anz';
//                                }
//                                if ($loadingTest->art <> trim($sheet[$r][15])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'art';
//                                }
//                                if ($loadingTest->ware <> trim($sheet[$r][16])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'ware';
//                                }
//                                if ($loadingTest->subfrachter <> trim($sheet[$r][25])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'subfrachter';
//                                }
//
//                                if (trim($sheet[$r][26]) == '' && ($loadingTest->kennzeichen <> 'OTHER' && $loadingTest->kennzeichen <> '')) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'kennzeichen';
//                                } elseif (trim($sheet[$r][26]) <> '' && trim($sheet[$r][26]) <> $loadingTest->kennzeichen) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'kennzeichen';
//                                }
//                                if ($loadingTest->zusladestellen <> trim($sheet[$r][27])) {
//                                    $errorsAtrnrImport[] = trim($sheet[$r][3]);
//                                    $errorsColImport[] = 'zus ladestellen';
//                                }
//                            }
//                        }
//                    }
////                    return [$errorsAtrnrImport, $errorsColImport];
//                });
//                dd($data->getSheet(0)->toArray());

