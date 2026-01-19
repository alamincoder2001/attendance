<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ip = session('device')->ipAddress ?? null;
        $attendances = getAttendance($ip);
        $userId = null;
        $dateFrom = "2026-01-01";
        $dateTo = date('Y-m-d');


        $userAttendance = array_values(array_filter($attendances, function ($log) use ($userId, $dateFrom, $dateTo) {
            $logDate = date('Y-m-d', strtotime($log['timestamp']));
            $condition = true;
            if ($userId !== null) {
                $condition = $condition && $log['id'] == $userId;
            }
            if ($dateFrom !== null) {
                $condition = $condition && $logDate >= $dateFrom;
            }
            if ($dateTo !== null) {
                $condition = $condition && $logDate <= $dateTo;
            }
            return $condition;
        }));

        $data['attendances'] = $userAttendance;
        return view('attendance.index', $data);
    }

    public function employee()
    {
        $ip = session('device')->ipAddress ?? null;
        $data['employees'] = getEmployee($ip);
        return view('attendance.employee', $data);
    }


    public function deviceAttendanceProcess(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = Session::get('device')->id ?? null;
            $device = Device::where('id', $id)->first();
            $attendances = getAttendance($device->ipAddress);
            $dateFrom = $request->dateFrom ?? '2026-01-01';
            $dateTo = $request->dateTo ?? date('Y-m-d');

            $signatures = array_values(array_filter($attendances, function ($log) use ($dateFrom, $dateTo) {
                $logDate = date('Y-m-d', strtotime($log['timestamp']));
                $condition = true;
                if ($dateFrom !== null) {
                    $condition = $condition && $logDate >= $dateFrom;
                }
                if ($dateTo !== null) {
                    $condition = $condition && $logDate <= $dateTo;
                }
                return $condition;
            }));

            foreach ($signatures as $signature) {
                $employee = DB::connection('mysql')->table('employees')->where('biometric_id', $signature['id'])->first();

                if ($employee) {
                    $att_details = array(
                        'employee_id'   => $employee->id,
                        'created_by'    => auth()->user()->id,
                        'branch_id'     => 1,
                        'ip_address'    => request()->ip(),
                        'date'          => Carbon::parse($signature['timestamp'])->format('Y-m-d'),
                        'punch_time'    => Carbon::parse($signature['timestamp'])->format('H:i:s'),
                        'device_id'     => $device->id,
                        'status'        => 'Machine',
                    );

                    $checkAttendance = DB::connection('mysql')->table('attendence_details')
                        ->where('employee_id', $employee->id)
                        ->where('date', Carbon::parse($signature['timestamp'])->format('Y-m-d'))
                        ->where("punch_time", Carbon::parse($signature['timestamp'])->format('H:i:s'))
                        ->first();
                    if (empty($checkAttendance)) {
                        DB::connection('mysql')->table('attendence_details')->insert($att_details);
                        attendenceMasterUpdate((object) $att_details);
                    }
                }
            }

            DB::commit();
            return response()->json(['message' => 'Attendance Processed']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => $e->getMessage()], 406);
        }
    }
}
