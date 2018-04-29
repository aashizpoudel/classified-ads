@extends('layout.main')
@section('title') Register | @parent @endsection
@section('main')

    <div class="container">
        <div class="row">
            <div class=" col-md-4 col-sm-4 col-xs-12">
                <div class="info-box bg-white ">
                    <h4><i class="fa fa-edit"></i> </h4>
                    <p class="intro"> View, edit and delete your ads. </p>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="info-box bg-white ">
                    <h4><i class="fa fa-clock-o"></i> </h4>
                    <p class="intro"> Quick publish new ads with contact details. </p>
                </div>
            </div>

            <div class="col-md-4 col-sm-4 col-xs-12">
                <div class="info-box bg-white ">
                    <h4><i class="fa fa-bar-chart-o"></i> </h4>
                    <p class="intro"> Keep track of your favorite ads. </p>
                </div>
            </div>

        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-12">

                <div class="login">
                    <h3 class="authTitle">Sign up or <a href="{{ route('login') }}" class="btn btn-xl btn-default">@lang('app.login')</a></h3>


                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('registration_success'))
                        <div class="alert alert-success">
                            {{ session('registration_success') }}
                        </div>
                    @endif


                    {{ Form::open(['route'=>'user.store', 'role'=> 'form']) }}
                    <hr />
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group {{ $errors->has('first_name')? 'has-error':'' }} ">
                                <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name') }}" placeholder="First Name" tabindex="1">

                                {!! $errors->has('first_name')? '<p class="help-block">'.$errors->first('first_name').'</p>':'' !!}
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group {{ $errors->has('last_name')? 'has-error':'' }} ">
                                <input type="text" name="last_name" id="last_name" class="form-control"  value="{{ old('last_name') }}" placeholder="Last Name" tabindex="2">
                                {!! $errors->has('last_name')? '<p class="help-block">'.$errors->first('last_name').'</p>':'' !!}
                            </div>
                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('email')? 'has-error':'' }} ">
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" placeholder="Email Address" tabindex="4">
                        {!! $errors->has('email')? '<p class="help-block">'.$errors->first('email').'</p>':'' !!}

                    </div>

                    <div class="form-group {{ $errors->has('phone')? 'has-error':'' }}">
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone') }}" placeholder="Phone Number" tabindex="3">
                        {!! $errors->has('phone')? '<p class="help-block">'.$errors->first('phone').'</p>':'' !!}
                    </div>


                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group {{ $errors->has('gender')? 'has-error':'' }}">
                                <select id="gender" name="gender" class="form-control select2">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Fe-Male</option>
                                    <option value="third_gender">Third Gender</option>
                                </select>
                                {!! $errors->has('gender')? '<p class="help-block">'.$errors->first('gender').'</p>':'' !!}

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group {{ $errors->has('country')? 'has-error':'' }}">
                                <select id="country" name="country" class="form-control select2">
                                    <option value="">@lang('app.select_a_country')</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}" {{ old('country') == $country->id ? 'selected' :'' }}>{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('country')? '<p class="help-block">'.$errors->first('country').'</p>':'' !!}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group {{ $errors->has('password')? 'has-error':'' }}">
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password" tabindex="5">
                                {!! $errors->has('password')? '<p class="help-block">'.$errors->first('password').'</p>':'' !!}

                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-6 col-md-6">
                            <div class="form-group {{ $errors->has('password_confirmation')? 'has-error':'' }}">
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password" tabindex="6">
                                {!! $errors->has('password_confirmation')? '<p class="help-block">'.$errors->first('password_confirmation').'</p>':'' !!}

                            </div>
                        </div>
                    </div>
                    <div class="row  {{ $errors->has('password')? 'has-error':'' }}">
                        <div class="col-xs-4 col-sm-3 col-md-3">
					<span class="button-checkbox">
						<label><input type="checkbox" name="agree" value="1" /> I Agree </label>
					</span>
                        </div>
                        <div class="col-xs-8 col-sm-9 col-md-9">
                            By clicking <strong class="label label-primary">Register</strong>, you agree to the <a href="{{ route('single_page', 'terms-and-condition') }}" target="_blank">Terms and Conditions</a> set out by this site, including our Cookie Use.
                        </div>

                        <div class="col-sm-12">
                            {!! $errors->has('password')? '<p class="help-block">You must agree with terms and condition</p>':'' !!}
                        </div>
                    </div>

                    <hr />
                    <div class="row">
                        <div class="col-xs-12"><input type="submit" value="Register" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
                    </div>
                    {{ Form::close() }}

                </div>
            </div>
        </div>
    </div>


@endsection

@section('page-js')
    <script>
        $(document).ready(function() {
            $('#phone').keyup(function(){
                $(this).val($(this).val().replace(/[^0-9]/g,""));
            });
        });

    </script>
@endsection

