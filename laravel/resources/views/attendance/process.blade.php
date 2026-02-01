@extends('layouts.master')
@section('title', 'Attendance Process')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">Attendance Process</h2>
                <button type="button" onclick="location.href = '/device-dashboard'" class="au-btn au-btn-icon au-btn--blue">
                    <i class="zmdi zmdi-home"></i>Back To Dashboard</button>
            </div>
        </div>
    </div>
    <div class="row m-t-25">
        <div class="col-12 col-md-4 offset-md-4">
            <div class="card">
                <div class="card-body">
                    <form onsubmit="process(event)">
                        <div class="form-group mb-3">
                            <label for="dateFrom">From</label>
                            <input type="date" class="form-control" name="dateFrom" id="dateFrom" value="{{date('Y-m-d')}}" />
                        </div>
                        <div class="form-group mb-3">
                            <label for="dateTo">To</label>
                            <input type="date" class="form-control" name="dateTo" id="dateTo" value="{{date('Y-m-d')}}" />
                        </div>
                        <!-- <div class="form-group mb-3">
                            <div class="btn-group-toggle" data-toggle="buttons">
                                <label class="btn btn-secondary" for="is_remove">
                                    <input type="checkbox" name="is_remove" true_value="yes" false_value="no" id="is_remove"> Machine Data Remove
                                </label>
                            </div>
                        </div> -->
                        <div class="form-group mb-3">
                            <button type="submit" class="btn btn-success py-3 w-100 d-flex justify-content-center gap-3" id="processBtn">
                                <span id="loadingSpinner" style="display:none;">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </span>
                                Process Attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push("script")
<script>
    async function process(event) {
        event.preventDefault();

        const button = document.getElementById('processBtn');
        const spinner = document.getElementById('loadingSpinner');

        button.disabled = true;
        spinner.style.display = 'block';

        let formdata = new FormData(event.target);
        fetch('/save-attendance-process', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formdata
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                button.disabled = false;
                spinner.style.display = 'none';
                location.reload();
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
                spinner.style.display = 'none';
            });
    }
</script>
@endpush