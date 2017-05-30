@extends('layouts.default')

@section('title')
    Details pallets transferts
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

                <div class="col-lg-14">
                    @if($totalpallets>0)
                        <div class="panel panel-warning">
                            @elseif($totalpallets=0)
                                <div class="panel panel-general">
                            @else
                                <div class="panel panel-danger">
                                    @endif
                    <div class="panel-heading">Details of all the pallets transferts<span class="col-lg-offset-6">{{$totalpallets}} pallets</span></div>
                    <div class="panel-body panel-body-general">
                        <div class="table-responsive ">
                            <table class="table table-hover table-bordered table-details-palletsaccount">
                                <thead>
                                {{--<tr>--}}
                                    {{--<th class="text-center">Reference Loading<a--}}
                                                {{--class="glyphicon glyphicon-chevron-up loadings-sorting"--}}
                                                {{--href="{{url('/totalPalletsaccounts?page='.$listLoadings->currentPage().'&sortby=loadingReference&order=asc')}}"></a><a--}}
                                                {{--class="glyphicon glyphicon-chevron-down loadings-sorting"--}}
                                                {{--href="{{url('/totalPalletsaccounts?page='.$listLoadings->currentPage().'&sortby=loadingReference&order=desc')}}"></a></th>--}}
                                    {{--<th class="text-center">Date Loading<a--}}
                                                {{--class="glyphicon glyphicon-chevron-up loadings-sorting"--}}
                                                {{--href="{{url('/totalPalletsaccounts?page='.$listLoadings->currentPage().'&sortby=loadingDate&order=asc')}}"></a><a--}}
                                                {{--class="glyphicon glyphicon-chevron-down loadings-sorting"--}}
                                                {{--href="{{url('/totalPalletsaccounts?page='.$listLoadings->currentPage().'&sortby=loadingDate&order=desc')}}"></a></th>--}}
                                    {{--<th class="text-center">Name Pallets account<a--}}
                                            {{--class="glyphicon glyphicon-chevron-up loadings-sorting"--}}
                                            {{--href="{{url('/totalPalletsaccounts?page='.$listLoadings->currentPage().'&sortby=nameAccount&order=asc')}}"></a><a--}}
                                            {{--class="glyphicon glyphicon-chevron-down loadings-sorting"--}}
                                            {{--href="{{url('/totalPalletsaccounts?page='.$listLoadings->currentPage().'&sortby=nameAccount&order=desc')}}"></a></th>--}}
                                    {{--<th class="text-center">Pallets Number<a--}}
                                                {{--class="glyphicon glyphicon-chevron-up loadings-sorting"--}}
                                                {{--href="{{url('/totalPalletsaccounts?page='.$listLoadings->currentPage().'&sortby=numberPallets&order=asc')}}"></a><a--}}
                                                {{--class="glyphicon glyphicon-chevron-down loadings-sorting"--}}
                                                {{--href="{{url('/totalPalletsaccounts?page='.$listLoadings->currentPage().'&sortby=numberPallets&order=desc')}}"></th>--}}
                                {{--</tr>--}}
                                <tr>
                                    <th class="text-center">Reference Loading</th>
                                    <th class="text-center">Date Loading</th>
                                    <th class="text-center">Name Pallets account</th>
                                    <th class="text-center">Pallets Number</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--@foreach($warehouse->loadings as $loading)--}}
                                <tr>
                                    <td class="text-center">reference1</td>
                                    <td class="text-center">date1</td>
                                    <td class="text-center">account1</td>
                                    <td class="text-center">number1</td>
                                </tr>

                                {{--@endforeach--}}
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection