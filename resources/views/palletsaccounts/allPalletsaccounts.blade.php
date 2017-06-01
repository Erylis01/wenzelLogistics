@extends('layouts.default')

@section('title')
    All pallets accounts
@endsection

@section('stylesheet')
    <link href="{{asset('css/palletsaccounts.css')}}" rel="stylesheet" type="text/css">
@endsection

@section('classLoadings')
    class="nonActive"
@endsection
@section('classWarehouses')
    class="nonActive"
@endsection
@section('classPalletsAccounts')
    class="active"
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
                <div class="panel panel-general col-lg-6 panel-palletsaccounts">
                    <div class="panel-heading">Total of pallets by account</div>

                    <div class="panel-body">
                        @if (Session::has('messageDeletePalletsaccount'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageDeletePalletsaccount') }}</div>
                        @elseif(Session::has('messageAddPalletsaccount'))
                            <div class="alert alert-success text-alert text-center">{{ Session::get('messageAddPalletsaccount') }}</div>
                        @endif

                        <div class="table-responsive table-palletsaccounts">
                            <table class="table table-hover table-bordered">
                                <thead>
                                @if($totalpallets<0)
                                    @php($class="text-alert")
                                @elseif($totalpallets>0)
                                    @php($class="text-warning")
                                @else
                                    @php($class="text-success")
                                @endif
                                <tr>
                                    <th class="text-center colName"><a href="{{route('showTotalPalletsaccounts')}}"
                                                                       class="link">TOTAL</a></th>
                                    <th class="text-center colTotal"><span class={{$class}}>{{$totalpallets}}</span>
                                    </th>
                                </tr>
                                </thead>
                            </table>
                        </div>

                        <!-- Table -->
                        <div class="table-responsive table-palletsaccounts">
                            <table class="table table-hover table-bordered">
                                <thead>
                                <tr>
                                    <th class="text-center colName">Name<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=name&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=name&order=desc')}}"></a></th>
                                    <th class="text-center colTotal">Total<br><a
                                                class="glyphicon glyphicon-chevron-up general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=numberPallets&order=asc')}}"></a><a
                                                class="glyphicon glyphicon-chevron-down general-sorting"
                                                href="{{url('/allPalletsaccounts?sortby=numberPallets&order=desc')}}"></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($listPalletsaccounts as $palletsaccount)
                                    <tr>
                                        <td class="text-center colName"><a
                                                    href="#{{str_replace(' ', '', $palletsaccount->name)}}-collapse"
                                                    data-toggle="collapse"
                                                    class="link">{{$palletsaccount->name}}</a></td>
                                        <td class="text-center colTotal">{{$palletsaccount->numberPallets}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="col-lg-offset-2 col-lg-8">
                            <a href="{{route('showAddPalletsaccount')}}" class="btn btn-add btn-block"><span
                                        class="glyphicon glyphicon-plus-sign"></span> Add account</a>
                        </div>
                    </div>
                </div>

                @foreach($listPalletsaccounts as $palletsaccount)
                    <div id="{{str_replace(' ', '', $palletsaccount->name)}}-collapse"
                         class="panel panel-general col-lg-8 col-lg-offset-1 panel-palletsaccounts-details collapse">
                        <div class="panel-heading">Account n° {{$palletsaccount->id}} : {{$palletsaccount->name}}</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="">
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <label for="numberPallets" class="control-label legend-palletsaccounts">Number
                                            of pallets :</label>
                                    </div>
                                    <div class="col-lg-6">
                                        <input id="numberPallets" type="number"
                                               class="form-control info-palletsaccounts" name="numberPallets"
                                               value="" placeholder="Pallets number" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-lg-5">
                                        <label for="warehousesAssociated" class="control-label legend-palletsaccounts">Warehouses
                                            associated :</label>
                                    </div>
                                    <div class="col-lg-6 info-palletsaccounts">
                                        <ul>
                                            <li>lalal</li>
                                        </ul>
                                    </div>
                                    {{--id="warehousesAssociated" type="text" class="form-control" name="warehousesAssociated"--}}
                                    {{--value="" placeholder="Warehouses associated" readonly>--}}
                                </div>

                                <div class="form-group">
                                    <div class="col-lg-offset-5 col-lg-6">
                                        <a href="{{route('showDetailsPalletsaccount', $palletsaccount->id)}}"
                                           class="btn btn-form btn-block">Details pallets transferts</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection