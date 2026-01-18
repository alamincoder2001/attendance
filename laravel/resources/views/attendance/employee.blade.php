@extends('layouts.master')
@section('title', 'Employee List')

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
            <div class="row">
                <div class="col-sm-12 col-md-3 mb-1">
                    <input type="search" oninput="searchData()" class="form-control shadow-none rounded" placeholder="Search...">
                </div>
            </div>
            <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>UserID</th>
                            <th>Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($employees as $key => $employee)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $employee['userid'] }}</td>
                            <td>{{ $employee['name'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push("script")
<script>
    function searchData() {
        let input = document.querySelector('input[type="search"]');
        let filter = input.value.toUpperCase();
        let table = document.querySelector('table');
        let tr = table.getElementsByTagName("tr");
        let hasResult = false;

        for (let i = 1; i < tr.length; i++) {
            let td = tr[i].getElementsByTagName("td");
            let found = false;

            for (let j = 0; j < td.length; j++) {
                let txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }

            if (found) {
                tr[i].style.display = "";
                hasResult = true;
            } else {
                tr[i].style.display = "none";
            }
        }

        if (!hasResult && filter.length > 0) {
            let tbody = table.querySelector('tbody');
            let existingNotFound = tbody.querySelector('.not-found-row');
            if (!existingNotFound) {
                let notFoundRow = tbody.insertRow();
                notFoundRow.className = 'not-found-row';
                let cell = notFoundRow.insertCell(0);
                cell.colSpan = 5;
                cell.textContent = 'No data found';
                cell.style.textAlign = 'center';
            }
        } else {
            let notFoundRow = table.querySelector('.not-found-row');
            if (notFoundRow) notFoundRow.remove();
        }
    }
</script>
@endpush