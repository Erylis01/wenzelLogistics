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
    class="nonActive"
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
                                <a href="{{route('showAddPalletstransfer')}}" class="btn btn-add"><span class="glyphicon glyphicon-plus-sign"></span> Add transfers</a>
                        </span>
                            </div>
                        </form>
                    </div>

                    <div class="panel-body panel-body-general">
                        @if(Session::has('messageDeletePalletstransfer'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeletePalletstransfer') }}</div>
                        @elseif(Session::has('messageAddPalletstransfer'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddPalletstransfer') }}</div>
                    @endif

                    <!-- Table -->
                        <div class="table-responsive table-transfers">
                            <table class="table table-hover table-bordered">
                                <thead>
                                @if(isset($searchQuery))
                                    <tr>
                                        <th class="text-center col1" >ID<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=id&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center col2" >Date<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=date&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=date&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center col3">Type<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=type&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=type&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center col4">Credit Account<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=creditAccount&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=creditAccount&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center col4">Debit Account<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=debitAccount&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=debitAccount&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center col5">Pal. Nbr<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center col7">State<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=state&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/allPalletstransfers?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listPalletstransfers->currentPage().'&sortby=state&order=desc')}}"></a>
                                        </th>
                                        <th></th>
                                    </tr>
                                    @else
                                <tr>
                                    <th class="text-center col1" >ID<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=id&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center col2" >Date<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=date&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=date&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center col3">Type<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=type&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=type&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center col4">Credit Account<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=creditAccount&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=creditAccount&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center col4">Debit Account<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=debitAccount&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=debitAccount&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center col5">Pal. Nbr<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=palletsNumber&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center col7">State<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=state&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletstransfers?page='.$listPalletstransfers->currentPage().'&sortby=state&order=desc')}}"></a>
                                    </th>
                                    <th></th>
                                </tr>
                                    @endif
                                </thead>
                                <tbody>
                                @foreach($listPalletstransfers as $transfer)
                                    @if($transfer->state=="Untreated")
                                        @php($class="untreated")
                                    @elseif($transfer->state=="Waiting documents")
                                        @php($class="waitingdocuments")
                                    @elseif($transfer->state=="Complete")
                                        @php($class="complete")
                                    @else
                                        @php($class="completevalidated")
                                    @endif
                                    <tr class="{{$class}}">
                                        <td class="text-center"><a class="link" href="{{route('showDetailsPalletstransfer',$transfer->id)}}">{{$transfer->id}}</a>
                                        </td>
                                        <td class="text-center">{{date('d-m-y', strtotime($transfer->date))}}</td>
                                        <td class="text-center">{{$transfer->type}}</td>
                                        @if(isset($transfer->creditAccount))
                                        @php($creditAccountId=\App\Palletsaccount::where('name', $transfer->creditAccount)->first()->id)
                                        <td class="text-center"><a class="link" href="{{route('showDetailsPalletsaccount',$creditAccountId)}}">{{$transfer->creditAccount}}</a></td>
                                        @else
                                            <td></td>
                                        @endif
                                        @if(isset($transfer->debitAccount))
                                            @php($debitAccountId=\App\Palletsaccount::where('name', $transfer->debitAccount)->first()->id)
                                        <td class="text-center"><a class="link" href="{{route('showDetailsPalletsaccount',$debitAccountId)}}">{{$transfer->debitAccount}}</a></td>
                                        @else
                                            <td></td>
                                        @endif
                                            <td class="text-center">{{$transfer->palletsNumber}}</td>
                                        <td class="text-center">{{$transfer->state}}</td>
                                        @if(Session::has('error'.$transfer->id))
                                        <td class="text-center"><span class="glyphicon glyphicon-warning-sign text-danger"></span></td>
                                            @else
                                        <td></td>
                                            @endif
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
                                    to @php($legend2= $listPalletstransfers->currentPage() * 10) {{$legend2}} of {{$count}}
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