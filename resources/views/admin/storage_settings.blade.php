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
                        <div class="form-group {{ $errors->has('default_storage')? 'has-error':'' }}">
                            <label for="default_storage" class="col-sm-4 control-label">@lang('app.default_storage')</label>
                            <div class="col-sm-8">
                                <label>
                                    <input type="radio" name="default_storage" value="public" {{ get_option('default_storage') == 'public'? 'checked' :'' }} /> @lang('app.local_server') <small class="text-info"> (@lang('app.local_server_help_text')) </small>
                                </label> <br />
                                <label>
                                    <input type="radio" name="default_storage" value="s3" {{ get_option('default_storage') == 's3'? 'checked' :'' }} /> @lang('app.amazon_s3') <small class="text-info"> (@lang('app.amazon_s3_help_text')) </small>
                                </label>

                            </div>
                        </div>


                        <div class="amazon_s3_settings_wrap" style="display: {{ get_option('default_storage') == 's3' ? 'block':'none' }};">

                            <hr />
                            <div class="form-group">
                                <label for="amazon_key" class="col-sm-4 control-label">@lang('app.amazon_key')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="amazon_key" value="{{ get_option('amazon_key') }}" name="amazon_key" placeholder="@lang('app.amazon_key')">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="amazon_secret" class="col-sm-4 control-label">@lang('app.amazon_secret')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="amazon_secret" value="{{ get_option('amazon_secret') }}" name="amazon_secret" placeholder="@lang('app.amazon_secret')">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="amazon_region" class="col-sm-4 control-label">@lang('app.amazon_region')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="amazon_region" value="{{ get_option('amazon_region') }}" name="amazon_region" placeholder="@lang('app.amazon_region')">
                                    <a href="http://docs.aws.amazon.com/general/latest/gr/rande.html" target="_blank">@lang('app.amazon_region_help')</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bucket" class="col-sm-4 control-label">@lang('app.bucket')</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="bucket" value="{{ get_option('bucket') }}" name="bucket" placeholder="@lang('app.bucket')">
                                </div>
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
                    data: { [input_name]: input_value, '_token': '{{ csrf_token() }}' },
                });
            });


            $('input[name="default_storage"]').click(function(){
                var default_storage = $(this).val();

                if (default_storage == 's3'){
                    $('.amazon_s3_settings_wrap').slideDown('slow');
                } else {
                    $('.amazon_s3_settings_wrap').slideUp();
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