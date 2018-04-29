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

                <div class="row">
                    <div class="col-md-10 col-xs-12">

                        {{ Form::open(['route'=>'save_settings','class' => 'form-horizontal', 'files' => true]) }}
                        <div class="form-group {{ $errors->has('ads_moderation')? 'has-error':'' }}">
                            <label for="ads_moderation" class="col-sm-4 control-label">@lang('app.ads_moderation')</label>
                            <div class="col-sm-8">

                                <label>
                                    <input type="radio" name="ads_moderation" value="need_moderation" {{ get_option('ads_moderation') == 'need_moderation'? 'checked' :'' }} /> @lang('app.ads_approval_pending_text') <small class="text-success"> (@lang('app.recommended')) </small>
                                </label> <br />
                                <label>
                                    <input type="radio" name="ads_moderation" value="direct_publish" {{ get_option('ads_price_plan') == 'direct_publish'? 'checked' :'' }} /> @lang('app.ads_approval_direct_text') <small class="text-danger"> (@lang('app.its_risky')) </small>
                                </label>

                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('ads_price_plan')? 'has-error':'' }}">
                            <label for="ads_price_plan" class="col-sm-4 control-label">@lang('app.ads_price_plan')</label>
                            <div class="col-sm-8">

                                <label>
                                    <input type="radio" name="ads_price_plan" value="regular_ads_free_premium_paid" {{ get_option('ads_price_plan') == 'regular_ads_free_premium_paid'? 'checked' :'' }} /> @lang('app.regular_ads_free_premium_paid')
                                </label>
                                <label>
                                    <input type="radio" name="ads_price_plan" value="all_ads_paid" {{ get_option('ads_price_plan') == 'all_ads_paid'? 'checked' :'' }} /> @lang('app.all_ads_paid')
                                </label>

                                <label>
                                    <input type="radio" name="ads_price_plan" value="all_ads_free" {{ get_option('ads_price_plan') == 'all_ads_free'? 'checked' :'' }} /> @lang('app.all_ads_free')
                                </label>

                                {!! $errors->has('ads_price_plan')? '<p class="help-block">'.$errors->first('site_title').'</p>':'' !!}
                                <p class="text-info"> @lang('app.ads_price_plan_help_text')</p>

                            </div>
                        </div>



                        <div class="set_pricing_wrap" style="display: {{ get_option('ads_price_plan') == 'all_ads_free' ? 'none':'block' }};">

                            <div id="regular_ads_price_wrap" class="form-group {{ $errors->has('regular_ads_price')? 'has-error':'' }}" style="display: {{ get_option('ads_price_plan') == 'regular_ads_free_premium_paid' ? 'none':'block' }};">
                                <label for="regular_ads_price" class="col-sm-4 control-label">@lang('app.regular_ads_price')</label>
                                <div class="col-sm-8">
                                    <input type="number" min="1" class="form-control" id="regular_ads_price" value="{{ get_option('regular_ads_price') }}" name="regular_ads_price" placeholder="@lang('app.regular_ads_price')">
                                    {!! $errors->has('regular_ads_price')? '<p class="help-block">'.$errors->first('regular_ads_price').'</p>':'' !!}
                                    <p class="text-info"> @lang('app.regular_ads_price_help_text')</p>
                                </div>
                            </div>

                            <div id="premium_ads_price_wrap" class="form-group {{ $errors->has('premium_ads_price')? 'has-error':'' }}">
                                <label for="premium_ads_price" class="col-sm-4 control-label">@lang('app.premium_ads_price')</label>
                                <div class="col-sm-8">
                                    <input type="number" min="1"  class="form-control"  id="premium_ads_price" value="{{ get_option('premium_ads_price') }}" name="premium_ads_price" placeholder="@lang('app.premium_ads_price')">
                                    {!! $errors->has('premium_ads_price')? '<p class="help-block">'.$errors->first('premium_ads_price').'</p>':'' !!}
                                    <p class="text-info"> @lang('app.premium_ads_price_help_text')</p>
                                </div>
                            </div>

                            <div id="urgent_ads_price_wrap" class="form-group {{ $errors->has('urgent_ads_price')? 'has-error':'' }}">
                                <label for="urgent_ads_price" class="col-sm-4 control-label">@lang('app.urgent_ads_price')</label>
                                <div class="col-sm-8">
                                    <input type="number" min="1"  class="form-control"  id="urgent_ads_price" value="{{ get_option('urgent_ads_price') }}" name="urgent_ads_price" placeholder="@lang('app.urgent_ads_price')">
                                    {!! $errors->has('urgent_ads_price')? '<p class="help-block">'.$errors->first('urgent_ads_price').'</p>':'' !!}
                                    <p class="text-info"> @lang('app.urgent_ads_price_help_text')</p>
                                </div>
                            </div>

                        </div>


                        <hr />

                        <div class="form-group {{ $errors->has('number_of_urgent_ads_in_home')? 'has-error':'' }}">
                            <label for="number_of_urgent_ads_in_home" class="col-sm-4 control-label">@lang('app.number_of_urgent_ads_in_home')</label>
                            <div class="col-sm-8">
                                <input type="number" min="1"  class="form-control" id="number_of_urgent_ads_in_home" value="{{ get_option('number_of_urgent_ads_in_home') }}" name="number_of_urgent_ads_in_home" placeholder="@lang('app.number_of_urgent_ads_in_home')">
                                {!! $errors->has('number_of_urgent_ads_in_home')? '<p class="help-block">'.$errors->first('number_of_urgent_ads_in_home').'</p>':'' !!}
                                <p class="text-info"> @lang('app.number_of_urgent_ads_in_home_help_text')</p>
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('number_of_premium_ads_in_home')? 'has-error':'' }}">
                            <label for="number_of_premium_ads_in_home" class="col-sm-4 control-label">@lang('app.number_of_premium_ads_in_home')</label>
                            <div class="col-sm-8">
                                <input type="number" min="1"  class="form-control" id="number_of_premium_ads_in_home" value="{{ get_option('number_of_premium_ads_in_home') }}" name="number_of_premium_ads_in_home" placeholder="@lang('app.number_of_premium_ads_in_home')">
                                {!! $errors->has('number_of_premium_ads_in_home')? '<p class="help-block">'.$errors->first('number_of_premium_ads_in_home').'</p>':'' !!}
                                <p class="text-info"> @lang('app.number_of_premium_ads_in_home_help_text')</p>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('number_of_free_ads_in_home')? 'has-error':'' }}">
                            <label for="number_of_free_ads_in_home" class="col-sm-4 control-label">@lang('app.number_of_free_ads_in_home')</label>
                            <div class="col-sm-8">
                                <input type="number" min="1"  class="form-control" id="number_of_free_ads_in_home" value="{{ get_option('number_of_free_ads_in_home') }}" name="number_of_free_ads_in_home" placeholder="@lang('app.number_of_free_ads_in_home')">
                                {!! $errors->has('number_of_free_ads_in_home')? '<p class="help-block">'.$errors->first('number_of_free_ads_in_home').'</p>':'' !!}
                                <p class="text-info"> @lang('app.number_of_free_ads_in_home_help_text')</p>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('ads_per_page')? 'has-error':'' }}">
                            <label for="ads_per_page" class="col-sm-4 control-label">@lang('app.ads_per_page')</label>
                            <div class="col-sm-8">
                                <input type="number" min="1"  class="form-control" id="ads_per_page" value="{{ get_option('ads_per_page') }}" name="ads_per_page" placeholder="@lang('app.ads_per_page')">
                                {!! $errors->has('ads_per_page')? '<p class="help-block">'.$errors->first('ads_per_page').'</p>':'' !!}
                                <p class="text-info"> @lang('app.ads_per_page_help_text')</p>
                            </div>
                        </div>


                        <hr />
                        <div class="form-group {{ $errors->has('number_of_premium_ads_in_listing')? 'has-error':'' }}">
                            <label for="number_of_premium_ads_in_listing" class="col-sm-4 control-label">@lang('app.number_of_premium_ads_in_listing')</label>
                            <div class="col-sm-8">
                                <input type="number" min="1"  class="form-control" id="number_of_premium_ads_in_listing" value="{{ get_option('number_of_premium_ads_in_listing') }}" name="number_of_premium_ads_in_listing" placeholder="@lang('app.number_of_premium_ads_in_listing')">
                                <p class="text-info"> @lang('app.number_of_premium_ads_in_listing_help_text')</p>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('premium_ads_max_impressions')? 'has-error':'' }}">
                            <label for="premium_ads_max_impressions" class="col-sm-4 control-label">@lang('app.premium_ads_max_impressions')</label>
                            <div class="col-sm-8">
                                <input type="number" min="1"  class="form-control" id="premium_ads_max_impressions" value="{{ get_option('premium_ads_max_impressions') }}" name="premium_ads_max_impressions" placeholder="@lang('app.premium_ads_max_impressions')">
                                {!! $errors->has('premium_ads_max_impressions')? '<p class="help-block">'.$errors->first('premium_ads_max_impressions').'</p>':'' !!}
                                <p class="text-info"> @lang('app.premium_ads_max_impressions_help_text')</p>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('number_of_last_days_premium_ads')? 'has-error':'' }}">
                            <label for="number_of_last_days_premium_ads" class="col-sm-4 control-label">@lang('app.number_of_last_days_premium_ads')</label>
                            <div class="col-sm-8">
                                <input type="number" min="1"  class="form-control" id="number_of_last_days_premium_ads" value="{{ get_option('number_of_last_days_premium_ads') }}" name="number_of_last_days_premium_ads" placeholder="@lang('app.number_of_last_days_premium_ads')">
                                <p class="text-info"> @lang('app.number_of_last_days_premium_ads_help_text')</p>
                            </div>
                        </div>



                        <div class="form-group {{ $errors->has('order_by_premium_ads_in_listing')? 'has-error':'' }}">
                            <label class="col-sm-4 control-label">@lang('app.order_by_premium_ads_in_listing_help_text')</label>
                            <div class="col-sm-8">

                                <label>
                                    <input type="radio" name="order_by_premium_ads_in_listing" value="latest" {{ get_option('order_by_premium_ads_in_listing') == 'latest'? 'checked' :'' }} /> @lang('app.latest')
                                </label>
                                <label>
                                    <input type="radio" name="order_by_premium_ads_in_listing" value="random" {{ get_option('order_by_premium_ads_in_listing') == 'random'? 'checked' :'' }} /> @lang('app.random')
                                </label>

                                <p class="text-info"> @lang('app.order_by_premium_ads_in_listing_help_text')</p>
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('enable_related_ads')? 'has-error':'' }}">
                            <label class="col-md-4 control-label">@lang('app.enable_disable') </label>
                            <div class="col-md-8">
                                <label for="enable_related_ads" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="enable_related_ads" name="enable_related_ads" {{ get_option('enable_related_ads') == 1 ? 'checked="checked"': '' }}>
                                    @lang('app.related_ads')
                                </label>

                                {!! $errors->has('type')? '<p class="help-block">'.$errors->first('type').'</p>':'' !!}
                            </div>
                        </div>


                        <hr />

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" id="settings_save_btn" class="btn btn-primary">@lang('app.save_settings')</button>
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>

            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection


@section('page-js')
    <script>
        $(function(){

            $('input[type="checkbox"], input[type="radio"]').click(function(){
                var input_name = $(this).attr('name');
                var input_value = 0;
                if ($(this).prop('checked')){
                    input_value = $(this).val();
                }
                $.ajax({
                    url : '{{ route('save_settings') }}',
                    type: "POST",
                    data: { [input_name]: input_value, '_token': '{{ csrf_token() }}' },
                });
            });


            $('input[name="ads_price_plan"]').click(function(){
                var price_plan = $(this).val();

                if (price_plan == 'all_ads_paid'){
                    $('.set_pricing_wrap').slideDown('slow');
                    $('#regular_ads_price_wrap').show();
                }else if(price_plan == 'regular_ads_free_premium_paid'){
                    $('#regular_ads_price_wrap').hide();
                    $('.set_pricing_wrap').slideDown('slow');
                } else {
                    $('.set_pricing_wrap').slideUp();
                }

            });

            /**
             * Send settings option value to server
             */
            $('#settings_save_btn').click(function(e){
                e.preventDefault();

                var this_btn = $(this);
                this_btn.attr('disabled', 'disabled');

                var form_data = this_btn.closest('form').serialize();
                $.ajax({
                    url : '{{ route('save_settings') }}',
                    type: "POST",
                    data: form_data,
                    success : function (data) {
                        if (data.success == 1){
                            this_btn.removeAttr('disabled');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });

        });
    </script>
@endsection