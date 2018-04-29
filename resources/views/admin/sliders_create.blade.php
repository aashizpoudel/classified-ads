@extends('layout.main')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('page-css')
    <link rel="stylesheet" href="{{ asset('assets/plugins/croppic/croppic.css') }}"/>
@endsection

@section('main')

    <div class="container">

        <div id="wrapper">
            @include('admin.sidebar_menu')

            <div id="page-wrapper">
                @if( ! empty($title))
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header"> {{ $title }}  </h1>
                        </div> <!-- /.col-lg-12 -->
                    </div> <!-- /.row -->
                @endif

                @include('admin.flash_msg')
                <div class="row">
                    <div class="col-sm-12">
                        <div id="cropContainerEyecandy"></div>
                    </div>
                </div>

            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->

    </div> <!-- /#container -->
@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/croppic/croppic.min.js') }}"></script>
    <script>
        var eyeCandy = $('#cropContainerEyecandy');
        var croppedOptions = {
            uploadUrl: '{{ route('create_slider') }}',
            cropUrl: '{{ route('create_crop') }}',
            loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> ',
            onAfterImgCrop:		function(){ location.href = '{{ route('slider') }}' },
            cropData:{
                'width' : (eyeCandy.width() * 2),
                'height': eyeCandy.height() * 2
            }
        };
        var cropperBox = new Croppic('cropContainerEyecandy', croppedOptions);
    </script>
@endsection