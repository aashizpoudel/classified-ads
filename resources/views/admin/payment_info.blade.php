@extends('layout.main')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('main')

    <div class="container">

        <div id="wrapper">

            @include('admin.sidebar_menu')

            <div id="page-wrapper">
                @if( ! empty($title))
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header"> {{ $title }}  </h1>
                        </div> <!-- /.col-lg-12 -->
                    </div> <!-- /.row -->
                @endif

                @include('admin.flash_msg')


                <div class="row">
                    <div class="col-xs-12">

                        <table class="table table-bordered table-striped">

                            <tr>
                                <th>@lang('app.ad')</th>
                                <td> @if($payment->ad){{ $payment->ad->title }} @endif</td>
                            </tr>
                            <tr>
                                <th>@lang('app.user_name')</th>
                                <td>@if($payment->user){{ $payment->user->name }} @endif</td>
                            </tr>


                            <tr>
                                <th>@lang('app.amount')</th>
                                <td>{{ $payment->amount }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.payment_method')</th>
                                <td>{{ $payment->payment_method }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.status')</th>
                                <td>{{ $payment->status }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.currency')</th>
                                <td>{{ $payment->currency }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.charge_id_or_token')</th>
                                <td>{{ $payment->status }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.payer_email')</th>
                                <td>{{ $payment->payer_email }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.description')</th>
                                <td>{{ $payment->description }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.transaction_id')</th>
                                <td>{{ $payment->local_transaction_id }}</td>
                            </tr>
                            <tr>
                                <th>@lang('app.payment_completed_at')</th>
                                <td>{{ $payment->payment_completed_at() }}</td>
                            </tr>



                        </table>

                    </div>
                </div>


            </div>   <!-- /#page-wrapper -->




        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection

@section('page-js')

@endsection