@extends('layout.main')
@section('title') Log-In | @parent @endsection

@section('main')

    <div class="container">
        <div class="row">
            <div class="col-sm-6 col-sm-offset-3 col-xs-12">

                <div class="login">
                    <h3 class="authTitle">Login or <a href="{{ route('user.create') }}" class="btn btn-xl btn-default">Sign up</a></h3>


                    @if(session('registration_success'))
                        <div class="alert alert-success">
                            {{ session('registration_success') }}
                        </div>
                    @endif

                    @if(get_option('enable_social_login') == 1)

                        <div class="row row-sm-offset-3 socialButtons">

                            @if(get_option('enable_facebook_login') == 1)
                                <div class="col-xs-6">
                                    <a href="{{ route('facebook_redirect') }}" class="btn btn-lg btn-block btn-facebook">
                                        <i class="fa fa-facebook visible-xs"></i>
                                        <span class="hidden-xs"><i class="fa fa-facebook-square"></i> Facebook</span>
                                    </a>
                                </div>
                            @endif

                            @if(get_option('enable_google_login') == 1)
                                <div class="col-xs-6">
                                    <a href="{{ route('google_redirect') }}" class="btn btn-lg btn-block btn-google">
                                        <i class="fa fa-google-plus visible-xs"></i>
                                        <span class="hidden-xs"><i class="fa fa-google-plus-square"></i> Google+</span>
                                    </a>
                                </div>
                            @endif

                        </div>

                        <div class="row row-sm-offset-3 loginOr">
                            <div class="col-xs-12">
                                <hr class="hrOr">
                                <span class="spanOr">or</span>
                            </div>
                        </div>

                    @endif

                    <div class="row row-sm-offset-3">
                        <div class="col-xs-12">
                            {{ Form::open(['class'=> 'loginForm', 'autocomplete'=> 'off']) }}

                            <div class="input-group {{ $errors->has('email')? 'has-error':'' }}">
                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="email address">

                            </div>
                            {!! $errors->has('email')? '<p class="help-block">'.$errors->first('email').'</p>':'' !!}
                            <span class="help-block"></span>

                            <div class="input-group {{ $errors->has('password')? 'has-error':'' }}">
                                <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                                <input  type="password" class="form-control" name="password" placeholder="Password">
                            </div>
                            {!! $errors->has('password')? '<p class="help-block">'.$errors->first('password').'</p>':'' !!}

                            <span class="help-block"></span>
                            <button class="btn btn-lg btn-primary btn-block" type="submit">Login</button>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <div class="row row-sm-offset-3">
                        <div class="col-xs-12">
                            <div class="col-xs-12 col-sm-6">
                                <label class="checkbox">
                                    <input type="checkbox" value="remember-me">Remember Me
                                </label>
                            </div>
                            <div class="col-xs-12 col-sm-6">
                                <p class="forgotPassword">
                                    {{--<a href="/password/reset" data-toggle="modal" data-target="#forgotPasswordEmail"> @lang('app.forgot_password')</a>--}}
                                    <a href="password/reset"> @lang('app.forgot_password')</a>
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="forgotPasswordEmail" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                {!! Form::open(['route'=>'send_reset_link']) !!}

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@lang('app.enter_email_to_reset_password')</h4>
                </div>
                <div class="modal-body">

                        <div class="form-group">
                            <label for="email" class="control-label">@lang('app.email'):</label>
                            <input type="text" class="form-control" id="email" name="email">
                            <div id="email_info"></div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">@lang('app.close')</button>
                    <button type="submit" class="btn btn-primary" id="send_reset_link">@lang('app.send_reset_link')</button>
                </div>
                </form>

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
