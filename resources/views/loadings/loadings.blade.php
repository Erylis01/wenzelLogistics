@extends('layouts.default')

@section('title')
    All loadings
@endsection

@section('stylesheet')
    <link href="{{asset('css/loadings.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="active"
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
    class="nonActive"
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
                <div class="panel panel-general panel-warehouses">
                    <div class="panel-heading">
                        <div class="col-lg-6">List of all loadings
                        </div>
                        <form role="form" method="GET" action="{{route('showAllLoadings', ['refresh'=>'false'])}}">
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
                                <span class="col-lg-offset-6">
                                    <a href="{{route('showAllLoadings', ['refresh'=>'true'])}}" class="btn btn-add"><span
                                                class="glyphicon glyphicon-refresh"></span></a>
                            </span>
                            </div>
                        </form>
                    </div>

                    <div class="panel-body panel-body-general">
                        <!-- Table -->
                        <div class="table-responsive loadings-wrapper">
                            <table class="table table-hover table-bordered table-loadings">
                                <thead>
                                <tr>
                                        <th class="col0 colHeight"></th>
                                        <th class="col0b colHeight"></th>
                                        <th class="text-center col1 colHeight">AtrNr<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=atrnr&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=atrnr&order=asc')}}"
                                               @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=atrnr&order=desc')}}"
                                                @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=atrnr&order=desc')}}"
                                                @endif></a>
                                        </th>
                                        <th class="col1b colHeight"></th>
                                        <th class="text-center colHeight">Laded.<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=ladedatum&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=ladedatum&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=desc')}}"
                                                    @endif></a>
                                        </th>
                                        <th class="text-center colHeight">Entladed.<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=entladedatum&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=entladedatum&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">Auftraggeber<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">LadL.<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=landb&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=landb&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=landb&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=landb&order=desc')}}"
                                                    @endif></a>
                                           </th>
                                        <th class="text-center colHeight">LadP<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=plzb&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=plzb&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=plzb&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=plzb&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">LadO<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=ortb&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=ortb&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=ortb&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=ortb&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">AblL.<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=lande&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=lande&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=lande&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=lande&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">AblP.<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=plze&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=plze&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=plze&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=plze&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">AblO.<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=orte&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=orte&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=orte&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=orte&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight colAnz">Anz.<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=anz&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=anz&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=anz&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=anz&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">Art.<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=art&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=art&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=art&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=art&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">Subfrächter<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=subfrachter&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=subfrachter&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">Kennzeichen<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                        <th class="text-center colHeight">Zus. Ladestellen<br>
                                            <a class="glyphicon glyphicon-chevron-up general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=asc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=asc')}}"
                                                    @endif></a>
                                            <a class="glyphicon glyphicon-chevron-down general-sorting"
                                               @if(isset($searchQuery)) href="{{url('/loadings/'.$refresh.'?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=desc')}}"
                                               @else href="{{url('/loadings/'.$refresh.'?page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=desc')}}"
                                                    @endif></a>
                                            </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listLoadings as $loading)
                                    <tr @if($loading->state=="In progress") class="inprogress" @elseif ($loading->state=="Waiting documents") class="waitingdocuments" @elseif ($loading->state=="Complete") class="complete" @elseif ($loading->state=="Complete Validated") class="completevalidated" @else class="untreated" @endif>
                                        <td class="text-center text-danger col0 colHeight">
                                        @php($listPalletstransfers=\App\Palletstransfer::where('loading_atrnr',$loading->atrnr)->get())
                                            @php($k=0)
                                        @foreach($listPalletstransfers as $transfer)
                                            @php($errorsTransfer= \App\Http\Controllers\PalletstransfersController::actualErrors($transfer))
                                            @if(!empty($errorsTransfer)&& $k<4)
                                                <span class="glyphicon glyphicon-warning-sign text-danger"></span>
                                            @endif
                                                @php($k=$k+1)
                                        @endforeach
                                        </td>
                                        <td class="col0b colHeight"></td>
                                        <td class="text-center col1 colHeight"><a
                                                    href="{{route('showDetailsLoading',$loading->atrnr)}}">{{$loading->atrnr}}</a>
                                        </td>
                                        <td class="col1b colHeight"></td>
                                        <td class="text-center colHeight colDate">{{date('d-m-y', strtotime($loading->ladedatum))}}</td>
                                        <td class="text-center colHeight colDate">{{date('d-m-y', strtotime($loading->entladedatum))}}</td>
                                        <td class="text-center colHeight colAufr">{{$loading->auftraggeber}}</td>
                                        <td class="text-center colHeight">{{$loading->landb}}</td>
                                        <td class="text-center colHeight">{{$loading->plzb}}</td>
                                        <td class="text-center colHeight colOrt">{{$loading->ortb}}</td>
                                        <td class="text-center colHeight">{{$loading->lande}}</td>
                                        <td class="text-center colHeight">{{$loading->plze}}</td>
                                        <td class="text-center colHeight colOrt">{{$loading->orte}}</td>
                                        <td class="text-center colHeight colAnz">{{$loading->anz}}</td>
                                        <td class="text-center colHeight colArt">{{$loading->art}}</td>
                                        <td class="text-center colHeight colSubfra">{{$loading->subfrachter}}</td>
                                        <td class="text-center colHeight colKenn">{{$loading->kennzeichen}}</td>
                                        <td class="text-center colHeight colZus">{{$loading->zusladestellen}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class="general-pagination text-left">{!! $listLoadings->render() !!}</div>
                            @if ($listLoadings->currentPage()==$listLoadings->lastPage())
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 10)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @elseif($listLoadings->isEmpty())
                                <div class="general-legend col-lg-offset-9">
                                    Showing 0 to 0 of 0 results
                                </div>
                            @else
                                <div class="general-legend col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listLoadings->currentPage() -1) * 10)  {{$legend1}}
                                    to @php($legend2= $listLoadings->currentPage() * 10) {{$legend2}} of {{$count}}
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