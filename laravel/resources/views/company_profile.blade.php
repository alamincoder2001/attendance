@extends('layouts.master')
@section('title', 'Company Profile')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">Company Profile</h2>
                <button type="button" onclick="location.href = '/device-dashboard'" class="au-btn au-btn-icon au-btn--blue">
                    <i class="zmdi zmdi-home"></i>Back To Dashboard</button>
            </div>
        </div>
    </div>
    <div class="row m-t-25">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <strong>Update Company Profile</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('company.profile.update') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-control-label">Company Name</label>
                            <input type="text" id="name" name="name" class="form-control shadow-none" value="{{ $company->name }}">
                        </div>
                        <div class="form-group">
                            <label for="domain" class="form-control-label">Domain</label>
                            <input type="text" id="domain" name="domain" class="form-control shadow-none" value="{{ $company->domain }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="database" class="form-control-label">Database</label>
                            <input type="text" id="database" name="database" class="form-control shadow-none" value="{{ $company->database }}">
                        </div>

                        <div class="form-group mb-2">
                            <label for="username" class="form-control-label">Username</label>
                            <input type="text" id="username" name="username" class="form-control shadow-none" value="{{ $company->username }}">
                        </div>
                        <div class="form-group mb-2">
                            <label for="password" class="form-control-label">Password</label>
                            <input type="text" id="password" name="password" class="form-control shadow-none" value="{{ $company->password }}">
                        </div>
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary">Update Company Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection