@extends('layout.main')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection


@section('social-meta')
    <meta property="og:title" content="{{ $post->title }}">
    <meta property="og:description" content="{{ substr(trim(preg_replace('/\s\s+/', ' ',strip_tags($post->post_content) )),0,160) }}">
    @if($post->feature_img)
        <meta property="og:image" content="{{ media_url($post->feature_img, true) }}">
    @else
        <meta property="og:image" content="{{ asset('uploads/placeholder.png') }}">
    @endif
    <meta property="og:url" content="{{ route('blog_single', $post->slug) }}">
    <meta name="twitter:card" content="summary_large_image">
    <!--  Non-Essential, But Recommended -->
    <meta name="og:site_name" content="{{ get_option('site_name') }}">
@endsection

@section('main')

    <div class="jumbotron jumbotron-xs">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2 class="blog-post-title"><a href="{{ route('blog_single', $post->slug) }}" title="{{ $title }}">{{ $title }}</a> </h2>
                </div>
                <div class="col-md-6">
                    <div class="blog-breadcrumb">
                        <ul class="breadcrumb">
                            <li> <a href="{{ route('home') }}">@lang('app.home')</a> </li>
                            <li> <a href="{{ route('blog') }}">@lang('app.blog')</a> </li>
                            <li> <span>{{ $post->title }}</span> </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div id="blog-single" class="col-md-10 col-sm-12 col-md-offset-1">

                <section class="post post-{{ $post->id }}">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="clearfix">
                                @if($post->author)
                                    <p class="author-category">By <a href="{{ route('author_blog_posts', $post->author->id) }}">{{ $post->author->name }}</a></p>
                                @endif
                                <p class="date-comments">
                                    <i class="fa fa-calendar"></i> {{ $post->created_at_datetime() }}
                                </p>
                            </div>

                            <hr />

                            @if($post->feature_img)
                                <div class="row">
                                    <div class="col-md-12">
                                        <img class="img-responsive" alt="{{ $post->title }}" title="{{ $post->title }}" src="{{ media_url($post->feature_img, true) }}" style="width: 100%">
                                    </div>
                                </div>
                            @endif

                            <div class="post-content">
                                {!! $post->post_content !!}
                            </div>
                        </div>
                    </div>
                </section>

                @if($enable_discuss)
                    <div class="comments-title"><h2> <i class="fa fa-comment"></i> @lang('app.comments')</h2></div>

                    <div id="disqus_thread"></div>
                    <script>
                        var disqus_config = function () {
                            this.page.url = '{{ route('blog_single', $post->slug) }}';  // Replace PAGE_URL with your page's canonical URL variable
                            this.page.identifier = '{{ route('blog_single', $post->slug) }}'; // Replace PAGE_IDENTIFIER with your page's unique identifier variable
                        };
                        (function() { // DON'T EDIT BELOW THIS LINE
                            var d = document, s = d.createElement('script');
                            s.src = '//{{get_option('disqus_shortname')}}.disqus.com/embed.js';
                            s.setAttribute('data-timestamp', +new Date());
                            (d.head || d.body).appendChild(s);
                        })();
                    </script>
                    <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>

                @endif

            </div>

        </div>

    </div>

@endsection

@section('page-js')
    @if($enable_discuss)
        <script id="dsq-count-scr" src="//tclassifieds.disqus.com/count.js" async></script>
    @endif
    <script>
        @if(session('success'))
            toastr.success('{{ session('success') }}', '<?php echo trans('app.success') ?>', toastr_options);
        @endif
    </script>
@endsection