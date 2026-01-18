@extends('layouts.master')
@section('title', 'Attendance List')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">overview</h2>
                <button class="au-btn au-btn-icon au-btn--blue">
                    <i class="zmdi zmdi-home"></i>Back To Dashboard</button>
            </div>
        </div>
    </div>
    <div class="row m-t-25">
        <div class="col-sm-6 col-lg-12">
            <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>User ID</th>
                            <th>Date</th>
                            <th>Day</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $key => $attendance)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $attendance['id'] }}</td>
                            <td>{{ date('d/m/Y', strtotime($attendance['timestamp'])) }}</td>
                            <td>{{ date('l', strtotime($attendance['timestamp'])) }}</td>
                            <td>{{ date('H:i:s', strtotime($attendance['timestamp'])) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection