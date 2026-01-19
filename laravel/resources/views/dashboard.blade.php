@extends('layouts.master')
@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">Dashboard</h2>
            </div>
        </div>
    </div>
    <div class="row justify-content-center m-t-25">
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">
                        Total Employees
                    </strong>
                </div>
                <div class="card-body">
                    <div class="mx-auto d-block">
                        <i class="rounded-circle mx-auto d-block fas fa-users fa-5x"></i>
                        <div class="location text-sm-center">
                            <i>Total: </i> {{count($employees)}}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" onclick="location.href='/employee'" class="btn btn-primary w-100">
                        <i class="fas fa-list"></i>&nbsp; Show Employees
                    </button>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">
                        Attendance List
                    </strong>
                </div>
                <div class="card-body">
                    <div class="mx-auto d-block">
                        <i class="rounded-circle mx-auto d-block fas fa-list fa-5x"></i>
                        <div class="location text-sm-center">
                            ||
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" onclick="location.href='/attendance'" class="btn btn-primary w-100">
                        <i class="fas fa-list"></i>&nbsp; Show Attendance
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection