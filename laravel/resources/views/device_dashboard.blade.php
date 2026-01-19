@extends('layouts.master')
@section('title', 'Device List')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">Device List</h2>
                <button type="button" onclick="location.href='/device'" class="au-btn au-btn-icon au-btn--blue">
                    <i class="zmdi zmdi-plus"></i>add device</button>
            </div>
        </div>
    </div>
    <div class="row justify-content-center m-t-25">
        @if(count($devices) > 0)
        @foreach($devices as $device)
        <div class="col-12 col-sm-6 col-lg-4 col-xl-3 mb-3">
            <div class="card">
                <div class="card-header">
                    <strong class="card-title">{{ $device->name }}
                        <small>
                            <span class="badge badge {{ $device->status == 'a' ? 'bg-success' : 'bg-danger' }} float-end mt-1">{{ $device->status == 'a' ? 'Active' : 'Deactive' }}</span>
                        </small>
                    </strong>
                </div>
                <div class="card-body">
                    <div class="mx-auto d-block">
                        <i class="rounded-circle mx-auto d-block fab fa-microsoft fa-5x"></i>
                        <div class="location text-sm-center">
                            <i>IP: </i> {{ $device->ipAddress }} || <i>Port: </i> {{ $device->port }}
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    @if($device->status == 'p')
                    <button type="button" onclick="location.href='/dashboard/{{ $device->id }}'" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane"></i>&nbsp; Active Device
                    </button>
                    @else
                    <button type="button" onclick="location.href='/dashboard'" class="btn btn-success w-100">
                        <i class="fas fa-paper-plane"></i>&nbsp; Show Info
                    </button>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-12 col-md-12 text-center">
            <div class="alert alert-info" role="alert">
                No devices found. Please add a device.
            </div>
        </div>
        @endif
    </div>
</div>
@endsection