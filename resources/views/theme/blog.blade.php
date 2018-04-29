@extends('layout.main')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('main')

    <div class="jumbotron jumbotron-xs">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h2>{{ $title }}</h2>
                </div>
                <div class="col-md-6">
                    <div class="blog-breadcrumb">
                        <ul class="breadcrumb">
                            <li>
                                <a href="{{ route('home') }}">@lang('app.home')</a>
                            </li>
                            <li>
                                <span>@lang('app.blog')</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div id="blog-listing" class="col-md-10 col-sm-12 col-md-offset-1">
                @foreach($posts as $post)

                    <section class="post">
                        <div class="row">

                            <div itemscope itemtype="http://schema.org/NewsArticle">
                                <div class="col-md-4">
                                    <div class="image" style="height: 196px;">
                                        <a href="{{ route('blog_single', $post->slug) }}">
                                            @if($post->feature_img)
                                                <img class="img-responsive" alt="{{ $post->title }}" src="{{ media_url($post->feature_img) }}">
                                            @else
                                                <img class="img-responsive" alt="{{ $post->title }}" src="{{ asset('uploads/placeholder.png') }}">
                                            @endif
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h2 itemprop="headline"><a href="{{ route('blog_single', $post->slug) }}" class="blog-title">{{ $post->title }}</a></h2>
                                    <div class="clearfix">
                                        @if($post->author)
                                            <p class="author-category"  itemprop="author" itemscope itemtype="https://schema.org/Person">By <a href="{{ route('author_blog_posts', $post->author->id) }}"  itemprop="name">{{ $post->author->name }}</a></p>
                                        @endif
                                        <p class="date-comments">
                                            <i class="fa fa-calendar"></i> {{ $post->created_at_datetime() }}
                                        </p>
                                    </div>
                                    <p class="intro" itemprop="description"> {{ str_limit(strip_tags($post->post_content), 250) }} </p>
                                    <p class="read-more"><a href="{{ route('blog_single', $post->slug) }}" class="btn btn-template-main">@lang('app.continue_reading')</a></p>
                                    <p></p>
                                </div>

                                <meta itemprop="datePublished" content="{{ $post->created_at->toW3cString() }}"/>
                            </div>
                        </div>
                    </section>
                @endforeach

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