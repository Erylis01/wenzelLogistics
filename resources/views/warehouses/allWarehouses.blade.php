@extends('layouts.default')

@section('title')
    All warehouses
@endsection

@section('stylesheet')
    <link href="{{asset('css/warehouses.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="active"
@endsection
@section('classPalletsAccounts')
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
                    <div class="panel-heading">List of all warehouses</div>

                    <div class="panel-body panel-body-general">
                        <!-- Table -->
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>

                                    <th class="text-center">ID<br> <a
                                                class="glyphicon glyphicon-chevron-up "
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=id&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down "
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=id&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Name<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=name&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=name&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Adress<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=adress&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=adress&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Zip Code<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=zipcode&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=zipcode&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Town<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=town&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=town&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Country<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=country&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/loadings?page='.$listWarehouses->currentPage().'&sortby=country&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Phone<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=phone&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=phone&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Fax<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=fax&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=fax&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Email<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=email&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=email&order=desc')}}"></a>
                                    </th>
                                    <th class="text-center">Contact Name<br><a
                                                class="glyphicon glyphicon-chevron-up"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=namecontact&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down"
                                                href="{{url('/allWarehouses?page='.$listWarehouses->currentPage().'&sortby=namecontact&order=desc')}}"></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listWarehouses as $warehouse)
                                    <tr class="text-center">
                                        {{--<td><a href="{{route('showDetailsWarehouse',$warehouse->id)}}">{{$warehouse->id}}</a>--}}
                                        {{--</td>--}}
                                        <td>{{$warehouse->id}}</td>
                                        <td>{{$warehouse->name}}</td>
                                        <td>{{$warehouse->adress}}</td>
                                        <td>{{$warehouse->zipcode}}</td>
                                        <td>{{$warehouse->town}}</td>
                                        <td>{{$warehouse->country}}</td>
                                        <td>{{$warehouse->phone}}</td>
                                        <td>{{$warehouse->fax}}</td>
                                        <td>{{$warehouse->email}}</td>
                                        <td>{{$warehouse->namecontact}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row">
                            <div class=" text-left">{!! $listWarehouses->render() !!}</div>
                            {{--->appends($links)--}}
                            @if ($listWarehouses->currentPage()==$listWarehouses->lastPage())
                                <div class="col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listWarehouses->currentPage() -1) * 5)  {{$legend1}}
                                    to {{$count}} of {{$count}} results
                                </div>
                            @else
                                <div class="col-lg-offset-8">
                                    Showing @php($legend1=1+ ($listWarehouses->currentPage() -1) * 5)  {{$legend1}}
                                    to @php($legend2= $listWarehouses->currentPage() * 5) {{$legend2}} of {{$count}}
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