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
                    <div class="col-sm-8 col-sm-offset-1 col-xs-12">

                        {{ Form::open(['class' => 'form-horizontal']) }}

                        <div class="form-group">
                            <label for="category_name" class="col-sm-4 control-label">@lang('app.select_a_category')</label>

                            <div class="col-sm-8">

                                <select class="form-control select2" name="category">
                                    @foreach($categories as $category)
                                        @if($category->sub_categories->count() > 0)
                                            <optgroup label="{{ $category->category_name }}">
                                                @foreach($category->sub_categories as $sub_category)
                                                    <option value="{{ $sub_category->id }}">{{ $sub_category->category_name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endif
                                    @endforeach

                                </select>

                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('brand_name')? 'has-error':'' }}">
                            <label for="brand_name" class="col-sm-4 control-label">@lang('app.brand_name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="brand_name" value="{{ old('brand_name') }}" name="brand_name" placeholder="@lang('app.brand_name')">
                                {!! $errors->has('brand_name')? '<p class="help-block">'.$errors->first('brand_name').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">@lang('app.save_new_brand')</button>
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>

                </div>


                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-bordered">
                            <tr>
                                <th>@lang('app.brand_name') (@lang('app.total_products')) </th>
                                <th>@lang('app.category') </th>
                            </tr>
                            @foreach($brands as $brand)
                                <tr>
                                    <td>
                                        <div class="clearfix">
                                            <strong>{{ $brand->brand_name }} ({{ $brand->product_count }})</strong>
                                            <span class="pull-right">

                                            <a href="{{ route('edit_brands', $brand->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil"></i> </a>
                                            <a href="javascript:;" class="btn btn-danger btn-xs" data-id="{{ $brand->id }}"><i class="fa fa-trash"></i> </a>
                                            </span>

                                        </div>

                                    </td>
                                    <td> @if($brand->category) {{ $brand->category->category_name }} @endif</td>
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
                if (!confirm("<?php echo trans('app.are_you_sure') ?>")) {
                    e.preventDefault();
                    return false;
                }

                var selector = $(this);
                var data_id = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('delete_brands') }}',
                    data: {data_id: data_id, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.success == 1) {
                            selector.closest('tr').hide('slow');
                            var options = {closeButton: true};
                            toastr.success(data.msg, '<?php echo trans('app.success') ?>', options)
                        }
                    }
                });
            });
        });
    </script>

    <script>
        var options = {closeButton : true};
        @if(session('success'))
            toastr.success('{{ session('success') }}', '<?php echo trans('app.success') ?>', options);
        @endif
    </script>
@endsection


