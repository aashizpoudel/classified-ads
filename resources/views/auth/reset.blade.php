@extends('layout.main')
@section('title') Log-In | @parent @endsection

@section('main')

    <div class="container">
        <div class="row">

            <div class="login">
                <div class="col-sm-6 col-sm-offset-3 col-xs-12">

                    {!! Form::open(['route'=>'password_reset_post', 'class'=>'form-horizontal']) !!}
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group {{ $errors->has('email')? 'has-error':'' }} ">
                        <label class="control-label col-md-4">@lang('app.email')</label>
                        <div class="col-md-8">
                            <input type="email" name="email" id="email" class="form-control"  value="{{ old('email') }}" placeholder="@lang('app.email')">
                            {!! $errors->has('email')? '<p class="help-block">'.$errors->first('email').'</p>':'' !!}

                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('password')? 'has-error':'' }} ">
                        <label class="control-label col-md-4">@lang('app.password')</label>
                        <div class="col-md-8">
                            <input type="password" name="password" id="password" class="form-control"  value="{{ old('password') }}" placeholder="@lang('app.password')">
                            {!! $errors->has('password')? '<p class="help-block">'.$errors->first('password').'</p>':'' !!}

                        </div>
                    </div>

                    <div class="form-group {{ $errors->has('password_confirmation')? 'has-error':'' }} ">
                        <label class="control-label col-md-4">@lang('app.confirm_password')</label>
                        <div class="col-md-8">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control"  value="{{ old('password_confirmation') }}" placeholder="@lang('app.confirm_password')">
                            {!! $errors->has('password_confirmation')? '<p class="help-block">'.$errors->first('password_confirmation').'</p>':'' !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-8 col-md-offset-4">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-unlock"></i> @lang('app.reset_password')</button>

                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>

                <div class="clearfix"></div>
            </div>
        </div>
    </div>

@endsection

@section('page-js')
    <script>
        var options = {closeButton : true};
        @if(session('error'))
            toastr.error('{{ session('error') }}', 'Error!', options)
        @endif
    </script>
@endsection
