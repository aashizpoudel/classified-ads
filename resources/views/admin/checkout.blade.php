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

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">@lang('app.review_your_order') </h4>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-9">
                                    <table class="table table-striped">
                                        <tr>
                                            <td colspan="2">
                                                <b>{{ $payment->ad->title }}</b>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <ul>
                                                    <li>{{ ucfirst($payment->ad->price_plan) }} @if($payment->ad->mark_ad_urgent == 1) + @lang('app.urgent') @endif @lang('app.posting')</li>
                                                </ul>
                                            </td>
                                            <td>
                                                <b>{{ $payment->currency.' '.$payment->amount }}</b>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-3">
                                    <div style="text-align: center;">
                                        <h3>@lang('app.order_total')</h3>
                                        <h3><span style="color:green;">{{ $payment->currency.' '.$payment->amount }}</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xs-4 col-xs-offset-4">

                            @if($payment->payment_method == 'stripe')
                                <div class="stripe-button-container">
                                    <script
                                            src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                            data-key="{{ get_stripe_key() }}"
                                            data-amount="{{ $payment->amount * 100 }}"
                                            data-email="{{ $lUser->email}}"
                                            data-name="{{ get_option('site_name') }}"
                                            data-description="{{ ucfirst($payment->ad->price_plan)." ad posting" }}"
                                            data-currency="{{get_option('currency_sign')}}"
                                            data-image="{{ asset('assets/img/stripe_logo.jpg') }}"
                                            data-locale="auto">
                                    </script>
                                </div>

                            @elseif($payment->payment_method == 'paypal')
                                {{ Form::open() }}
                                <input type="hidden" name="cmd" value="_xclick" />
                                <input type="hidden" name="no_note" value="1" />
                                <input type="hidden" name="lc" value="UK" />
                                <input type="hidden" name="currency_code" value="{{get_option('currency_sign')}}" />
                                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                                <button type="submit" class="btn btn-info"> <i class="fa fa-paypal"></i> Submit Payment</button>
                                {{ Form::close() }}
                            @endif

                        </div>

                    </div>
                </div>

            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->

    </div> <!-- /#container -->
@endsection

@section('page-js')

    @if($payment->payment_method == 'stripe')
        <script>
            $(function() {
                $('.stripe-button').on('token', function(e, token){
                    $('#stripeForm').replaceWith('');

                    $.ajax({
                        url : '{{ route('payment_checkout', $payment->local_transaction_id) }}',
                        type: "POST",
                        data: { stripeToken : token.id, _token : '{{ csrf_token() }}' },
                        success : function (data) {
                            if (data.success == 1){
                                toastr.success(data.msg, '@lang('app.success')', toastr_options);
                            }
                        }
                    });
                });
            });
        </script>
    @endif

@endsection