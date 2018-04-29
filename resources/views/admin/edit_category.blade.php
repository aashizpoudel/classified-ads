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
                            @lang('app.category_edit_page_warning_msg') <br />
                            @lang('app.current_slug') : <strong>{{ $edit_category->category_slug }}</strong>
                        </div>


                        {{ Form::open(['class' => 'form-horizontal']) }}

                        <div class="form-group">
                            <label for="category_name" class="col-sm-4 control-label">@lang('app.select_a_category')</label>

                            <div class="col-sm-8">
                                <select class="form-control select2" name="parent_category">
                                    <option value="0">Top Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $edit_category->category_id == $category->id ? 'selected="selected"' : '' }}>{{ $category->category_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('category_name')? 'has-error':'' }}">
                            <label for="category_name" class="col-sm-4 control-label">@lang('app.category_name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="category_name" value="{{ old('category_name') ? old('category_name') : $edit_category->category_name }}" name="category_name" placeholder="@lang('app.category_name')<">
                                {!! $errors->has('category_name')? '<p class="help-block">'.$errors->first('category_name').'</p>':'' !!}

                            </div>
                        </div>


                        <div class="form-group">
                            <label for="fa_icon" class="col-sm-4 control-label">@lang('app.select_icon')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2icon" name="fa_icon">
                                    <option value="0">@lang('app.select_icon')</option>
                                    @foreach(fa_icons() as $icon => $val)
                                        @if($icon == $edit_category->fa_icon)
                                        <option value="{{ $icon }}" data-icon="{{ $icon }}" selected>{{ $icon }}</option>
                                        @else
                                            <option value="{{ $icon }}" data-icon="{{ $icon }}">{{ $icon }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="`color_class" class="col-sm-4 control-label">@lang('app.select_color')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" name="color_class">
                                    @foreach(category_classes() as $class)
                                        <?php $selected = ($class == $edit_category->color_class) ? 'selected':''; ?>
                                        <option value="{{ $class }}" {{ $selected }}>{{ $class }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('description')? 'has-error':'' }}">
                            <label for="description" class="col-sm-4 control-label">@lang('app.description')</label>
                            <div class="col-sm-8">
                                <textarea name="description" id="description" class="form-control" rows="6">{{ old('description')? old('description') : $edit_category->description }}</textarea>
                                {!! $errors->has('description')? '<p class="help-block">'.$errors->first('description').'</p>':'' !!}

                            </div>
                        </div>





                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">@lang('app.edit_category')</button>
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

@endsection