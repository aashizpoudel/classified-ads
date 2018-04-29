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

                        <div class="alert alert-warning">
                            @lang('app.brand_edit_page_warning_msg') <br />
                            @lang('app.current_slug') : <strong>{{ $edit_brand->brand_slug }}</strong>
                        </div>


                        {{ Form::open(['class' => 'form-horizontal']) }}

                        <div class="form-group">
                            <label for="category_name" class="col-sm-4 control-label">@lang('app.select_a_category')</label>

                            <div class="col-sm-8">

                                <select class="form-control select2" name="category">
                                    @foreach($categories as $category)
                                        @if($category->sub_categories->count() > 0)
                                            <optgroup label="{{ $category->category_name }}">
                                                @foreach($category->sub_categories as $sub_category)
                                                    <option value="{{ $sub_category->id }}" {{ $sub_category->id == $edit_brand->category_id ? 'selected="selected"' : '' }}>{{ $sub_category->category_name }}</option>
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
                                <input type="text" class="form-control" id="brand_name" value="{{ old('brand_name')? old('brand_name') : $edit_brand->brand_name }}" name="brand_name" placeholder="@lang('app.brand_name')">
                                {!! $errors->has('brand_name')? '<p class="help-block">'.$errors->first('brand_name').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">@lang('app.edit_brand')</button>
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>

                </div>


            </div>   <!-- /#page-wrapper -->




        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection


