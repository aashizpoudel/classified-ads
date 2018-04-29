@extends('layout.main')

@section('main')

    <div class="container">

    <div id="wrapper">

        @include('admin.sidebar_menu')

        <div id="page-wrapper">

            @if(session('error'))
                <div class="row">
                    <div class="col-lg-12">
                        <br />
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">@lang('app.dashboard')</h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-green">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="huge">{{ $approved_ads }}</div>
                                    <div>@lang('app.approved_ads')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="huge">{{ $pending_ads }}</div>
                                    <div>@lang('app.pending_ads')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-red">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="huge">{{ $blocked_ads }}</div>
                                    <div>@lang('app.blocked_ads')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($ten_contact_messages)
                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="huge">{{ $total_users }}</div>
                                    <div>@lang('app.users')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="panel panel-yellow">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="huge">{{ $total_reports }}</div>
                                    <div>@lang('app.reports')</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

            </div>
            <!-- /.row -->

                @if($lUser->is_admin())
            <div class="row">
                @if($ten_contact_messages)
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            @lang('app.latest_ten_contact_messages')
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>@lang('app.sender')</th>
                                    <th>@lang('app.message')</th>
                                </tr>

                                @foreach($ten_contact_messages as $message)
                                    <tr>
                                        <td>
                                            <i class="fa fa-user"></i> {{ $message->name }} <br />
                                            <i class="fa fa-envelope-o"></i> {{ $message->email }} <br />
                                            <i class="fa fa-clock-o"></i> {{ $message->created_at->diffForHumans() }}
                                        </td>
                                        <td>{{ $message->message }}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
                @endif

                @if($reports)
                <div class="col-xs-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            @lang('app.latest_ten_ads_report')
                        </div>
                        <div class="panel-body">

                            @if($reports->count() > 0)
                                <table class="table table-bordered table-striped table-responsive">
                                    <tr>
                                        <th>@lang('app.reason')</th>
                                        <th>@lang('app.email')</th>
                                        <th>@lang('app.message')</th>
                                        <th>@lang('app.ad_info')</th>
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
                                        </tr>
                                    @endforeach
                                </table>
                            @endif

                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif



        </div>
        <!-- /#page-wrapper -->
    </div>
    <!-- /#wrapper -->

    </div> <!-- /#container -->
@endsection