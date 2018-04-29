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
                            <h1 class="page-header"> {{ $title }}  </h1>
                        </div> <!-- /.col-lg-12 -->
                    </div> <!-- /.row -->
                @endif

                @include('admin.flash_msg')


                <div class="row">
                    <div class="col-xs-12">

                        @if($reports->total() > 0)
                            <table class="table table-bordered table-striped table-responsive">

                                <tr>
                                    <th>@lang('app.reason')</th>
                                    <th>@lang('app.email')</th>
                                    <th>@lang('app.message')</th>
                                    <th>@lang('app.ad_info')</th>
                                    <th>@lang('app.action')</th>
                                </tr>

                                @foreach($reports as $report)
                                    <tr>

                                        <td>{{ $report->reason }}</td>
                                        <td> {{ $report->email }}  </td>
                                        <td>
                                            {{ $report->message }}

                                            <hr />
                                            <p class="text-muted"> <i>@lang('app.date_time'): {{ $report->posting_datetime() }}</i></p>
                                        </td>
                                        <td>
                                            @if($report->ad)
                                            <a href="{{ route('single_ad', [$report->ad->id, $report->ad->slug]) }}" target="_blank">@lang('app.view_ad')</a>

                                            <i class="clearfix"></i>
                                            <a href="{{ route('reports_by_ads', $report->ad->slug) }}">
                                                <i class="fa fa-exclamation-triangle"></i> @lang('app.reports') : {{ $report->ad->reports->count() }}
                                            </a>
                                                @endif
                                        </td>
                                        <td>
                                            <a href="javascript:;" class="btn btn-danger deleteReport" data-id="{{ $report->id }}"><i class="fa fa-trash"></i> </a>
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        @endif

                        {!! $reports->links() !!}

                    </div>
                </div>

            </div>   <!-- /#page-wrapper -->

        </div>   <!-- /#wrapper -->


    </div> <!-- /#container -->
@endsection

@section('page-js')

    <script>
        $(document).ready(function() {
            $('.deleteReport').on('click', function () {
                if (!confirm('{{ trans('app.are_you_sure') }}')) {
                    return '';
                }
                var selector = $(this);
                var id = selector.data('id');
                $.ajax({
                    url: '{{ route('delete_report') }}',
                    type: "POST",
                    data: {id: id, _token: '{{ csrf_token() }}'},
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

    <script>
        @if(session('success'))
            toastr.success('{{ session('success') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
        @if(session('error'))
            toastr.error('{{ session('error') }}', '{{ trans('app.success') }}', toastr_options);
        @endif
    </script>

@endsection