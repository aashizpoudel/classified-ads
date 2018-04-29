@lang('app.hi') : {{ $user['name'] }} <br /><br />

<p>@lang('app.activate_email_text')</p><br />

<a href="{{route('email_activation_link', $user['activation_code'])}}">{{route('email_activation_link', $user['activation_code'])}}</a>
