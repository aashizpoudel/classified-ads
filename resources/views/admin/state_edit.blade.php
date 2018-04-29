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
                            <label for="country_id" class="col-sm-4 control-label">@lang('app.select_a_category')</label>

                            <div class="col-sm-8 {{ $errors->has('country_id')? 'has-error':'' }}">
                                <select class="form-control select2" name="country_id">
                                    <option value="">@lang('app.select_country')</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $country->id == $state->country_id ? 'selected':'' }} >{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('country_id')? '<p class="help-block">'.$errors->first('country_id').'</p>':'' !!}

                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('state_name')? 'has-error':'' }}">
                            <label for="state_name" class="col-sm-4 control-label">@lang('app.state_name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="state_name" value="{{ old('state_name') ? old('state_name'): $state->state_name }}" name="state_name" placeholder="@lang('app.state_name')">
                                {!! $errors->has('state_name')? '<p class="help-block">'.$errors->first('state_name').'</p>':'' !!}

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">@lang('app.edit_state')</button>
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>

                </div>


            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection
