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

                    <h4>@lang('app.variable_info')</h4>
                    <pre>[year], [copyright_sign], [site_name]</pre>
                    <hr />

                    <div class="form-group">
                        <label for="default_theme" class="col-sm-4 control-label">
                            @lang('app.default_theme')
                        </label>
                        <div class="col-sm-8 {{ $errors->has('default_theme')? 'has-error':'' }}">
                            <select class="form-control select2" name="default_theme" id="default_theme">
                                <option value="classic" {{ get_option('default_theme') == 'classic' ? 'selected':'' }}>Classic</option>
                                <option value="modern" {{ get_option('default_theme') == 'modern' ? 'selected':'' }}>Modern</option>
                            </select>

                            {!! $errors->has('default_theme')? '<p class="help-block">'.$errors->first('default_theme').'</p>':'' !!}
                            <p class="text-info">@lang('app.default_theme_help_text')</p>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="default_style" class="col-sm-4 control-label">
                            @lang('app.default_style')
                        </label>
                        <div class="col-sm-8 {{ $errors->has('default_style')? 'has-error':'' }}">
                            <select class="form-control select2" name="default_style" id="default_style">
                                <option value="default" {{ get_option('default_style') == 'default' ? 'selected':'' }}>Default</option>
                                <option value="green" {{ get_option('default_style') == 'green' ? 'selected':'' }}>Green</option>
                                <option value="blue" {{ get_option('default_style') == 'blue' ? 'selected':'' }}>Blue</option>
                                <option value="gray" {{ get_option('default_style') == 'gray' ? 'selected':'' }}>Gray</option>
                            </select>

                            {!! $errors->has('default_style')? '<p class="help-block">'.$errors->first('default_style').'</p>':'' !!}
                            <p class="text-info">@lang('app.default_style_help_text')</p>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="additional_css" class="col-sm-4 control-label">@lang('app.additional_css') </label>
                        <div class="col-sm-8">
                            <textarea name="additional_css" class="form-control">{{ get_option('additional_css') }}</textarea>
                            <p class="text-info">@lang('app.additional_css_help_text')</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="additional_js" class="col-sm-4 control-label">@lang('app.additional_js') </label>
                        <div class="col-sm-8">
                            <textarea name="additional_js" class="form-control">{{ get_option('additional_js') }}</textarea>
                            <p class="text-info">@lang('app.additional_js_help_text')</p>
                        </div>
                    </div>


                    <legend>@lang('app.contact_us_page')</legend>

                    <div class="form-group">
                        <label for="google_map_embedded_code" class="col-sm-4 control-label">@lang('app.google_map_embedded_code') </label>
                        <div class="col-sm-8">
                            <textarea name="google_map_embedded_code" class="form-control">{{ get_option('google_map_embedded_code') }}</textarea>
                            <a href="https://support.google.com/maps/answer/144361" target="_blank">@lang('app.google_map_embedded_code_help_text')</a>
                        </div>
                    </div>

                    <legend>@lang('app.footer')</legend>

                    <div class="form-group">
                        <label for="footer_about_us" class="col-sm-4 control-label">@lang('app.footer_about_us') </label>
                        <div class="col-sm-8">
                            <textarea name="footer_about_us" class="form-control">{{ get_option('footer_about_us') }}</textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="footer_about_us_read_more_text" class="col-sm-4 control-label">@lang('app.footer_about_us_read_more_text') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="footer_about_us_read_more_text" value="{{ get_option('footer_about_us_read_more_text') }}" name="footer_about_us_read_more_text" placeholder="@lang('app.footer_about_us_read_more_text')">
                            <p class="text-info">@lang('app.footer_about_us_read_more_text')</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="footer_address" class="col-sm-4 control-label">@lang('app.footer_address') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="footer_address" value="{{ get_option('footer_address') }}" name="footer_address" placeholder="@lang('app.footer_address')">
                            <p class="text-info">@lang('app.footer_address_help_text')</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_phone_number" class="col-sm-4 control-label">@lang('app.site_phone_number') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="site_phone_number" value="{{ get_option('site_phone_number') }}" name="site_phone_number" placeholder="@lang('app.site_phone_number')">
                            <p class="text-info">@lang('app.site_phone_number_help_text')</p>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="site_email_address" class="col-sm-4 control-label">@lang('app.site_email_address') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="site_email_address" value="{{ get_option('site_email_address') }}" name="site_email_address" placeholder="@lang('app.site_email_address')">
                            <p class="text-info">@lang('app.site_email_address_help_text')</p>

                        </div>
                    </div>

                    <div class="form-group">
                        <label for="footer_company_name" class="col-sm-4 control-label">@lang('app.footer_company_name') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="footer_company_name" value="{{ get_option('footer_company_name') }}" name="footer_company_name" placeholder="@lang('app.footer_company_name')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="footer_left_text" class="col-sm-4 control-label">@lang('app.footer_left_text') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="footer_left_text" value="{{ get_option('footer_left_text') }}" name="footer_left_text" placeholder="@lang('app.footer_left_text')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="footer_right_text" class="col-sm-4 control-label">@lang('app.footer_right_text') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="footer_right_text" value="{{ get_option('footer_right_text') }}" name="footer_right_text" placeholder="@lang('app.footer_right_text')">
                        </div>
                    </div>



                    <legend>@lang('app.meta_tags_and_content')</legend>

                    <div class="form-group">
                        <label for="meta_title" class="col-sm-4 control-label">@lang('app.meta_title') </label>
                        <div class="col-sm-8">
                            <pre>{{ get_option('site_title') }}</pre>
                            <p class="text-info">@lang('app.meta_title_help_text')</p>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="meta_description" class="col-sm-4 control-label">@lang('app.meta_description') </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" name="meta_description" id="meta_description">{{get_option('meta_description')}}</textarea>
                            <p class="text-info">@lang('app.meta_description_help_text')</p>
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
             * show or hide stripe and paypal settings wrap
             */
            $('#enable_facebook_login').click(function(){
                if ($(this).prop('checked')){
                    $('#facebook_login_api_wrap').slideDown();
                }else{
                    $('#facebook_login_api_wrap').slideUp();
                }
            });
            $('#enable_google_login').click(function(){
                if ($(this).prop('checked')){
                    $('#google_login_api_wrap').slideDown();
                }else{
                    $('#google_login_api_wrap').slideUp();
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