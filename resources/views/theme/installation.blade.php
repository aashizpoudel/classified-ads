<!doctype html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang=""> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang=""> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang=""> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>@lang('app.installation_wizard')</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- bootstrap css -->
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap-theme.min.css') }}">

    <script src="{{ asset('assets/js/vendor/modernizr-2.8.3-respond-1.4.2.min.js') }}"></script>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-sm-6 col-sm-offset-3 col-xs-12">

            <h1>@lang('app.installation_wizard')</h1>
            <p>Give your database configuration</p>

            <div class="login">

                <div id="server_message"></div>


                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                {{ Form::open(['class'=>'form-horizontal', 'id' => 'installation-form']) }}
                <hr />

                <div class="form-group {{ $errors->has('hostname')? 'has-error':'' }}">
                    <label for="hostname" class="col-sm-4 control-label">Hostname</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="hostname" name="hostname" placeholder="Hostname" value="{{ old('hostname')? old('hostname') : '127.0.0.1' }}">
                        {!! $errors->has('hostname')? '<p class="help-block">'.$errors->first('hostname').'</p>':'' !!}
                        <p class="text-muted">This is usually "127.0.0.1" <p>
                    </div>
                </div>

                <div class="form-group {{ $errors->has('dbport')? 'has-error':'' }}">
                    <label for="dbport" class="col-sm-4 control-label">Database port</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="dbport" name="dbport" placeholder="Database Port" value="{{ old('dbport')? old('dbport') : '3306' }}">
                        {!! $errors->has('dbport')? '<p class="help-block">'.$errors->first('dbport').'</p>':'' !!}
                        <p class="text-muted">In case of different, usually its "3306" <p>
                    </div>
                </div>

                <div class="form-group {{ $errors->has('username')? 'has-error':'' }}">
                    <label for="username" class="col-sm-4 control-label">Username</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="username" name="username" placeholder="username" value="{{ old('username')? old('username') : 'root' }}">
                        {!! $errors->has('username')? '<p class="help-block">'.$errors->first('username').'</p>':'' !!}
                        <p class="text-muted">Either something as "root" or a username given by the host <p>
                    </div>
                </div>

                <div class="form-group {{ $errors->has('password')? 'has-error':'' }}">
                    <label for="password" class="col-sm-4 control-label">Password</label>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" name="password" placeholder="password" value="{{ old('password') }}">
                        {!! $errors->has('password')? '<p class="help-block">'.$errors->first('password').'</p>':'' !!}
                        <p class="text-muted">Your database password <p>
                    </div>
                </div>

                <div class="form-group {{ $errors->has('database_name')? 'has-error':'' }}">
                    <label for="database_name" class="col-sm-4 control-label">Database Name</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="database_name" name="database_name" placeholder="Database Name" value="{{ old('database_name') }}">
                        {!! $errors->has('database_name')? '<p class="help-block">'.$errors->first('database_name').'</p>':'' !!}
                        <p class="text-muted">Place a Database name to connect with database <p>
                    </div>
                </div>

                <div class="form-group {{ $errors->has('envato_purchase_code')? 'has-error':'' }}">
                    <label for="envato_purchase_code" class="col-sm-4 control-label">Purchase Code</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="envato_purchase_code" name="envato_purchase_code" placeholder="Purchase Code" value="{{ old('envato_purchase_code') }}">
                        {!! $errors->has('envato_purchase_code')? '<p class="help-block">'.$errors->first('envato_purchase_code').'</p>':'' !!}
                        <p class="text-muted">Envato (Codecanyon) purchase code, you will get your purchase code from your purchase notification email or your codecanyon profile download item menu <p>
                    </div>
                </div>

                <hr />
                <div class="row">
                    <div class="col-xs-12"><input type="submit" id="installation_btn" value="@lang('app.install')" class="btn btn-primary btn-block btn-lg" tabindex="7"></div>
                </div>
                {{ Form::close() }}

                <div style="height: 100px;"></div>

            </div>
        </div>
    </div>
</div>



<script src="{{ asset('assets/js/vendor/jquery-1.11.2.min.js') }}"></script>

<script>
    $(function(){
        $('#installation-form').submit(function(e){
            e.preventDefault();
            var form_data = $(this).serialize();

            $('#installation_btn').attr('disabled', 'disabled');
            $.post( "{{route('installation')}}", form_data, function( data ) {
                if (data.success == 1){
                    $('#server_message').html('');
                    location.href = '{{route('home')}}';
                }else{
                    $('#server_message').html('<div class="alert alert-danger"> '+data.msg+' </div>');
                }
                $('#installation_btn').removeAttr('disabled');
            }).always(function(){
                $('#installation_btn').removeAttr('disabled');
            });


        });
    })
</script>

</body>
</html>