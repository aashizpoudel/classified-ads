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



                    <div class="col-sm-12">

                        <div class="form-group">
                            <label class="col-md-3 control-label">@lang('app.enable_disable') </label>
                            <div class="col-md-6">
                                <label for="enable_slider" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="enable_slider" name="enable_slider" {{ get_option('enable_slider') == 1 ? 'checked="checked"': '' }}>
                                    @lang('app.enable_slider')
                                </label>
                            </div>

                            <div class="col-sm-3">
                                <a href="{{ route('create_slider') }}" class="btn btn-primary pull-right"><i class="fa fa-save"></i> @lang('app.create_slider')</a>
                            </div>

                        </div>

                    </div>

                    <div class="col-sm-12">
                        <div class="clearfix"></div>
                        <hr />

                    @if($sliders->count() > 0)
                        <table class="table table-responsive table-bordered slideShow">
                            @foreach($sliders as $slider)
                                <tr>
                                    <td width="450">
                                        <img src="{{ slider_url($slider) }}" />
                                    </td>
                                    <td>
                                        <input type="text" placeholder="@lang('app.caption')" name="slider_caption" data-id="{{ $slider->id }}" class="form-control" value="{{ $slider->caption }}" />
                                        <br />
                                        <a href="javascript:;" class="btn btn-danger btn-lg imgDeleteBtn" data-id="{{ $slider->id }}"><i class="fa fa-trash"></i> </a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endif

                    </div>
                </div>
            </div>   <!-- /#page-wrapper -->
        </div>   <!-- /#wrapper -->

    </div> <!-- /#container -->
@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            $('body').on('click', '.imgDeleteBtn', function () {
                //Get confirm from user
                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }

                var current_selector = $(this);
                var img_id = $(this).data('id');

                $.ajax({
                    url: '{{ route('delete_slider') }}',
                    type: "POST",
                    data: {media_id: img_id, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.success == 1) {
                            current_selector.closest('tr').hide('slow');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });

            $('body').on('keyup change', 'input[name="slider_caption"]', function () {
                var caption = $(this).val();
                var slider_id = $(this).data('id');
                $.ajax({
                    url: '{{ route('update_slider_caption') }}',
                    type: "POST",
                    data: {media_id: slider_id, caption: caption, _token: '{{ csrf_token() }}'}
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
                    data: {[input_name]: input_value, '_token': '{{ csrf_token() }}'}
                });
            });
        });


    </script>
@endsection