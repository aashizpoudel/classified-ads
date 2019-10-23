@extends('layout.main')

@section('main')

    @if(get_option('enable_slider') == 1 )

        <div class="container-fluid">
            <div class="row">
                <div class="">
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            @php $i=0; @endphp
                            @foreach($sliders as $slider)
                                <li data-target="#carousel-example-generic" data-slide-to="{{ $i }}" class="{{ $i==0? 'active':'' }}"></li>
                                @php $i++; @endphp
                            @endforeach
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            @php $i=0; @endphp
                            @foreach($sliders as $slider)
                                <div class="item {{ $i==0? 'active':'' }}">
                                    <img src="{{ slider_url($slider) }}" alt="Image one">
                                    <div class="carousel-caption">{{ $slider->caption }} </div>
                                </div>
                                @php $i++; @endphp
                            @endforeach
                        </div>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
                            <span class="sr-only">@lang('app.previous')</span>
                        </a>
                        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
                            <span class="sr-only">@lang('app.next')</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif


    <div class="container">
        <div class="row">
            <div class="col-md-12">


                {{-- @if($enable_monetize)
                    {!! get_option('monetize_code_below_slider') !!}
                @endif --}}

                <div class="section-header bg-white my-5">
                    <div class="search-wrapper  d-inline-block mx-auto">
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

                            <div class="form-group">
                                <select class="form-control select2" id="city_select" name="city">
                                    <option value=""> @lang('app.select_city') </option>
                                </select>
                            </div>

                            <button type="submit" class="btn btn-default"> <i class="fa fa-search"></i> Search Ads</button>
                        </form>

                    </div>
                    <div class="clearfix"></div>
                </div>

                {{-- @if($enable_monetize)
                    {!! get_option('monetize_code_below_search_bar') !!}
                @endif --}}

            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header bg-white">
                    <div class="icon">
                        <i class="fa fa-folder-open-o"></i>
                    </div>
                    <div class="section-header-text">
                        <h4>@lang('app.categories')</h4>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            @foreach($top_categories as $category)
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                    <div class="category-item category-single-box bg-white {{ $category->color_class }}">
                        <h4 class="green">
                            <a href="{{ route('listing') }}?category={{$category->id}}">
                                <i class="fa {{ $category->fa_icon }}"></i>
                                <span class="category-name">{{ $category->category_name }} </span>
                            </a>
                        </h4>
                        <hr />
                        <p class="count text-muted">({{ number_format($category->product_count) }})</p>
                        <p class="intro"> {{ $category->description }} </p>
                    </div>
                </div>
            @endforeach

        </div>
    </div>


    {{-- @if($enable_monetize)
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    {!! get_option('monetize_code_below_categories') !!}
                </div>
            </div>
        </div>
    @endif --}}
    <style>
        .m-auto{
            margin-right:auto;
            margin-left:auto;
        }
        </style>
    <div class='container'>
        <a class="btn btn-info">Submit Your listing</a>
        <div class='row'>
                <div class='col-md-6'>
                    <div class="jumbotron">
                        <h3>Please Wait!!</h3>
                        <p>
                                To submit your property, Are you ready with the followings?
                        </p>
                        <ol>
                            <li> at least 4 photos of your property </li>
                            <li> at least 1 legal document </li>
                            <li> proof of payment if you are vip seller? </li>
                        </ol>
                    </div>
                </div>
                <div class='col-md-6'></div>
        </div>
        </div>
    </div>


    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-header bg-white">
                    <div class="icon">
                        <i class="fa fa-star-o"></i>
                    </div>
                    <div class="section-header-text">
                        <h4 class="pull-left">@lang('app.new_premium_ads') </h4>
                        <a href="{{ route('listing') }}" class="btn pull-right">@lang('app.all_ads')</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">

            @foreach($premium_ads as $ad)
                <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">

                    <div itemscope itemtype="http://schema.org/Product" class="ads-item-thumbnail ad-box-{{$ad->price_plan}}">
                        <div class="ads-thumbnail">
                            <a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}">
                                <img itemprop="image"  src="{{ media_url($ad->feature_img) }}" class="img-responsive" alt="{{ $ad->title }}">
                            </a>
                        </div>
                        <div class="caption">
                            <h4><a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}"><span itemprop="name">{{ str_limit($ad->title, 40) }} </span></a></h4>
                            <a class="price text-muted" href="{{ route('listing', ['category' => $ad->category->id]) }}"> <i class="fa fa-folder-o"></i> {{ $ad->category->category_name }} </a>

                            @if($ad->city)
                            <a class="location text-muted" href="{{ route('listing', ['city' => $ad->city->id]) }}"> <i class="fa fa-location-arrow"></i> {{ $ad->city->city_name }} </a>
                            @endif
                            <p class="date-posted text-muted"> <i class="fa fa-clock-o"></i> {{ $ad->created_at->diffForHumans() }}</p>
                            <p class="price"> <span itemprop="price" content="{{$ad->price}}"> {{ themeqx_price_ng($ad->price, $ad->is_negotiable) }} </span></p>
                            <link itemprop="availability" href="http://schema.org/InStock" />
                        </div>

                        @if($ad->price_plan == 'premium')
                            <div class="label-premium" data-toggle="tooltip" data-placement="top" title="{{ ucfirst($ad->price_plan) }} ad"><i class="fa fa-star-o"></i> </div>
                        @endif
                    </div>
                </div>
            @endforeach

        </div>
    </div>


    {{-- @if($enable_monetize)
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    {!! get_option('monetize_code_below_premium_ads') !!}
                </div>
            </div>
        </div>
    @endif --}}


    @if($regular_ads->count() > 0)

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-header bg-white">
                        <div class="icon">
                            <i class="fa fa-star-o"></i>
                        </div>
                        <div class="section-header-text">
                            <h4 class="pull-left">@lang('app.new_regular_ads') </h4>
                            <a href="{{ route('listing') }}" class="btn pull-right">@lang('app.all_ads')</a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">

                @foreach($regular_ads as $ad)
                    <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                        <div itemscope itemtype="http://schema.org/Product" class="ads-item-thumbnail ad-box-{{$ad->price_plan}}">
                            <div class="ads-thumbnail">
                                <a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}">
                                    <img itemprop="image"  src="{{ media_url($ad->feature_img) }}" class="img-responsive" alt="{{ $ad->title }}">
                                </a>
                            </div>
                            <div class="caption">
                                <h4><a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}"><span itemprop="name">{{ str_limit($ad->title, 40) }} </span></a></h4>
                                <a class="price text-muted" href="{{ route('listing', ['category' => $ad->category->id]) }}"> <i class="fa fa-folder-o"></i> {{ $ad->category->category_name }} </a>
                                @if($ad->city)
                                <a class="location text-muted" href="{{ route('listing', ['city' => $ad->city->id]) }}"> <i class="fa fa-location-arrow"></i> {{ $ad->city->city_name }} </a>
                                @endif

                                <p class="date-posted text-muted"> <i class="fa fa-clock-o"></i> {{ $ad->created_at->diffForHumans() }}</p>
                                <p class="price"> <span itemprop="price" content="{{$ad->price}}"> {{ themeqx_price_ng($ad->price, $ad->is_negotiable) }} </span></p>
                                <link itemprop="availability" href="http://schema.org/InStock" />
                            </div>

                            @if($ad->price_plan == 'premium')
                                <div class="label-premium" data-toggle="tooltip" data-placement="top" title="{{ ucfirst($ad->price_plan) }} ad"><i class="fa fa-star-o"></i> </div>
                            @endif
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    @endif

    {{-- @if($enable_monetize)
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    {!! get_option('monetize_code_below_regular_ads') !!}
                </div>
            </div>
        </div>
    @endif --}}
    @if(get_option('show_latest_blog_in_homepage') ==1)

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-header bg-white">
                        <div class="icon">
                            <i class="fa fa-rss"></i>
                        </div>
                        <div class="section-header-text">
                            <h4 class="pull-left">@lang('app.latest_post_from_blog') </h4>
                            <a href="{{ route('blog') }}" class="btn pull-right">@lang('app.blog')</a>

                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row">
                <div class="home-latest-blog">
                    @foreach($posts as $post)
                        <div id="blog-listing" class="col-sm-6 col-xs-12">
                            <section class="post">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="image">
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
                                        <h2><a href="{{ route('blog_single', $post->slug) }}" class="blog-title">{{ $post->title }}</a></h2>
                                        <div class="clearfix">
                                            @if($post->author)
                                                <p class="author-category">By <a href="{{ route('author_blog_posts', $post->author->id) }}">{{ $post->author->name }}</a></p>
                                            @endif
                                            <p class="date-comments">
                                                <i class="fa fa-calendar"></i> {{ $post->created_at_datetime() }}
                                            </p>
                                        </div>
                                        <p class="intro"> {{ str_limit(strip_tags($post->post_content), 120) }} <a href="{{ route('blog_single', $post->slug) }}" >@lang('app.continue_reading')</a></p>
                                        <p></p>
                                    </div>
                                </div>
                            </section>

                        </div>
                    @endforeach
                    <div class="clearfix"></div>
                </div>

            </div>
        </div>

    @endif

@endsection

@section('page-js')

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
            }else if(fromLoad === 'state_to_city'){
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="" selected> @lang('app.select_city') </option>';
                    for ( i in jsonData){
                        option += '<option value="'+jsonData[i].id+'"> '+jsonData[i].city_name +' </option>';
                    }
                    $('#city_select').html(option);
                    $('#city_select').select2();
                }else {
                    $('#city_select').html('<option value="" selected> @lang('app.select_city') </option>');
                    $('#city_select').select2();
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

            $('[name="state"]').change(function(){
                var state_id = $(this).val();
                $('#loaderListingIcon').show();
                $.ajax({
                    type : 'POST',
                    url : '{{ route('get_city_by_state') }}',
                    data : { state_id : state_id,  _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        generate_option_from_json(data, 'state_to_city');
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