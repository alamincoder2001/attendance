<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $attendances = getAttendance();
        $userId = "100023";
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
        $data['employees'] = getEmployee();
        return view('attendance.employee', $data);
    }
}
