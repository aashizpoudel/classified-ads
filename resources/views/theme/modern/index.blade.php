@extends('layout.main')

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/owl.carousel/assets/owl.carousel.css') }}">
@endsection

@section('main')
    <div class="modern-top-intoduce-section">

        <div class="container">
            <div class="row">
                <div class="col-md-5">

                    <div class="mdern-top-introduce-left">
                        <div class="alert alert-warning">
                            <h2>{{ get_option('modern_home_left_title') }} </h2>
                            <?php
                                $modern_home_left_content = get_option('modern_home_left_content');
                            if ($modern_home_left_content){
                                $modern_home_left_content = explode("\n", $modern_home_left_content);
                                foreach ($modern_home_left_content as $mhlc_value){
                                    echo "<p><i class='fa fa-check'></i> {$mhlc_value} </p>";
                                }
                            }
                            ?>
                        </div>
                    </div>

                    </div>

                <div class="col-md-7">

                    <div class="mdern-top-introduce-left">
                        <h1>{{ get_option('modern_home_right_title') }}</h1>

                        <p>{{ get_option('modern_home_right_content') }}</p>
                        <a href="" class="btn btn-info btn-lg"> @lang('app.post_an_ad') </a>
                    </div>

                </div>
            </div>
        </div>
    </div>


    <div class="modern-top-hom-cat-section">

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="modern-home-search-bar-wrap">
                        <div class="search-wrapper">
                            <form class="form-inline" action="{{ route('listing') }}" method="get">
                                <div class="form-group">
                                    <input type="text"  class="form-control" id="searchTerms" name="q" value="{{ request('q') }}" placeholder="@lang('app.search___')" />
                                </div>

                                <div class="form-group">
                                    <select class="form-control select2" name="sub_category">
                                        <option value="">@lang('app.select_a_category')</option>
                                        @foreach($top_categories as $category)
                                            @if($category->sub_categories->count() > 0)
                                                <optgroup label="{{ $category->category_name }}">
                                                    @foreach($category->sub_categories as $sub_category)
                                                        <option value="{{ $sub_category->id }}" {{ old('category') == $sub_category->id ? 'selected': '' }}>{{ $sub_category->category_name }}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>

                                @php $country_usage = get_option('countries_usage'); @endphp
                                @if($country_usage == 'all_countries')
                                    <div class="form-group">
                                        <select class="form-control select2" name="country">
                                            <option value="">@lang('app.select_a_country')</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country->id }}" {{ request('country') == $country->id ? 'selected' :'' }}>{{ $country->country_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <select class="form-control select2" id="state_select" name="state">
                                        <option value=""> @lang('app.select_state') </option>
                                    </select>
                                </div>

                                <button type="submit" class="btn theme-btn"> <i class="fa fa-search"></i> Search Ads</button>
                            </form>
                        </div>

                    </div>

                    <div class="clearfix"></div>

                    @if(get_option('modern_category_display_style') == 'show_top_category')
                    <div class="modern-home-cat-wrap">
                        <ul class="modern-home-cat-ul">
                            @foreach($top_categories as $category)
                                <li><a href="{{ route('listing') }}?category={{$category->id}}">
                                        <i class="fa {{ $category->fa_icon }}"></i>
                                        <span class="category-name">{{ $category->category_name }} </span>
                                        <p class="count text-muted">({{ number_format($category->product_count) }})</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @else
                        <div class="modern-home-cat-with-sub-wrap">

                            @foreach($top_categories as $category)
                                <div class="modern-cat-list-with-sub-wrap">
                                    <div class="modern-home-cat-top-item">
                                        <a href="{{ route('listing') }}?category={{$category->id}}">
                                            <i class="fa {{ $category->fa_icon }}"></i>
                                            <span class="category-name"><strong>{{ $category->category_name }}</strong> </span>
                                        </a>
                                    </div>

                                    <div class="modern-home-cat-sub-item">
                                        @if($category->sub_categories->count())
                                            <ul class="list-unstyled">

                                                @foreach($category->sub_categories as $s_cat)

                                                    <li><a href="{{ route('listing') }}?category={{$category->id}}&sub_category={{$s_cat->id}}">
                                                            <i class="fa fa-arrow-right"></i> {{ $s_cat->category_name }}
                                                        </a></li>
                                                @endforeach
                                            </ul>

                                        @endif
                                    </div>
                                    <div class="clearfix"></div>
                                </div>

                            @endforeach

                        </div>
                    @endif

                </div>
            </div>
        </div>

    </div>

    @if($enable_monetize)
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    {!! get_option('monetize_code_below_categories') !!}
                </div>
            </div>
        </div>
    @endif


    @if($urgent_ads->count() > 0)
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="carousel-header">
                        <h4><a href="{{ route('listing') }}">
                                @lang('app.new_urgent_ads')
                            </a>
                        </h4>
                    </div>
                    <hr />
                    <div class="themeqx_new_regular_ads_wrap themeqx-carousel-ads">
                        @foreach($urgent_ads as $ad)
                            <div>
                                <div itemscope itemtype="http://schema.org/Product" class="ads-item-thumbnail ad-box-{{$ad->price_plan}}">
                                    <div class="ads-thumbnail">
                                        <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}">
                                            <img itemprop="image"  src="{{ media_url($ad->feature_img) }}" class="img-responsive" alt="{{ $ad->title }}">

                                            <span class="modern-img-indicator">
                                                @if(! empty($ad->video_url))
                                                    <i class="fa fa-file-video-o"></i>
                                                @else
                                                    <i class="fa fa-file-image-o"> {{ $ad->media_img->count() }}</i>
                                                @endif
                                            </span>
                                        </a>
                                    </div>
                                    <div class="caption">
                                        <h4><a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}"><span itemprop="name">{{ str_limit($ad->title, 40) }} </span></a></h4>
                                        @if($ad->category)
                                        <a class="price text-muted" href="{{ route('listing', ['category' => $ad->category->id]) }}"> <i class="fa fa-folder-o"></i> {{ $ad->category->category_name }} </a>
                                        @endif

                                        @if($ad->city)
                                            <a class="location text-muted" href="{{ route('listing', ['city' => $ad->city->id]) }}"> <i class="fa fa-location-arrow"></i> {{ $ad->city->city_name }} </a>
                                        @endif
                                        <p class="date-posted text-muted"> <i class="fa fa-clock-o"></i> {{ $ad->created_at->diffForHumans() }}</p>
                                        <p class="price"> <span itemprop="price" content="{{$ad->price}}"> {{ themeqx_price_ng($ad->price, $ad->is_negotiable) }} </span></p>
                                        <link itemprop="availability" href="http://schema.org/InStock" />
                                    </div>

                                    @if($ad->price_plan == 'premium')
                                        <div class="ribbon-wrapper-green"><div class="ribbon-green">{{ ucfirst($ad->price_plan) }}</div></div>
                                    @endif
                                    @if($ad->mark_ad_urgent == '1')
                                        <div class="ribbon-wrapper-red"><div class="ribbon-red">@lang('app.urgent')</div></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div> <!-- themeqx_new_premium_ads_wrap -->
                </div>
            </div>
        </div>
    @endif


    @if($premium_ads->count() > 0)
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="carousel-header">
                        <h4><a href="{{ route('listing') }}">
                                @lang('app.new_premium_ads')
                            </a>
                        </h4>
                    </div>
                    <hr />
                    <div class="themeqx_new_regular_ads_wrap themeqx-carousel-ads">
                        @foreach($premium_ads as $ad)
                            <div>
                                <div itemscope itemtype="http://schema.org/Product" class="ads-item-thumbnail ad-box-{{$ad->price_plan}}">
                                    <div class="ads-thumbnail">
                                        <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}">
                                            <img itemprop="image"  src="{{ media_url($ad->feature_img) }}" class="img-responsive" alt="{{ $ad->title }}">

                                            <span class="modern-img-indicator">
                                                @if(! empty($ad->video_url))
                                                    <i class="fa fa-file-video-o"></i>
                                                @else
                                                    <i class="fa fa-file-image-o"> {{ $ad->media_img->count() }}</i>
                                                @endif
                                            </span>
                                        </a>
                                    </div>
                                    <div class="caption">
                                        <h4><a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}"><span itemprop="name">{{ str_limit($ad->title, 40) }} </span></a></h4>
                                        @if($ad->category)
                                        <a class="price text-muted" href="{{ route('listing', ['category' => $ad->category->id]) }}"> <i class="fa fa-folder-o"></i> {{ $ad->category->category_name }} </a>
                                        @endif

                                        @if($ad->city)
                                            <a class="location text-muted" href="{{ route('listing', ['city' => $ad->city->id]) }}"> <i class="fa fa-location-arrow"></i> {{ $ad->city->city_name }} </a>
                                        @endif
                                        <p class="date-posted text-muted"> <i class="fa fa-clock-o"></i> {{ $ad->created_at->diffForHumans() }}</p>
                                        <p class="price"> <span itemprop="price" content="{{$ad->price}}"> {{ themeqx_price_ng($ad->price, $ad->is_negotiable) }} </span></p>
                                        <link itemprop="availability" href="http://schema.org/InStock" />
                                    </div>

                                    @if($ad->price_plan == 'premium')
                                        <div class="ribbon-wrapper-green"><div class="ribbon-green">{{ ucfirst($ad->price_plan) }}</div></div>
                                    @endif
                                    @if($ad->mark_ad_urgent == '1')
                                        <div class="ribbon-wrapper-red"><div class="ribbon-red">@lang('app.urgent')</div></div>
                                    @endif


                                </div>
                            </div>
                        @endforeach
                    </div> <!-- themeqx_new_premium_ads_wrap -->
                </div>


            </div>
        </div>
        @if($enable_monetize)
            <div class="container">
                <div class="row">
                    <div class="col-sm-12">
                        {!! get_option('monetize_code_below_premium_ads') !!}
                    </div>
                </div>
            </div>
        @endif
    @endif



    @if($regular_ads->count() > 0)

        <div class="container">
            <div class="row">

                <div class="col-sm-12">

                    <div class="carousel-header">
                        <h4><a href="{{ route('listing') }}">
                                @lang('app.new_regular_ads')
                            </a>
                        </h4>
                    </div>
                    <hr />

                    <div class="themeqx_new_premium_ads_wrap themeqx-carousel-ads">
                        @foreach($regular_ads as $ad)
                            <div>
                                <div itemscope itemtype="http://schema.org/Product" class="ads-item-thumbnail ad-box-{{$ad->price_plan}}">
                                    <div class="ads-thumbnail">
                                        <a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}">
                                            <img itemprop="image"  src="{{ media_url($ad->feature_img) }}" class="img-responsive" alt="{{ $ad->title }}">

                                            <span class="modern-img-indicator">
                                                @if(! empty($ad->video_url))
                                                    <i class="fa fa-file-video-o"></i>
                                                @else
                                                    <i class="fa fa-file-image-o"> {{ $ad->media_img->count() }}</i>
                                                @endif
                                            </span>
                                        </a>
                                    </div>
                                    <div class="caption">
                                        <h4><a href="{{ route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}"><span itemprop="name">{{ str_limit($ad->title, 40) }} </span></a></h4>
                                        @if($ad->category)
                                        <a class="price text-muted" href="{{ route('listing', ['category' => $ad->category->id]) }}"> <i class="fa fa-folder-o"></i> {{ $ad->category->category_name }} </a>
                                        @endif

                                        @if($ad->city)
                                            <a class="location text-muted" href="{{ route('listing', ['city' => $ad->city->id]) }}"> <i class="fa fa-location-arrow"></i> {{ $ad->city->city_name }} </a>
                                        @endif
                                        <p class="date-posted text-muted"> <i class="fa fa-clock-o"></i> {{ $ad->created_at->diffForHumans() }}</p>
                                        <p class="price"> <span itemprop="price" content="{{$ad->price}}"> {{ themeqx_price_ng($ad->price, $ad->is_negotiable) }} </span></p>
                                        <link itemprop="availability" href="http://schema.org/InStock" />
                                    </div>

                                    @if($ad->price_plan == 'premium')
                                        <div class="ribbon-wrapper-green"><div class="ribbon-green">{{ ucfirst($ad->price_plan) }}</div></div>
                                    @endif
                                    @if($ad->mark_ad_urgent == '1')
                                        <div class="ribbon-wrapper-red"><div class="ribbon-red">@lang('app.urgent')</div></div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div> <!-- themeqx_new_premium_ads_wrap -->
                </div>

            </div>
        </div>

    @endif

    @if($enable_monetize)
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    {!! get_option('monetize_code_below_regular_ads') !!}
                </div>
            </div>
        </div>
    @endif

    @if(get_option('show_latest_blog_in_homepage') ==1)
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="carousel-header">
                        <h4><a href="{{ route('blog') }}">
                                @lang('app.latest_post_from_blog')
                            </a>
                        </h4>
                    </div>
                    <hr />

                    <div class="home-latest-blog themeqx-carousel-blog-post">
                        @foreach($posts as $post)
                            <div>
                                <div class="image">
                                    <a href="{{ route('blog_single', $post->slug) }}">
                                        @if($post->feature_img)
                                            <img alt="{{ $post->title }}" src="{{ media_url($post->feature_img) }}">
                                        @else
                                            <img  alt="{{ $post->title }}" src="{{ asset('uploads/placeholder.png') }}">
                                        @endif
                                    </a>
                                </div>

                                <h2><a href="{{ route('blog_single', $post->slug) }}" class="blog-title">{{ $post->title }}</a></h2>

                                <div class="blog-post-carousel-meta-info">
                                    @if($post->author)
                                        <span class="pull-left">By <a href="{{ route('author_blog_posts', $post->author->id) }}">{{ $post->author->name }}</a></span>
                                    @endif
                                    <span class="pull-right">
                                        <i class="fa fa-calendar"></i> {{ $post->created_at_datetime() }}
                                    </span>
                                    <div class="clearfix"></div>
                                </div>
                                <p class="intro"> {{ str_limit(strip_tags($post->post_content), 80) }}</p>
                                <a class="btn btn-default" href="{{ route('blog_single', $post->slug) }}" >@lang('app.continue_reading')  <i class="fa fa-external-link"></i> </a>

                            </div>
                        @endforeach
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="modern-post-ad-call-to-cation">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <h1>@lang('app.want_something_sell_quickly')</h1>
                    <p>@lang('app.post_your_ad_quicly')</p>
                    <a href="{{route('create_ad')}}" class="btn btn-info btn-lg">@lang('app.post_an_ad')</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/owl.carousel/owl.carousel.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(".themeqx_new_premium_ads_wrap").owlCarousel({
                loop:true,
                margin:10,
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                        nav:true
                    },
                    600:{
                        items:3,
                        nav:false
                    },
                    1000:{
                        items:4,
                        nav:true,
                        loop:false
                    }
                },
                navText : ['<i class="fa fa-arrow-circle-o-left"></i>','<i class="fa fa-arrow-circle-o-right"></i>']
            });
        });

        $(document).ready(function(){
            $(".themeqx_new_regular_ads_wrap").owlCarousel({
                loop:true,
                margin:10,
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                        nav:true
                    },
                    600:{
                        items:3,
                        nav:false
                    },
                    1000:{
                        items:4,
                        nav:true,
                        loop:false
                    }
                },
                navText : ['<i class="fa fa-arrow-circle-o-left"></i>','<i class="fa fa-arrow-circle-o-right"></i>']
            });
        });
        $(document).ready(function(){
            $(".home-latest-blog").owlCarousel({
                loop:true,
                margin:10,
                responsiveClass:true,
                responsive:{
                    0:{
                        items:1,
                        nav:true
                    },
                    600:{
                        items:3,
                        nav:false
                    },
                    1000:{
                        items:4,
                        nav:true,
                        loop:false
                    }
                },
                navText : ['<i class="fa fa-arrow-circle-o-left"></i>','<i class="fa fa-arrow-circle-o-right"></i>']
            });
        });

    </script>
    <script>
        function generate_option_from_json(jsonData, fromLoad){
            //Load Category Json Data To Brand Select
            if(fromLoad === 'country_to_state'){
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="" selected> @lang('app.select_state') </option>';
                    for ( i in jsonData){
                        option += '<option value="'+jsonData[i].id+'"> '+jsonData[i].state_name +' </option>';
                    }
                    $('#state_select').html(option);
                    $('#state_select').select2();
                }else {
                    $('#state_select').html('<option value="" selected> @lang('app.select_state') </option>');
                    $('#state_select').select2();
                }
                $('#loaderListingIcon').hide('slow');
            }
        }

        $(document).ready(function(){
            $('[name="country"]').change(function(){
                var country_id = $(this).val();
                $('#loaderListingIcon').show();
                $.ajax({
                    type : 'POST',
                    url : '{{ route('get_state_by_country') }}',
                    data : { country_id : country_id,  _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        generate_option_from_json(data, 'country_to_state');
                    }
                });
            });
        });

        $(document).ready(function(){
            @if($country_usage == 'single_country')
                $('#loaderListingIcon').show();
            var country_id = {{get_option('usage_single_country_id')}};
                $.ajax({
                    type : 'POST',
                    url : '{{ route('get_state_by_country') }}',
                    data : { country_id : country_id,  _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        generate_option_from_json(data, 'country_to_state');
                    }
                });
            @endif
        });

    </script>
@endsection