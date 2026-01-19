@extends('layouts.master')
@section('title', 'Device Create')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="overview-wrap">
                <h2 class="title-1">Device Create</h2>
                <button type="button" onclick="location.href = '/device-dashboard'" class="au-btn au-btn-icon au-btn--blue">
                    <i class="zmdi zmdi-home"></i>Back To Dashboard</button>
            </div>
        </div>
    </div>
    <div class="row m-t-25">
        <div class="col-12 col-md-6 offset-md-3">
            <div class="card">
                <div class="card-header">
                    <strong>Create Attendance Device</strong>
                </div>
                <div class="card-body">
                    <form onsubmit="addDevice(event)">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="form-control-label">Device Name</label>
                            <input type="text" id="name" name="name" class="form-control shadow-none">
                        </div>
                        <div class="form-group">
                            <label for="ipAddress" class="form-control-label">IP Address</label>
                            <input type="text" id="ipAddress" name="ipAddress" class="form-control shadow-none">
                        </div>
                        <div class="form-group mb-2">
                            <label for="port" class="form-control-label">Port</label>
                            <input type="text" id="port" name="port" class="form-control shadow-none">
                        </div>
                        <div class="form-group mb-2">
                            <label for="serial_number" class="form-control-label">Serial Number</label>
                            <input type="text" id="serial_number" name="serial_number" class="form-control shadow-none">
                        </div>
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary">Save Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12">
            <div class="row">
                <div class="col-12 col-sm-6 col-md-3 mb-1">
                    <input type="search" oninput="searchData()" class="form-control shadow-none rounded" placeholder="Search...">
                </div>
            </div>
            <div class="table-responsive table--no-card m-b-30">
                <table class="table table-borderless table-striped table-earning">
                    <thead>
                        <tr>
                            <th>Sl.</th>
                            <th>Device Name</th>
                            <th>Type</th>
                            <th>Port</th>
                            <th>IpAddress</th>
                            <th>Serial</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($devices) > 0)
                        @foreach($devices as $index => $device)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $device->name }}</td>
                            <td>{{ $device->type }}</td>
                            <td>{{ $device->port }}</td>
                            <td>{{ $device->ipAddress }}</td>
                            <td>{{ $device->serial_number }}</td>
                            <td>
                                <button type="button" onclick="editData({{ $device }})" class="btn btn-sm btn-primary">Edit</button>
                                <button type="button" onclick="deleteData({{$device->id}})" class="btn btn-sm btn-danger">
                                    @if($device->deleted_at == null)
                                    Delete
                                    @else
                                    Forced Delete
                                    @endif
                                </button>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6" class="text-center">No devices found.</td>
                        </tr>
                        @endif
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

        let notFoundRow = table.querySelector('.not-found-row');
        if (!hasResult && filter.length > 0) {
            if (!notFoundRow) {
                let tbody = table.querySelector('tbody');
                notFoundRow = tbody.insertRow();
                notFoundRow.className = 'not-found-row';
                let cell = notFoundRow.insertCell(0);
                cell.colSpan = 6;
                cell.textContent = 'No data found';
                cell.style.textAlign = 'center';
            }
        } else {
            if (notFoundRow) notFoundRow.remove();
        }
    }

    // Add Device
    function addDevice(event) {
        event.preventDefault();

        let name = document.getElementById('name').value;
        let ipAddress = document.getElementById('ipAddress').value;
        let port = document.getElementById('port').value;

        if (!name || !ipAddress || !port) {
            alert('Please fill all fields');
            return;
        }

        fetch('/add-device', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    name: name,
                    ipAddress: ipAddress,
                    port: port
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Device added successfully.');
                    document.querySelector('form').reset();
                    location.reload();
                } else {
                    alert('Error adding device.');
                }
            });
    }

    // Edit Device
    function editData(device) {
        let editForm = `
            <div class="modal fade" id="editModal" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Device</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Device Name</label>
                                <input type="text" id="editName" class="form-control" value="${device.name}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">IP Address</label>
                                <input type="text" id="editIpAddress" class="form-control" value="${device.ipAddress}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Port</label>
                                <input type="text" id="editPort" class="form-control" value="${device.port}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Serial Number</label>
                                <input type="text" id="editSerialNumber" class="form-control" value="${device.serial_number}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" onclick="saveEdit(${device.id})">Update</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', editForm);
        new bootstrap.Modal(document.getElementById('editModal')).show();
    }

    function saveEdit(deviceId) {
        let name = document.getElementById('editName').value;
        let ipAddress = document.getElementById('editIpAddress').value;
        let port = document.getElementById('editPort').value;
        let serialNumber = document.getElementById('editSerialNumber').value;

        if (!name || !ipAddress || !port || !serialNumber) {
            alert('Please fill all fields');
            return;
        }

        fetch('/update-device', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id: deviceId,
                    name: name,
                    ipAddress: ipAddress,
                    port: port,
                    serial_number: serialNumber
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Device updated successfully.');
                    location.reload();
                } else {
                    alert('Error updating device.');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    // Delete Device
    function deleteData(deviceId) {
        if (!confirm('Are you sure you want to delete this device?')) return;

        fetch('/delete-device', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id: deviceId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Device deleted successfully.');
                    location.reload();
                } else {
                    alert('Error deleting device.');
                }
            })
            .catch(error => console.error('Error:', error));
    }
</script>
@endpush