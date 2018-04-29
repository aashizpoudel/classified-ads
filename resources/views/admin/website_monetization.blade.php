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

                {{ Form::open(['route'=>'save_settings','class' => 'form-horizontal', 'files' => true]) }}

                    <h4>@lang('app.website_monetization_help_text')</h4>

                    <hr />

                    <div class="form-group {{ $errors->has('enable_monetize')? 'has-error':'' }}">
                        <label class="col-md-4 control-label">@lang('app.enable_disable') </label>
                        <div class="col-md-8">
                            <label for="enable_monetize" class="checkbox-inline">
                                <input type="checkbox" value="1" id="enable_monetize" name="enable_monetize" {{ get_option('enable_monetize') == 1 ? 'checked="checked"': '' }}>
                                @lang('app.enable_monetize')
                            </label>

                            {!! $errors->has('type')? '<p class="help-block">'.$errors->first('type').'</p>':'' !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">@lang('app.test_adsens_code') </label>
                        <div class="col-sm-8">
<pre>{{ '<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- TestCSP -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8618780985613063"
     data-ad-slot="5484345690"
     data-ad-format="auto"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>' }}</pre>
                        </div>
                    </div>




                    <legend>@lang('app.homepage')</legend>
                    <div class="form-group">
                        <label for="monetize_code_below_slider" class="col-sm-4 control-label">@lang('app.below_slider') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_slider" class="form-control">{{ get_option('monetize_code_below_slider') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="monetize_code_below_search_bar" class="col-sm-4 control-label">@lang('app.below_search_bar') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_search_bar" class="form-control">{{ get_option('monetize_code_below_search_bar') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="monetize_code_below_categories" class="col-sm-4 control-label">@lang('app.below_categories') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_categories" class="form-control">{{ get_option('monetize_code_below_categories') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="monetize_code_below_premium_ads" class="col-sm-4 control-label">@lang('app.below_premium_ads') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_premium_ads" class="form-control">{{ get_option('monetize_code_below_premium_ads') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="monetize_code_below_regular_ads" class="col-sm-4 control-label">@lang('app.below_regular_ads') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_regular_ads" class="form-control">{{ get_option('monetize_code_below_regular_ads') }}</textarea>
                        </div>
                    </div>

                    <legend>@lang('app.listing_page')</legend>

                    <div class="form-group">
                        <label for="monetize_code_listing_sidebar_top" class="col-sm-4 control-label">@lang('app.sidebar_top') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_listing_sidebar_top" class="form-control">{{ get_option('monetize_code_listing_sidebar_top') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="monetize_code_listing_sidebar_bottom" class="col-sm-4 control-label">@lang('app.sidebar_bottom') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_listing_sidebar_bottom" class="form-control">{{ get_option('monetize_code_listing_sidebar_bottom') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="monetize_code_listing_above_premium_ads" class="col-sm-4 control-label">@lang('app.above_premium_ads') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_listing_above_premium_ads" class="form-control">{{ get_option('monetize_code_listing_above_premium_ads') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="monetize_code_listing_above_regular_ads" class="col-sm-4 control-label">@lang('app.above_regular_ads') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_listing_above_regular_ads" class="form-control">{{ get_option('monetize_code_listing_above_regular_ads') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="monetize_code_listing_below_regular_ads" class="col-sm-4 control-label">@lang('app.below_regular_ads') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_listing_below_regular_ads" class="form-control">{{ get_option('monetize_code_listing_below_regular_ads') }}</textarea>
                        </div>
                    </div>


                    <legend>@lang('app.single_ad_page')</legend>

                    <div class="form-group">
                        <label for="monetize_code_below_ad_title" class="col-sm-4 control-label">@lang('app.below_ad_title') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_ad_title" class="form-control">{{ get_option('monetize_code_below_ad_title') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="monetize_code_below_ad_image" class="col-sm-4 control-label">@lang('app.below_ad_image') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_ad_image" class="form-control">{{ get_option('monetize_code_below_ad_image') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="monetize_code_below_ad_description" class="col-sm-4 control-label">@lang('app.below_ad_description') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_ad_description" class="form-control">{{ get_option('monetize_code_below_ad_description') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="monetize_code_above_general_info" class="col-sm-4 control-label">@lang('app.above_general_info') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_above_general_info" class="form-control">{{ get_option('monetize_code_above_general_info') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="monetize_code_below_general_info" class="col-sm-4 control-label">@lang('app.below_general_info') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_general_info" class="form-control">{{ get_option('monetize_code_below_general_info') }}</textarea>
                        </div>
                    </div>




                    <div class="form-group">
                        <label for="monetize_code_above_seller_info" class="col-sm-4 control-label">@lang('app.above_seller_info') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_above_seller_info" class="form-control">{{ get_option('monetize_code_above_seller_info') }}</textarea>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="monetize_code_below_seller_info" class="col-sm-4 control-label">@lang('app.below_seller_info') </label>
                        <div class="col-sm-8">
                            <textarea name="monetize_code_below_seller_info" class="form-control">{{ get_option('monetize_code_below_seller_info') }}</textarea>
                        </div>
                    </div>

                    <hr />
                <div class="form-group">
                    <div class="col-sm-offset-4 col-sm-8">
                        <button type="submit" id="settings_save_btn" class="btn btn-primary">@lang('app.save_settings')</button>
                    </div>
                </div>

                {{ Form::close() }}

            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection


@section('page-js')
    <script>
        $(document).ready(function(){

            $('input[type="checkbox"], input[type="radio"]').click(function(){
                var input_name = $(this).attr('name');
                var input_value = 0;
                if ($(this).prop('checked')){
                    input_value = $(this).val();
                }
                $.ajax({
                    url : '{{ route('save_settings') }}',
                    type: "POST",
                    data: { [input_name]: input_value, '_token': '{{ csrf_token() }}' }
                });
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