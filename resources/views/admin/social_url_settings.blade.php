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

                    <div class="form-group">
                        <label for="facebook_url" class="col-sm-4 control-label">@lang('app.facebook_url') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="facebook_url" value="{{ get_option('facebook_url') }}" name="facebook_url" placeholder="@lang('app.facebook_url')">
                        </div>
                    </div>


                    <div class="form-group">
                        <label for="twitter_url" class="col-sm-4 control-label">@lang('app.twitter_url') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="twitter_url" value="{{ get_option('twitter_url') }}" name="twitter_url" placeholder="@lang('app.twitter_url')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="linked_in_url" class="col-sm-4 control-label">@lang('app.linked_in_url') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="linked_in_url" value="{{ get_option('linked_in_url') }}" name="linked_in_url" placeholder="@lang('app.linked_in_url')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="dribble_url" class="col-sm-4 control-label">@lang('app.dribble_url') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="dribble_url" value="{{ get_option('dribble_url') }}" name="dribble_url" placeholder="@lang('app.dribble_url')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="google_plus_url" class="col-sm-4 control-label">@lang('app.google_plus_url') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="google_plus_url" value="{{ get_option('google_plus_url') }}" name="google_plus_url" placeholder="@lang('app.google_plus_url')">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="youtube_url" class="col-sm-4 control-label">@lang('app.youtube_url') </label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="youtube_url" value="{{ get_option('youtube_url') }}" name="youtube_url" placeholder="@lang('app.youtube_url')">
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