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


                    <div class="alert alert-warning">
                        @lang('app.modern_settings_warning')
                    </div>

                    <div class="form-group">
                        <label for="modern_category_display_style" class="col-sm-4 control-label">
                            @lang('app.modern_category_display_style')
                        </label>
                        <div class="col-sm-8 {{ $errors->has('modern_category_display_style')? 'has-error':'' }}">
                            <select class="form-control select2" name="modern_category_display_style" id="modern_category_display_style">
                                <option value="show_top_category" {{ get_option('modern_category_display_style') == 'show_top_category' ? 'selected':'' }}>@lang('app.show_only_top_category')</option>
                                <option value="show_top_category_with_sub" {{ get_option('modern_category_display_style') == 'show_top_category_with_sub' ? 'selected':'' }}>@lang('app.show_top_category_with_sub')</option>
                            </select>

                            {!! $errors->has('modern_category_display_style')? '<p class="help-block">'.$errors->first('modern_category_display_style').'</p>':'' !!}
                            <p class="text-info">@lang('app.modern_category_display_style_help_text')</p>
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="modern_home_left_title" class="col-sm-4 control-label">@lang('app.modern_home_left_title') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="modern_home_left_title" value="{{ get_option('modern_home_left_title') }}" name="modern_home_left_title" placeholder="@lang('app.modern_home_left_title')">
                            <p class="text-info">@lang('app.modern_home_left_title_help_text')</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="modern_home_left_content" class="col-sm-4 control-label">@lang('app.modern_home_left_content') </label>
                        <div class="col-sm-8">
                            <textarea name="modern_home_left_content" class="form-control">{{ get_option('modern_home_left_content') }}</textarea>
                            <p class="text-info">@lang('app.modern_home_left_content_help_text')</p>

                        </div>
                    </div>




                    <div class="form-group">
                        <label for="modern_home_right_title" class="col-sm-4 control-label">@lang('app.modern_home_right_title') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="modern_home_right_title" value="{{ get_option('modern_home_right_title') }}" name="modern_home_right_title" placeholder="@lang('app.modern_home_right_title')">
                            <p class="text-info">@lang('app.modern_home_right_title_help_text')</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="modern_home_right_content" class="col-sm-4 control-label">@lang('app.modern_home_right_content') </label>
                        <div class="col-sm-8">
                            <textarea name="modern_home_right_content" class="form-control">{{ get_option('modern_home_right_content') }}</textarea>
                            <p class="text-info">@lang('app.modern_home_right_content_help_text')</p>

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