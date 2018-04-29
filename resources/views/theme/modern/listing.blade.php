@extends('layout.main')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('main')

    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="bg-white">
                    <div class="sidebar-filter-wrapper">

                        @if($enable_monetize)
                            {!! get_option('monetize_code_listing_sidebar_top') !!}
                        @endif

                        {{ Form::open([ 'method'=>'get', 'id' => 'listingFilterForm']) }}

                        <div class="row">
                            <div class="col-xs-12">
                                <p class="listingSidebarLeftHeader">@lang('app.filter_ads')
                                    <span id="loaderListingIcon" class="pull-right" style="display: none;"><i class="fa fa-spinner fa-spin"></i></span>
                                </p>
                            </div>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="q" value="{{ request('q') }}" placeholder="@lang('app.search___')" />
                        </div>

                        <hr />
                        <div class="form-group">
                            <select class="form-control select2" name="category">
                                <option value="">@lang('app.select_a_category')</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') ==  $category->id ? 'selected':'' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control select2" id="sub_category_select" name="sub_category">
                                <option value="">@lang('app.select_a_sub_category')</option>
                                @if($selected_categories)
                                    @foreach($selected_categories->sub_categories as $sub_category)
                                        <option value="{{ $sub_category->id }}" {{ request('sub_category') ==  $sub_category->id ? 'selected':'' }} >{{ $sub_category->category_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control select2" name="brand" id="brand_select">
                                <option value=""> @lang('app.select_a_brand') </option>
                                @if($selected_sub_categories)
                                    @foreach($selected_sub_categories->brands as $brand)
                                        <option value="{{ $brand->id }}" {{ request('brand') ==  $brand->id ? 'selected':'' }} >{{ $brand->brand_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <hr />

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
                                @if($selected_countries)
                                    @foreach($selected_countries->states as $state)
                                        <option value="{{ $state->id }}" {{ request('state') ==  $state->id ? 'selected':'' }} >{{ $state->state_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <div class="form-group">
                            <select class="form-control select2" id="city_select" name="city">
                                <option value=""> @lang('app.select_city') </option>
                                @if($selected_states)
                                    @foreach($selected_states->cities as $city)
                                        <option value="{{ $city->id }}" {{ request('city') ==  $city->id ? 'selected':'' }} >{{ $city->city_name }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <hr />
                        <div class="form-group">
                            <label>@lang('app.price_min_max')</label>

                            <div class="row">
                                <div class="col-xs-6">
                                    <input type="number" class="form-control" name="min_price" value="{{ request('min_price') }}" placeholder="@lang('app.min_price')" />
                                </div>
                                <div class="col-xs-6">
                                    <input type="number" class="form-control" name="max_price" value="{{ request('max_price') }}" placeholder="@lang('app.max_price')" />
                                </div>
                            </div>
                        </div>

                        <hr />
                        <div class="form-group">
                            <label>@lang('app.condition')</label>
                            <div class="checkbox">
                                <label>
                                    <input type="radio" name="condition" id="new" value="new" {{ request('condition') == 'new'? 'checked':'' }}>
                                    @lang('app.new')
                                </label>
                            </div>
                            <div class="checkbox">
                                <label>
                                    <input type="radio" name="condition" id="used" value="used" {{ request('condition') == 'used'? 'checked':'' }}>
                                    @lang('app.used')
                                </label>
                            </div>
                        </div>

                        <hr />
                        <div class="form-group">
                            <div class="row">
                                <div class=" col-sm-6 col-xs-12">
                                    <button class="btn btn-primary btn-block"><i class="fa fa-search"></i>  @lang('app.filter')</button>
                                </div>
                                <div class=" col-sm-6 col-xs-12">
                                    <a href="{{ route('listing') }}" class="btn btn-default btn-block"><i class="fa fa-refresh"></i>  @lang('app.reset')</a>
                                </div>
                            </div>
                        </div>

                        {{ Form::close() }}
                        <div class="clearfix"></div>

                        @if($enable_monetize)
                            {!! get_option('monetize_code_listing_sidebar_bottom') !!}
                        @endif

                    </div>

                </div>
            </div>

            <div class="col-md-9">
                <div class="row">
                    <div class="col-sm-12">

                        <?php
                        $allAdTab = route('listing').str_replace('/', '', str_replace(route('listing'), '', request()->fullUrlWithQuery(['adType'=>'all'])));
                        $personalAdTab = route('listing').str_replace('/', '', str_replace(route('listing'), '', request()->fullUrlWithQuery(['adType'=>'personal'])));
                        $businessAdTab = route('listing').str_replace('/', '', str_replace(route('listing'), '', request()->fullUrlWithQuery(['adType'=>'business'])));

                        ?>

                        <div class="listingTopFilterBar">
                            <ul class="filterAdType pull-left">
                                <li class="{{ request('adType') == false || request('adType') == 'all'? 'active':'' }}"><a href="{{ $allAdTab }}">@lang('app.all_ads') <small>({{ $personal_ads_count + $business_ads_count }})</small></a> </li>
                                <li class="{{ request('adType') == 'personal'? 'active':'' }}"><a href="{{ $personalAdTab }}">@lang('app.personal') <small>({{ $personal_ads_count }})</small></a> </li>
                                <li class="{{ request('adType') == 'business'? 'active':'' }}"><a href="{{ $businessAdTab }}">@lang('app.business') <small>({{ $business_ads_count }})</small></a> </li>
                            </ul>

                            <ul class="listingViewIcon pull-right">
                                <li class="dropdown shortByListingLi">
                                    <a aria-expanded="false" aria-haspopup="true" role="button" data-toggle="dropdown" class="dropdown-toggle" href="#">@lang('app.short_by') <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li><a href="{{ request()->fullUrlWithQuery(['shortBy'=>'price_high_to_low']) }}">@lang('app.price_high_to_low')</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['shortBy'=>'price_low_to_high']) }}">@lang('app.price_low_to_high')</a></li>
                                        <li><a href="{{ request()->fullUrlWithQuery(['shortBy'=>'latest']) }}">@lang('app.latest')</a></li>
                                    </ul>
                                </li>
                                <li><a href="javascript:void(0)" id="showGridView">
                                        <i class="fa fa-th-large"></i> </a> </li>
                                <li><a href="javascript:void(0)" id="showListView">
                                        <i class="fa fa-list"></i> </a> </li>
                            </ul>
                        </div>
                    </div>
                </div>


                @if($enable_monetize)
                    <div class="row">
                        <div class="col-sm-12">
                            {!! get_option('monetize_code_listing_above_premium_ads') !!}
                        </div>
                    </div>
                @endif


                <div class="ad-box-wrap">
                    @if( ! request('user_id'))
                        @if($premium_ads)
                            @if($premium_ads->count() > 0)
                                <div class="ad-box-premium-wrap">
                                    <div class="ad-box-grid-view" style="display: {{ session('grid_list_view') ? (session('grid_list_view') == 'grid'? 'block':'none') : 'block' }};">
                                        <div class="row">
                                            @foreach($premium_ads as $ad)
                                                @php session('grid_list_view') ? (session('grid_list_view') == 'grid'? $ad->increase_impression() :'none') : $ad->increase_impression(); @endphp

                                                <div class="col-md-4 col-sm-6 col-xs-12">
                                                    <div itemscope itemtype="http://schema.org/Product" class="ads-item-thumbnail ad-box-{{$ad->price_plan}}">
                                                        <div class="ads-thumbnail">
                                                            <a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}">
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
                                                            <h4><a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}"><span itemprop="name">{{ str_limit($ad->title, 40) }} </span></a></h4>
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
                                        </div>
                                    </div>
                                </div>


                                <div class="ad-box-list-view" style="display: {{ session('grid_list_view') == 'list'? 'block':'none' }};">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table class="table table-bordered table-responsive">
                                                @foreach($premium_ads as $ad)
                                                    @php session('grid_list_view') == 'list'? $ad->increase_impression() :'none' @endphp
                                                    <tr class="ad-{{ $ad->price_plan }}">
                                                        <td width="100">
                                                            <img src="{{ media_url($ad->feature_img) }}" class="img-responsive" alt="">
                                                            <span class="modern-img-indicator">
                                                                @if(! empty($ad->video_url))
                                                                    <i class="fa fa-file-video-o"></i>
                                                                @else
                                                                    <i class="fa fa-file-image-o"> {{ $ad->media_img->count() }}</i>
                                                                @endif
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <h5><a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" >{{ $ad->title }}</a> </h5>
                                                            <p class="text-muted">
                                                                @if($ad->city)
                                                                    <i class="fa fa-map-marker"></i> <a class="location text-muted" href="{{ route('listing', ['city'=>$ad->city->id]) }}"> {{ $ad->city->city_name }} </a>,
                                                                @endif
                                                                <i class="fa fa-clock-o"></i> {{ $ad->created_at->diffForHumans() }}
                                                            </p>
                                                        </td>
                                                        <td>
                                                            <p>
                                                                <a class="price text-muted" href="{{ route('listing', ['category' => $ad->category->id]) }}"> <i class="fa fa-folder-o"></i> {{ $ad->category->category_name }} </a>
                                                            </p>
                                                            <h5>{{ themeqx_price_ng($ad->price) }}</h5>
                                                            @if($ad->price_plan == 'premium')
                                                                <div class="ribbon-green-bar">{{ ucfirst($ad->price_plan) }}</div>
                                                            @endif
                                                            @if($ad->mark_ad_urgent == '1')
                                                                <div class="ribbon-red-bar">@lang('app.urgent')</div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endif



                    @if($enable_monetize)
                        <div class="row">
                            <div class="col-sm-12">
                                {!! get_option('monetize_code_listing_above_regular_ads') !!}
                            </div>
                        </div>
                    @endif


                    @if($ads->total() > 0)

                        <div class="ad-box-grid-view" style="display: {{ session('grid_list_view') ? (session('grid_list_view') == 'grid'? 'block':'none') : 'block' }};">

                            <div class="row">
                                @foreach($ads as $ad)
                                    <div class="col-md-4 col-sm-6 col-xs-12">
                                        <div itemscope itemtype="http://schema.org/Product" class="ads-item-thumbnail ad-box-{{$ad->price_plan}}">
                                            <div class="ads-thumbnail">
                                                <a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}">
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
                                                <h4><a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" title="{{ $ad->title }}"><span itemprop="name">{{ str_limit($ad->title, 40) }} </span></a></h4>
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
                            </div>
                        </div>

                        <div class="ad-box-list-view" style="display: {{ session('grid_list_view') == 'list'? 'block':'none' }};">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered table-responsive">
                                        @foreach($ads as $ad)
                                            <tr class="ad-{{ $ad->price_plan }}">
                                                <td width="100">
                                                    <img src="{{ media_url($ad->feature_img) }}" class="img-responsive" alt="">
                                                     <span class="modern-img-indicator">
                                                         @if(! empty($ad->video_url))
                                                             <i class="fa fa-file-video-o"></i>
                                                         @else
                                                             <i class="fa fa-file-image-o"> {{ $ad->media_img->count() }}</i>
                                                         @endif
                                                     </span>
                                                </td>
                                                <td>
                                                    <h5><a href="{{  route('single_ad', [$ad->id, $ad->slug]) }}" >{{ $ad->title }}</a> </h5>
                                                    <p class="text-muted">
                                                        @if($ad->city)
                                                            <i class="fa fa-map-marker"></i> <a class="location text-muted" href="{{ route('listing', ['city'=>$ad->city->id]) }}"> {{ $ad->city->city_name }} </a>,
                                                        @endif
                                                        <i class="fa fa-clock-o"></i> {{ $ad->created_at->diffForHumans() }}
                                                    </p>
                                                </td>
                                                <td>
                                                    @if($ad->category)
                                                        <p><a class="price text-muted" href="{{ route('listing', ['category' => $ad->category->id]) }}"> <i class="fa fa-folder-o"></i> {{ $ad->category->category_name }} </a></p>
                                                    @endif

                                                    <h5>{{ themeqx_price_ng($ad->price) }}</h5>

                                                    @if($ad->price_plan == 'premium')
                                                        <div class="ribbon-green-bar">{{ ucfirst($ad->price_plan) }}</div>
                                                    @endif
                                                    @if($ad->mark_ad_urgent == '1')
                                                        <div class="ribbon-red-bar">@lang('app.urgent')</div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($enable_monetize)
                        <div class="row">
                            <div class="col-sm-12">
                                {!! get_option('monetize_code_listing_below_regular_ads') !!}
                            </div>
                        </div>
                    @endif
                </div>


                <div class="row">
                    <div class="col-xs-12">
                        {{ $ads->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection


@section('page-js')
    <script>
        $(document).ready(function() {
            $('#shortBySelect').change(function () {
                var form_input = $('#listingFilterForm').serialize();
                location.href = '{{ route('listing') }}?' + form_input + '&shortBy=' + $(this).val();
            });
        });
        function generate_option_from_json(jsonData, fromLoad){
            //Load Category Json Data To Brand Select
            if (fromLoad === 'category_to_sub_category'){
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="" selected> <?php echo trans('app.select_a_sub_category') ?> </option>';
                    for ( i in jsonData){
                        option += '<option value="'+jsonData[i].id+'"> '+jsonData[i].category_name +' </option>';
                    }
                    $('#sub_category_select').html(option);
                    $('#sub_category_select').select2();
                }else {
                    $('#sub_category_select').html('<option value="">@lang('app.select_a_sub_category')</option>');
                    $('#sub_category_select').select2();
                }
                $('#loaderListingIcon').hide('slow');
            }else if (fromLoad === 'category_to_brand'){
                var option = '';
                if (jsonData.length > 0) {
                    option += '<option value="" selected> <?php echo trans('app.select_a_brand') ?> </option>';
                    for ( i in jsonData){
                        option += '<option value="'+jsonData[i].id+'"> '+jsonData[i].brand_name +' </option>';
                    }
                    $('#brand_select').html(option);
                    $('#brand_select').select2();
                }else {
                    $('#brand_select').html('<option value="">@lang('app.select_a_brand')</option>');
                    $('#brand_select').select2();
                }
                $('#loaderListingIcon').hide('slow');
            }else if(fromLoad === 'country_to_state'){
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

        $(function(){
            $('[name="category"]').change(function(){
                var category_id = $(this).val();
                $('#loaderListingIcon').show();
                //window.history.pushState("", "", 'newpage');
                $.ajax({
                    type : 'POST',
                    url : '{{ route('get_sub_category_by_category') }}',
                    data : { category_id : category_id,  _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        generate_option_from_json(data, 'category_to_sub_category');
                    }
                });
            });

            $('[name="sub_category"]').change(function(){
                var category_id = $(this).val();
                $('#loaderListingIcon').show();

                $.ajax({
                    type : 'POST',
                    url : '{{ route('get_brand_by_category') }}',
                    data : { category_id : category_id,  _token : '{{ csrf_token() }}' },
                    success : function (data) {
                        generate_option_from_json(data, 'category_to_brand');
                    }
                });
            });

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

        $(function(){
            $('#showGridView').click(function(){
                $('.ad-box-grid-view').show();
                $('.ad-box-list-view').hide();
                $.ajax({
                    type : 'POST',
                    url : '{{ route('switch_grid_list_view') }}',
                    data : { grid_list_view : 'grid',  _token : '{{ csrf_token() }}' },
                });
            });
            $('#showListView').click(function(){
                $('.ad-box-grid-view').hide();
                $('.ad-box-list-view').show();
                $.ajax({
                    type : 'POST',
                    url : '{{ route('switch_grid_list_view') }}',
                    data : { grid_list_view : 'list',  _token : '{{ csrf_token() }}' },
                });
            });
        });
    </script>


    <script>
        @if(session('success'))
            toastr.success('{{ session('success') }}', '<?php echo trans('app.success') ?>', toastr_options);
        @endif
    </script>
@endsection