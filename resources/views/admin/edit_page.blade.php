@extends('layout.main')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection


@section('main')
    <div class="container">
        <div id="wrapper">
            @include('admin.sidebar_menu')
            <div id="page-wrapper">
                @if( ! empty($title))
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header"> {{ $title }} <a href="{{ route('create_new_page') }}" class="btn btn-info pull-right"> <i class="fa fa-floppy-o"></i> @lang('app.create_new_page')</a>
                            </h1>
                        </div> <!-- /.col-lg-12 -->
                    </div> <!-- /.row -->
                @endif

                @include('admin.flash_msg')

                <div class="row">
                    <div class="col-xs-12">

                        {{ Form::open(['class' => 'form-horizontal']) }}

                        <div class="form-group {{ $errors->has('title')? 'has-error':'' }}">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="title" value="{{ old('title')?old('title'): $page->title }}" name="title" placeholder="@lang('app.title')">
                                {!! $errors->has('title')? '<p class="help-block">'.$errors->first('title').'</p>':'' !!}
                            </div>
                        </div>


                        <div class="form-group {{ $errors->has('post_content')? 'has-error':'' }}">
                            <div class="col-sm-12">
                                <textarea name="post_content" id="post_content" class="form-control">{!!  old('post_content')? old('post_content'): $page->post_content !!}</textarea>
                                {!! $errors->has('post_content')? '<p class="help-block">'.$errors->first('post_content').'</p>':'' !!}
                            </div>
                        </div>



                        <div class="form-group">
                            <div class="col-md-6">
                                <label for="show_in_header_menu" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="show_in_header_menu" name="show_in_header_menu" {{ $page->show_in_header_menu? 'checked':'' }}>
                                    @lang('app.show_in_header_menu')
                                </label>
                            </div>
                            <div class="col-md-6">
                                <label for="show_in_footer_menu" class="checkbox-inline">
                                    <input type="checkbox" value="1" id="show_in_footer_menu" name="show_in_footer_menu"  {{ $page->show_in_footer_menu? 'checked':'' }}>
                                    @lang('app.show_in_footer_menu')
                                </label>
                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-9">
                                <button type="submit" class="btn btn-primary">@lang('app.update_page')</button>
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>

                </div>


            </div>   <!-- /#page-wrapper -->




        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace( 'post_content' );
    </script>
    <script>
        $(document).ready(function() {
            $('.btn-danger').on('click', function (e) {
                if (!confirm("Are you sure? its can't be undone")) {
                    e.preventDefault();
                    return false;
                }

                var selector = $(this);
                var data_id = $(this).data('id');

                $.ajax({
                    type: 'POST',
                    url: '{{ route('delete_categories') }}',
                    data: {data_id: data_id, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.success == 1) {
                            selector.closest('div').hide('slow');
                        }
                    }
                });
            });
        });
    </script>
@endsection