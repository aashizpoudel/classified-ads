@extends('layout.main')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('main')

    <div class="jumbotron jumbotron-xs">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-lg-12">
                    <h2>@lang('app.contact_with_us')</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-6">

                <form>
                    <legend><span class="glyphicon glyphicon-globe"></span> @lang('app.our_office')</legend>

                    <address>
                        <strong>{{ get_text_tpl(get_option('footer_company_name')) }}</strong>
                        @if(get_option('footer_address'))
                            <br />
                            <i class="fa fa-map-marker"></i>
                            {!! get_option('footer_address') !!}
                        @endif
                        @if(get_option('site_phone_number'))
                            <br><i class="fa fa-phone"></i>
                            <abbr title="Phone">{!! get_option('site_phone_number') !!}</abbr>
                        @endif

                    </address>

                    @if(get_option('site_email_address'))
                        <address>
                            <strong>@lang('app.email')</strong>
                            <br> <i class="fa fa-envelope-o"></i>
                            <a href="mailto:{{ get_option('site_email_address') }}"> {{ get_option('site_email_address') }} </a>
                        </address>
                    @endif

                </form>

                <div class="well well-sm">
                    {!! Form::open() !!}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group {{ $errors->has('name')? 'has-error':'' }}">
                                    <label for="name">@lang('app.name')</label>
                                    <input type="text" class="form-control" id="name" name="name" placeholder="@lang('app.enter_name')" value="{{ old('name') }}" required="required" />
                                    {!! $errors->has('name')? '<p class="help-block">'.$errors->first('name').'</p>':'' !!}
                                </div>
                                <div class="form-group {{ $errors->has('email')? 'has-error':'' }}">
                                    <label for="email">@lang('app.email_address')</label>
                                    <div class="input-group">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-envelope"></span>
                                </span>
                                        <input type="email" class="form-control" id="email" placeholder="@lang('app.enter_email_address')" name="email" value="{{ old('email') }}" required="required" />
                                    </div>
                                    {!! $errors->has('email')? '<p class="help-block">'.$errors->first('email').'</p>':'' !!}

                                </div>

                                <div class="form-group {{ $errors->has('message')? 'has-error':'' }}">
                                    <label for="name">@lang('app.message')</label>
                                    <textarea name="message" id="message" class="form-control" required="required" placeholder="@lang('app.message')">{{ old('message') }}</textarea>
                                    {!! $errors->has('message')? '<p class="help-block">'.$errors->first('message').'</p>':'' !!}
                                </div>
                            </div>

                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary pull-right" id="btnContactUs"> @lang('app.send_message')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-6">
                {!! get_option('google_map_embedded_code') !!}
            </div>
        </div>
    </div>



@endsection

@section('page-js')


    <script>
        @if(session('success'))
            toastr.success('{{ session('success') }}', '<?php echo trans('app.success') ?>', toastr_options);
        @endif
    </script>
@endsection