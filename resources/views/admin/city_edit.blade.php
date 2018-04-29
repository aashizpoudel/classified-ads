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
                            <label for="country" class="col-sm-4 control-label">@lang('app.select_a_category')</label>

                            <div class="col-sm-8 {{ $errors->has('country')? 'has-error':'' }}">
                                <select class="form-control select2" name="country">
                                    <option value="">@lang('app.select_country')</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ $city->state ? $country->id == $city->state->country_id? 'selected':'' :'' }}>{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('country')? '<p class="help-block">'.$errors->first('country').'</p>':'' !!}
                            </div>
                        </div>

                        <div class="form-group  {{ $errors->has('state')? 'has-error':'' }}">
                            <label for="category_name" class="col-sm-4 control-label">@lang('app.state')</label>
                            <div class="col-sm-8">
                                <select class="form-control select2" id="state_select" name="state">
                                    @if($states)
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" {{ $city->state_id == $state->id ? 'selected':'' }}> {{ $state->state_name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <p class="text-info">
                                    <span id="state_loader" style="display: none;"><i class="fa fa-spin fa-spinner"></i> </span>
                                </p>
                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('city_name')? 'has-error':'' }}">
                            <label for="city_name" class="col-sm-4 control-label">@lang('app.city_name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="city_name" value="{{ old('city_name')? old('city_name') : $city->city_name }}" name="city_name" placeholder="@lang('app.city_name')">
                                {!! $errors->has('city_name')? '<p class="help-block">'.$errors->first('city_name').'</p>':'' !!}
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">@lang('app.edit_city')</button>
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
        function generate_option_from_json(jsonData, fromLoad){
            //Load Category Json Data To Brand Select
            if(fromLoad === 'country_to_state'){
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="0" selected> @lang('app.select_state') </option>';
                    for ( i in jsonData){
                        option += '<option value="'+jsonData[i].id+'"> '+jsonData[i].state_name +' </option>';
                    }
                    $('#state_select').html(option);
                    $('#state_select').select2();
                }else {
                    $('#state_select').html('');
                    $('#state_select').select2();
                }
                $('#state_loader').hide('slow');
            }
        }
        $(document).ready(function() {
            $('[name="country"]').change(function () {
                var country_id = $(this).val();
                $('#state_loader').show();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('get_state_by_country') }}',
                    data: {country_id: country_id, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        generate_option_from_json(data, 'country_to_state');
                    }
                });
            });
        });
    </script>
@endsection