@extends('layouts.default')

@section('title')
    All pallets transfers
@endsection

@section('stylesheet')
    <link href="{{asset('css/palletstransfers.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classTrucks')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    nonActive
@endsection
@section('classPalletsTransfers')
    class="active"
@endsection
@section('classProfile')
    nonActive
@endsection

@section('content')

    <div class="row">
        @if(Auth::guest())
            <h4>You need to login to see the content</h4>
        @else
            <div class="col-lg-14">
                <div class="panel panel-general">
                    <div class="panel-heading">
                        <div class="col-lg-4">List of all pallets transfers
                        </div>
                        <form role="form" method="GET" action="{{route('showAllPalletstransfers')}}">
                            {{ csrf_field() }}
                            <div class="col-lg-5 input-group searchBar">
                            <span class="input-group-btn searchInput">
                                @if(isset($searchQuery))
                                    <input type="text" class="form-control" name="search" value="{{$searchQuery}}"
                                           placeholder="search">
                                @else
                                    <input type="text" class="form-control" name="search" value=""
                                           placeholder="search">
                                @endif
                            </span>
                                <span class="input-group-btn">
                                    <select class="selectpicker show-tick form-control searchSelect" data-size="5"
                                            data-live-search="true" data-live-search-style="startsWith"
                                            title="columns" name="searchColumns[]" multiple required>
                                      @if((isset($searchColumns)&& in_array('ALL',$searchColumns))||(Illuminate\Support\Facades\Input::old('searchColumns') && in_array('ALL', Illuminate\Support\Facades\Input::old('searchColumns'))))
                                            <option selected>ALL</option>
                                        @else
                                            <option>ALL</option>
                                        @endif
                                        @foreach($listColumns as $column)
                                            @php($list[]=null)
                                            @if(isset($searchColumns))
                                                @foreach($searchColumns as $searchC)
                                                    @if($column==$searchC)
                                                        <option selected>{{$column}}</option>
                                                        @php($list[]=$column)
                                                    @endif
                                                @endforeach
                                                @if(!in_array($column, $list))
                                                    <option>{{$column}}</option>
                                                @endif
                                            @elseif(Illuminate\Support\Facades\Input::old('searchColumns'))
                                                @foreach(old('searchColumns') as $searchC)
                                                    @if($column==$searchC)
                                                        <option selected>{{$column}}</option>
                                                        @php($list[]=$column)
                                                    @endif
                                                @endforeach
                                                @if(!in_array($column, $list))
                                                    <option>{{$column}}</option>
                                                @endif
                                            @else
                                                <option>{{$column}}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                     </span>
                                <span class="input-group-btn">
                                <button class="btn glyphicon glyphicon-search" type="submit"
                                        name="searchSubmit"></button>
                            </span>
                                <span class="col-lg-offset-8">
                                <a href="{{route('showAddPalletstransfer')}}" class="btn btn-add"><span
                                            class="glyphicon glyphicon-plus-sign"></span> Add transfers</a>
                        </span>
                            </div>
                        </form>
                    </div>

                    <div class="panel-body panel-body-general">
                        @if(Session::has('messageDeletePalletstransfer'))
                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageDeletePalletstransfer') }}</p>
                        @elseif(Session::has('messageAddPalletstransfer'))
                            <p class="alert alert-success text-alert text-center">{{ Session::get('messageAddPalletstransfer') }}</p>
                    @endif

                    <!-- Table -->
                        <div class="table-responsive table-transfers">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center col1 colHeightTitle">ID<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=id&order=asc')}}"
                                            @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=id&order=asc')}}"
                                                @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=id&order=desc')}}"
                                            @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=id&order=desc')}}"
                                                @endif></a>
                                        </th>
                                        <th class="col1b colHeightTitle"></th>
                                        <th class="colHeightTitle col8"></th>
                                        <th class="text-center col2 colHeightTitle">Date<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=date&order=asc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=date&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=date&order=desc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=date&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center col3 colHeightTitle">Type<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=type&order=asc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=type&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=type&order=desc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=type&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center col4 colHeightTitle">Debit Account<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=debitAccount&order=asc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=debitAccount&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=debitAccount&order=desc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=debitAccount&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center col4 colHeightTitle">Credit Account<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=creditAccount&order=asc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=creditAccount&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=creditAccount&order=desc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=creditAccount&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center col5 colHeightTitle">Pal.<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=asc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=desc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center col6 colHeightTitle">Loading<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=asc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=desc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=loading_atrnr&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center col7 colHeightTitle">State<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=state&order=asc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=state&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=state&order=desc')}}"
                                               @else href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=state&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($listPalletstransfers as $transfer)
                                    <tr @if($transfer->state=="Untreated") class="untreated" @elseif($transfer->state=="Waiting documents") class="waitingdocuments" @elseif($transfer->state=="Complete") class="complete" @else class="completevalidated" @endif>
                                        <td class="text-center colHeight col1">
                                            <a class="link" href="{{route('showDetailsPalletstransfer',$transfer->id)}}">{{$transfer->id}}</a>
                                        </td>
                                        <td class="col1b colHeight"></td>
                                        <td class="text-right colDanger colHeight col8">
                                            @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                            @php($k=0)
                                        @if(!empty($errorsTransfer))
                                                @foreach($errorsTransfer as $errorTrans)
                                                    @if(!empty($errorTrans)&& $k<2)
                                                        <span class="glyphicon glyphicon-warning-sign text-danger" data-toggle="tooltip" title="{{$errorTrans->name}}"></span>
                                                    @elseif(!empty($errorTrans)&& $k==2)
                                                        <span class="text-danger">...</span>
                                                    @endif
                                                    @php($k=$k+1)
                                                    @endforeach
                                            @endif
                                        </td>
                                        <td class="text-center colHeight col2">{{date('d-m-y', strtotime($transfer->date))}}</td>
                                        <td class="text-center colHeight col3">@if($transfer->type=='Deposit-Withdrawal') Dep-With @elseif($transfer->type=='Withdrawal-Deposit') With-Dep @elseif($transfer->type=='Withdrawal_Only') With_only @elseif($transfer->type=='Deposit_Only') Dep_only @elseif($transfer->type=='Sale-Purchase') Sale-Purch @elseif($transfer->type=='Purchase-Sale') Purch-Sale @elseif($transfer->type=='Other') Other @elseif($transfer->type=='Debt') Debt @endif</td>
                                        <td class="text-center colHeight col4">
                                        @if(isset($transfer->debitAccount))
                                            @php($partsDebitAccount=explode('-', $transfer->debitAccount))
                                            @php($typeDebitAccount=$partsDebitAccount[count($partsDebitAccount)-2])
                                            @php($idDebitAccount=$partsDebitAccount[count($partsDebitAccount)-1])
                                            @if($typeDebitAccount=='account')
                                                @php($nameDebitAccount=\App\Palletsaccount::where('id', $idDebitAccount)->first()->name)
                                                    <a class="link"
                                                       href="{{route('showDetailsPalletsaccount',$idDebitAccount)}}">{{$nameDebitAccount}}</a>
                                            @elseif($typeDebitAccount=='truck')
                                                @php($nameDebitAccount=\App\Truck::where('id', $idDebitAccount)->first()->name)
                                                @php($licensePlateDebitAccount=\App\Truck::where('id', $idDebitAccount)->first()->licensePlate)
                                                    <a class="link"
                                                       href="{{route('showDetailsTruck',$idDebitAccount)}}">{{$nameDebitAccount}}
                                                        - {{$licensePlateDebitAccount}}</a>
                                            @endif
                                        @endif
                                        </td>
                                        <td class="text-center colHeight col4">
                                        @if(isset($transfer->creditAccount))
                                            @php($partsCreditAccount=explode('-', $transfer->creditAccount))
                                            @php($typeCreditAccount=$partsCreditAccount[count($partsCreditAccount)-2])
                                            @php($idCreditAccount=$partsCreditAccount[count($partsCreditAccount)-1])
                                            @if($typeCreditAccount=='account')
                                                @php($nameCreditAccount=\App\Palletsaccount::where('id', $idCreditAccount)->first()->name)
                                                    <a class="link"
                                                       href="{{route('showDetailsPalletsaccount',$idCreditAccount)}}">{{$nameCreditAccount}}</a>
                                            @elseif($typeCreditAccount=='truck')
                                                @php($nameCreditAccount=\App\Truck::where('id', $idCreditAccount)->first()->name)
                                                @php($licensePlateCreditAccount=\App\Truck::where('id', $idCreditAccount)->first()->licensePlate)
                                                    <a class="link"
                                                       href="{{route('showDetailsTruck',$idCreditAccount)}}">{{$nameCreditAccount}}
                                                        - {{$licensePlateCreditAccount}}</a>
                                            @endif
                                        @endif
                                        </td>
                                        <td class="text-center colHeight col5">{{$transfer->palletsNumber}}</td>
                                        <td class="text-center colHeight col6"><a class="link"
                                                                                  href="{{route('showDetailsLoading',$transfer->loading_atrnr)}}">{{$transfer->loading_atrnr}}</a>
                                            </td>
                                        <td class="text-center colHeight col7">{{$transfer->state}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="general-pagination text-left">{!! $listPalletstransfers->render() !!}</div>

                            @if ($listPalletstransfers->currentPage()==$listPalletstransfers->lastPage())
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listPalletstransfers->currentPage() -1) * 10)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @elseif($listPalletstransfers->isEmpty())
                                <div class="general-legend col-lg-offset-9">
                                    Showing 0 to 0 of 0 results
                                </div>
                            @else
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listPalletstransfers->currentPage() -1) * 10)  {{$legend1}}
                                    to @php($legend2= $listPalletstransfers->currentPage() * 10) {{$legend2}}
                                    of {{$count}}
                                    results
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection