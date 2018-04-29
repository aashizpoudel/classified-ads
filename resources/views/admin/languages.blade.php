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
                        <div class="well well-sm">
                            <h4>@lang('app.language_documentation')</h4>
                            <p>Add a language by filling and submit below form. Language name will show every where need a language, such as in language switcher, and language code will be work in background and it will be hit url for make request to application to change that language. You have to make a directory in <code>{{ resource_path('lang') }}</code> directory, name will be same as language code. example: if your language code is <b>en</b>, so directory name also will be <b>en</b>, although en is default. you can't delete or edit this language. copy <code>{{ resource_path('lang') }}/en/app.php</code> file to your newly created directory and change all array value. <br /> Example: <code>'login'  => 'Changeable String',</code> </p>
                        </div>
                    </div>


                    <div class="col-sm-8 col-sm-offset-1 col-xs-12">

                        {{ Form::open(['class' => 'form-horizontal']) }}


                        <div class="form-group {{ $errors->has('enable_language_switcher')? 'has-error':'' }}">
                            <label class="col-md-4 control-label">@lang('app.enable_disable') </label>
                            <div class="col-md-8">
                                <label for="enable_language_switcher" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="enable_language_switcher" name="enable_language_switcher" {{ get_option('enable_language_switcher') == 1 ? 'checked="checked"': '' }}>
                                    @lang('app.enable_language_switcher')
                                </label>

                                {!! $errors->has('type')? '<p class="help-block">'.$errors->first('type').'</p>':'' !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('language_name')? 'has-error':'' }}">
                            <label for="language_name" class="col-sm-4 control-label">@lang('app.language_name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="language_name" value="{{ old('language_name') }}" name="language_name" placeholder="Category Name">
                                {!! $errors->has('language_name')? '<p class="help-block">'.$errors->first('language_name').'</p>':'' !!}

                            </div>
                        </div>
                        <div class="form-group {{ $errors->has('language_code')? 'has-error':'' }}">
                            <label for="language_code" class="col-sm-4 control-label">@lang('app.language_code')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="language_code" value="{{ old('language_code') }}" name="language_code" placeholder="@lang('app.language_code')">
                                {!! $errors->has('language_code')? '<p class="help-block">'.$errors->first('language_code').'</p>':'' !!}
                                <p class="text-info">@lang('app.language_code_help_text')</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">@lang('app.save_new_language')</button>
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>

                </div>


                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-bordered">
                            <tr>
                                <th>@lang('app.language_name') </th>
                                <th>@lang('app.language_code') </th>
                                <th>@lang('app.action') </th>
                            </tr>


                            @foreach($languages as $language)
                                <tr>
                                    <td>{{ $language->language_name }}</td>
                                    <td>{{ $language->language_code }}
                                        @if( ! file_exists(resource_path('lang/'.$language->language_code )))
                                            <p class="text-danger">@lang('app.directory_not_exists_in') <code>{{ resource_path('lang/') }}</code></p>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="btn btn-danger btn-xs" data-id="{{ $language->id }}"><i class="fa fa-trash"></i> </a>
                                    </td>
                                </tr>
                            @endforeach

                        </table>
                    </div>
                </div>




            </div>   <!-- /#page-wrapper -->




        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            $('.btn-danger').on('click', function (e) {
                if (!confirm("Are you sure? its can't be undone")) {
                    e.preventDefault();
                    return false;
                }
                var selector = $(this);
                var data_id = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('delete_language') }}',
                    data: {data_id: data_id, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.success == 1) {
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                            selector.closest('tr').hide('slow');
                        }
                    }
                });
            });

            $('input[type="checkbox"], input[type="radio"]').click(function () {
                var input_name = $(this).attr('name');
                var input_value = 0;
                if ($(this).prop('checked')) {
                    input_value = $(this).val();
                }
                $.ajax({
                    url: '{{ route('save_settings') }}',
                    type: "POST",
                    data: {[input_name]: input_value, '_token': '{{ csrf_token() }}'},
                });
            });
        });

    </script>
@endsection