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
                        <form role="form" method="GET" action="{{route('showAllLoadings')}}">
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
                            </div>
                        </form>
                    </div>

                    <div class="panel-body panel-body-general">
                        <!-- Table -->
                        <div class="table-responsive loadings-wrapper">
                            <table class="table table-hover table-bordered table-loadings">
                                <thead>
                                <tr>
                                    @if(isset($searchQuery))
                                        <th class="text-center col1 colHeight">AtrNr<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=atrnr&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=atrnr&order=desc')}}"></a>
                                        </th>
                                        <th class="col1b colHeight"></th>
                                        <th class="text-center colHeight">Laded.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=ladedatum&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=ladedatum&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Entladed.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=entladedatum&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=entladedatum&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Auftraggeber<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">LadL.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=landb&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=landb&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">LadP<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=plzb&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=plzb&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">LadO<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=ortb&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=ortb&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">AblL.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=lande&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=lande&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">AblP.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=plze&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=plze&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">AblO.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=orte&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=orte&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Anz.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=anz&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=anz&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Art.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=art&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=art&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Subfrächter<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=subfrachter&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=subfrachter&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Kennzeichen<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Zus. Ladestellen<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?search='.$searchQuery.'&searchColumnsString='.$searchColumnsString.'&page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=desc')}}"></a>
                                        </th>
                                    @else
                                        <th class="text-center col1 colHeight">AtrNr<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=atrnr&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=atrnr&order=desc')}}"></a>
                                        </th>
                                        <th class="col1b colHeight"></th>
                                        <th class="text-center colHeight">Laded.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ladedatum&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Entladed.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=entladedatum&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Auftraggeber<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=auftraggeber&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">LadL.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=landb&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=landb&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">LadP<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plzb&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plzb&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">LadO<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ortb&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=ortb&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">AblL.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=lande&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=lande&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">AblP.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plze&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=plze&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">AblO<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=orte&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=orte&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Anz.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=anz&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=anz&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Art.<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=art&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=art&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Subfrächter<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=subfrachter&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Kennzeichen<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=kennzeichen&order=desc')}}"></a>
                                        </th>
                                        <th class="text-center colHeight">Zus. Ladestellen<br><a
                                                    class="glyphicon glyphicon-chevron-up general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=asc')}}"></a><a
                                                    class="glyphicon glyphicon-chevron-down general-sorting"
                                                    href="{{url('/loadings?page='.$listLoadings->currentPage().'&sortby=zusladestellen&order=desc')}}"></a>
                                        </th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listLoadings as $loading)
                                    {{--@if($loading->state=="OK")--}}
                                    {{--@php($class="success")--}}
                                    {{--@elseif ($loading->state=="almost OK")--}}
                                    {{--@php($class="warning")--}}
                                    {{--@elseif ($loading->state=="not OK")--}}
                                    {{--@php($class="danger")--}}
                                    {{--@else--}}
                                    {{--@php ($class="default")--}}
                                    {{--@endif--}}
                                    <tr>
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
                                        <td class="text-center colHeight">{{$loading->anz}}</td>
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