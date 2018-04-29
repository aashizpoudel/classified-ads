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


                    <div class="form-group {{ $errors->has('show_blog_in_header')? 'has-error':'' }}">
                        <label class="col-md-4 control-label">@lang('app.show_hide') </label>
                        <div class="col-md-8">
                            <label for="show_blog_in_header" class="checkbox-inline">
                                <input type="checkbox" value="1" id="show_blog_in_header" name="show_blog_in_header" {{ get_option('show_blog_in_header') == 1 ? 'checked="checked"': '' }}>
                                @lang('app.show_blog_in_header')
                            </label>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('show_blog_in_footer')? 'has-error':'' }}">
                        <label class="col-md-4 control-label">@lang('app.show_hide') </label>
                        <div class="col-md-8">
                            <label for="show_blog_in_footer" class="checkbox-inline">
                                <input type="checkbox" value="1" id="show_blog_in_footer" name="show_blog_in_footer" {{ get_option('show_blog_in_footer') == 1 ? 'checked="checked"': '' }}>
                                @lang('app.show_blog_in_footer')
                            </label>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('show_latest_blog_in_homepage')? 'has-error':'' }}">
                        <label class="col-md-4 control-label">@lang('app.show_hide') </label>
                        <div class="col-md-8">
                            <label for="show_latest_blog_in_homepage" class="checkbox-inline">
                                <input type="checkbox" value="1" id="show_latest_blog_in_homepage" name="show_latest_blog_in_homepage" {{ get_option('show_latest_blog_in_homepage') == 1 ? 'checked="checked"': '' }}>
                                @lang('app.show_latest_blog_in_homepage')
                            </label>
                        </div>
                    </div>


                    <div class="form-group {{ $errors->has('enable_disqus_comment_in_blog')? 'has-error':'' }}">
                        <label class="col-md-4 control-label">@lang('app.enable_disable') </label>
                        <div class="col-md-8">
                            <label for="enable_disqus_comment_in_blog" class="checkbox-inline">
                                <input type="checkbox" value="1" id="enable_disqus_comment_in_blog" name="enable_disqus_comment_in_blog" {{ get_option('enable_disqus_comment_in_blog') == 1 ? 'checked="checked"': '' }}>
                                @lang('app.enable_disqus_comment_in_blog')
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="blog_post_amount_in_homepage" class="col-sm-4 control-label">@lang('app.blog_post_amount_in_homepage')</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="blog_post_amount_in_homepage" value="{{ get_option('blog_post_amount_in_homepage') }}" name="blog_post_amount_in_homepage" placeholder="@lang('app.blog_post_amount_in_homepage')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="disqus_shortname" class="col-sm-4 control-label">@lang('app.disqus_shortname')</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="disqus_shortname" value="{{ get_option('disqus_shortname') }}" name="disqus_shortname" placeholder="@lang('app.disqus_shortname')">
                            <p class="text-info">@lang('app.disqus_shortname_help_text') </p>
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