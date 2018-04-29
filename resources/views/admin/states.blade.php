@extends('layout.main')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('page-css')
    <link href="{{ asset('assets/plugins/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" />
@stop

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
                    <div class="col-sm-8 col-sm-offset-1 col-xs-12">

                        {{ Form::open(['class' => 'form-horizontal']) }}

                        <div class="form-group">
                            <label for="country_id" class="col-sm-4 control-label">@lang('app.select_a_category')</label>

                            <div class="col-sm-8 {{ $errors->has('country_id')? 'has-error':'' }}">
                                <select class="form-control select2" name="country_id">
                                    <option value="">@lang('app.select_country')</option>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('country_id')? '<p class="help-block">'.$errors->first('country_id').'</p>':'' !!}

                            </div>
                        </div>

                        <div class="form-group {{ $errors->has('state_name')? 'has-error':'' }}">
                            <label for="state_name" class="col-sm-4 control-label">@lang('app.state_name')</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="state_name" value="{{ old('state_name') }}" name="state_name" placeholder="@lang('app.state_name')">
                                {!! $errors->has('state_name')? '<p class="help-block">'.$errors->first('state_name').'</p>':'' !!}

                            </div>
                        </div>


                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" class="btn btn-primary">@lang('app.save_new_state')</button>
                            </div>
                        </div>
                        {{ Form::close() }}

                    </div>

                </div>

                <hr />



                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-bordered table-striped" id="jDataTable">
                            <thead>
                            <tr>
                                <th>@lang('app.state_name')</th>
                                <th>@lang('app.country_name')</th>
                                <th>@lang('app.actions')</th>
                            </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection

@section('page-js')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#jDataTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('get_state_data') }}',
                "columns": [
                    //title will auto-generate th columns
                    {"data": "state_name", "name": "state_name"},
                    {"data": "country_name", "name": "country_name"},
                    {"data": "actions", "name": "actions", "orderable": false, "searchable": false}

                ],
                "aaSorting": []
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('body').on('click', '.deleteState', function (e) {
                if (!confirm("Are you sure? its can't be undone")) {
                    e.preventDefault();
                    return false;
                }
                var selector = $(this);
                var data_id = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: '{{ route('delete_state') }}',
                    data: {state_id: data_id, _token: '{{ csrf_token() }}'},
                    success: function (data) {
                        if (data.success == 1) {
                            selector.closest('tr').hide('slow');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });
        });
    </script>
@endsection