
<div class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row">

                <div class="col-sm-4 col-xs-12">
                    <div class="footer-widget">
                        <h4>About Us</h4>
                        <p>{{ get_option('footer_about_us') }}</p>
                        <p>{!! get_option('footer_about_us_read_more_text') !!}</p>
                    </div>
                </div>

                <div class="col-sm-4 col-xs-12">
                    <div class="footer-widget footer-address">
                        <h4>@lang('app.contact_us')</h4>
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
                    </div>
                </div>

                <div class="col-sm-4 col-xs-12">
                    <div class="footer-widget">
                        <h4>Quick Link</h4>
                        <ul class="footer-menu">
                            @if($show_in_footer_menu->count() > 0)
                                @foreach($show_in_footer_menu as $page)
                                    <li><a href="{{ route('single_page', $page->slug) }}">{{ $page->title }} </a></li>
                                @endforeach
                            @endif

                            @if(get_option('show_blog_in_footer'))
                                <li><a href="{{ route('blog') }}">@lang('app.blog')</a></li>
                            @endif
                            <li><a href="{{ route('contact_us_page') }}">@lang('app.contact_us')</a></li>
                        </ul>

                        <div class="social-icons">
                            <ul class="social-ul">
                                @if(get_option('facebook_url'))
                                    <li><a href="{{ get_option('facebook_url') }}" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                @endif

                                @if(get_option('twitter_url'))
                                    <li><a href="{{ get_option('twitter_url') }}" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                @endif
                                @if(get_option('linked_in_url'))
                                    <li><a href="{{ get_option('linked_in_url') }}" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                @endif
                                @if(get_option('dribble_url'))
                                    <li><a href="{{ get_option('dribble_url') }}" target="_blank"><i class="fa fa-dribbble"></i></a></li>
                                @endif
                                @if(get_option('google_plus_url'))
                                    <li><a href="{{ get_option('google_plus_url') }}" target="_blank"><i class="fa fa-google-plus"></i></a></li>
                                @endif
                                @if(get_option('youtube_url'))
                                    <li><a href="{{ get_option('youtube_url') }}" target="_blank"><i class="fa fa-youtube"></i></a></li>
                                @endif

                            </ul>
                            <div class="clearfix"></div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="pull-left">{!! get_text_tpl(get_option('footer_left_text')) !!}</p>
                    <p class="pull-right"> Site By <a href="https://aashizpoudel.github.io" target="_blank">Aashiz</a> </p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loadingOverlay" style="display: none;">
    <div class="circleLoader"></div>
    <p>@lang('app.loading')...</p>
</div>


<script src="{{ asset('assets/js/vendor/jquery-1.11.2.min.js') }}"></script>
<script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/plugins/toastr/toastr.min.js') }}"></script>
<script src="{{ asset('assets/select2-3.5.3/select2.min.js') }}"></script>
<script src="{{ asset('assets/plugins/nprogress/nprogress.js') }}"></script>
<script type="text/javascript">
    NProgress.start();
    NProgress.done();
</script>
<!-- Conditional page load script -->
@if(request()->segment(1) === 'dashboard')
    <script src="{{ asset('assets/plugins/metisMenu/dist/metisMenu.min.js') }}"></script>
    <script>
        $(function() {
            $('#side-menu').metisMenu();
        });
    </script>
@endif
<script src="{{ asset('assets/js/main.js') }}"></script>
<script>
    var toastr_options = {closeButton : true};
</script>
@yield('page-js')


@if(get_option('additional_js'))
    {!! get_option('additional_js') !!}
@endif
<script>
    $(document).on('click', '.ghuranti', function(){
        $('.themeqx-demo-chooser-wrap').toggleClass('open');
    });
</script>

</body>
</html>