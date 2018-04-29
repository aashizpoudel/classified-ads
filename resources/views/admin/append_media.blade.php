@if($ads_images->count() > 0)
    @foreach($ads_images as $img)
        <div class="creating-ads-img-wrap">
            <img src="{{ media_url($img, false) }}" class="img-responsive" />
            <div class="img-action-wrap" id="{{ $img->id }}">
                <a href="javascript:;" class="imgDeleteBtn"><i class="fa fa-trash-o"></i> </a>
                <a href="javascript:;" class="imgFeatureBtn"><i class="fa fa-star{{ $img->is_feature ==1 ? '':'-o' }}"></i> </a>
            </div>
        </div>
    @endforeach
@endif