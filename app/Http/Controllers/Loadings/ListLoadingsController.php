<?php

namespace App\Http\Controllers;

use App\Carrier;
use App\Loading;
use App\Palletsaccount;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

;

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
            $limitDate = $currentDate->subDays(60)->format('Y-m-d');
            $searchQuery = $request->get('search');

            if (request()->has('sortby') && request()->has('order')) {
                $sortby = $request->get('sortby'); // Order by what column?
                $order = $request->get('order'); // Order direction: asc or desc
                if (isset($searchQuery) && $searchQuery <> '') {
                    //search query
                    $listLoadings = DB::table('loadings')
                        ->where('pt', 'ja')
                        ->where('ladedatum', '>=', $limitDate)
                        ->where(function ($q) use ($searchQuery) {
                            $q->where('atrnr', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('ladedatum', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('entladedatum', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('auftraggeber', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('landb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('plzb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('ortb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('lande', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('plze', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('orte', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('anz', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('art', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('subfrachter', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('kennzeichen', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('zusladestellen', 'LIKE', '%' . $searchQuery . '%');
                        })->orderBy($sortby, $order)->paginate(10);

                    $count = count(DB::table('loadings')->where('pt', 'ja')
                        ->where('ladedatum', '>=', $limitDate)
                        ->where(function ($q) use ($searchQuery) {
                            $q->where('atrnr', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('ladedatum', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('entladedatum', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('auftraggeber', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('landb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('plzb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('ortb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('lande', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('plze', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('orte', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('anz', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('art', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('subfrachter', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('kennzeichen', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('zusladestellen', 'LIKE', '%' . $searchQuery . '%');
                        })->get());
                } else {
                    $listLoadings = DB::table('loadings')->where([
                        ['pt', '=', 'ja'],
                        ['ladedatum', '>=', $limitDate],
                    ])->orderBy($sortby, $order)->paginate(10);
                    $count = count(DB::table('loadings')->where([
                        ['pt', '=', 'ja'],
                        ['ladedatum', '>=', $limitDate],
                    ])->get());
                }
                $links = $listLoadings->appends(['sortby' => $sortby, 'order' => $order])->render();
            } else {
                if (isset($searchQuery) && $searchQuery <> '') {
                    $listLoadings = DB::table('loadings')
                        ->where('pt', 'ja')
                        ->where('ladedatum', '>=', $limitDate)
                        ->where(function ($q) use ($searchQuery) {
                            $q->where('atrnr', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('ladedatum', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('entladedatum', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('auftraggeber', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('landb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('plzb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('ortb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('lande', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('plze', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('orte', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('anz', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('art', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('subfrachter', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('kennzeichen', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('zusladestellen', 'LIKE', '%' . $searchQuery . '%');
                        })->paginate(10);

                    $count = count(DB::table('loadings')->where('pt', 'ja')
                        ->where('ladedatum', '>=', $limitDate)
                        ->where(function ($q) use ($searchQuery) {
                            $q->where('atrnr', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('ladedatum', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('entladedatum', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('auftraggeber', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('landb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('plzb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('ortb', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('lande', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('plze', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('orte', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('anz', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('art', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('subfrachter', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('kennzeichen', 'LIKE', '%' . $searchQuery . '%')
                                ->orWhere('zusladestellen', 'LIKE', '%' . $searchQuery . '%');
                        })->get());

                } else {
                    $listLoadings = DB::table('loadings')->where([
                        ['pt', '=', 'ja'],
                        ['ladedatum', '>=', $limitDate],
                    ])->paginate(10);
                    $count = count(DB::table('loadings')->where([
                        ['pt', '=', 'ja'],
                        ['ladedatum', '>=', $limitDate],
                    ])->get());
                }
                $links = '';
            }

            return view('loadings.loadings', compact('listLoadings', 'sortby', 'order', 'links', 'count', 'searchQuery'));
        } else {
            return view('auth.login');
        }
    }

    /**
     * Import data from an excel file
     */
    public function importData()
    {
        $path = '../resources/assets/excel/';
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

                                Loading::firstOrCreate([
                                    'ladedatum' => $datel,
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
                            $carrierTest = DB::table('carriers')->where('licensePlate', '=', trim($sheet[$r][26]))->first();
                            if ($carrierTest == null) {
                                //not double
                                if (trim($sheet[$r][26]) == null) {
                                    Palletsaccount::firstOrCreate([
                                        'name' => trim($sheet[$r][25]),
                                        'type' => 'Carrier',
                                    ]);
                                    Carrier::firstOrCreate([
                                        'name' => trim($sheet[$r][25]),
                                        'licensePlate' => 'OTHER',
                                        'palletsaccount_name' => trim($sheet[$r][25]),
                                    ]);
                                } else {
                                    Palletsaccount::firstOrCreate([
                                        'name' => trim($sheet[$r][26]) . ' - ' . trim($sheet[$r][25]),
                                        'type' => 'Carrier',
                                    ]);
                                    Carrier::firstOrCreate([
                                        'name' => trim($sheet[$r][25]),
                                        'licensePlate' => trim($sheet[$r][26]),
                                        'palletsaccount_name' => trim($sheet[$r][26]) . ' - ' . trim($sheet[$r][25]),
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
