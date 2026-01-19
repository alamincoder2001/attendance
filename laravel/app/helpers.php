<?php

use Rats\Zkteco\Lib\ZKTeco;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

//credentials check
function credentials($username, $password)
{
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        return ['email' => $username, 'password' => $password];
    } else {
        return ['username' => $username, 'password' => $password];
    }
}

function getAttendance($ip = '192.168.0.234', $port = 4370)
{
    $zk = new ZKTeco($ip, $port);

    if ($zk->connect()) {
        $allAttendance = $zk->getAttendance();
        $zk->disconnect();

        return $allAttendance;
    }
}

function getEmployee($ip = '192.168.0.234', $port = 4370)
{
    $zk = new ZKTeco($ip, $port);

    if ($zk->connect()) {
        $allEmployee = $zk->getUser();
        $zk->disconnect();
        return $allEmployee;
    }
}


//attendance master update
function attendenceMasterUpdate($attendance, $option = null)
{
    $employee = DB::connection('mysql')
        ->table('employees')
        ->leftJoin('shifts as s', 'employees.shift_id', '=', 's.id')
        ->select('employees.*', 's.name as shift_name', 's.start_at', 's.end_at', 's.late_time', 's.absent_time')
        ->where('employees.id', $attendance->employee_id)
        ->first();
    $master = DB::connection('mysql')->table('attendence_masters')->where('employee_id', $attendance->employee_id)
        ->whereDate('date', '=', $attendance->date)
        ->first();

    list($status, $late_time, $ot_time) = attendanceOperation($employee, $attendance, $master, $option);

    if ($master) {
        $update_data = array(
            'attendence_from'   => $attendance->status,
            'updated_by'        => auth()->user()->id,
        );

        if (
            $option == 'in_time' ||
            (
                is_null($option) &&
                timeCalculation(
                    $attendance->punch_time,
                    $master->in_time,
                    (object) ['comparison' => 'less_than_equal']
                )
            )
        ) {
            $update_data['status'] = $status;
            $update_data['late_time'] = $late_time;
            $update_data['in_time'] = $attendance->punch_time;
        } else {
            $update_data['ot_time'] = $ot_time;
            $update_data['out_time'] = $attendance->punch_time;
        }

        $master->update($update_data);
        return;
    }



    if (!is_null($option) && $option == 'out_time') {
        $in_time = null;
        $out_time = $attendance->punch_time;
    } else {
        $in_time = $attendance->punch_time;
        $out_time = null;
    }

    $insert_data = array(
        'employee_id'       => $attendance->employee_id,
        'date'              => $attendance->date,
        'in_time'           => $in_time,
        'out_time'          => $out_time,
        'late_time'         => $late_time,
        'created_by'        => auth()->user()->id,
        'ip_address'        => request()->ip(),
        'branch_id'         => 1,
        'status'            => $status,
        'attendence_from'   => $attendance->status,
    );
    DB::connection('mysql')->table('attendence_masters')->insert($insert_data);
}


function timeCalculation($time1, $time2, $option = null)
{
    if (empty($time1)) {
        $time1 = '00:00:00';
    }
    if (empty($time2)) {
        $time2 = '00:00:00';
    }
    $time1 = Carbon::parse($time1);
    $time2 = Carbon::parse($time2);

    if (!is_null($option)) {
        switch ($option) {
            case isset($option->sum):
                return $time1->addHours($time2->format('H'))
                    ->addMinutes($time2->format('i'))
                    ->addSeconds($time2->format('s'))
                    ->format('H:i:s');

            case isset($option->diff):
                return gmdate('H:i:s', $time1->diffInSeconds($time2));

            case isset($option->comparison):
                switch ($option->comparison) {
                    case 'equal':
                        return $time1 == $time2;

                    case 'not_equal':
                        return $time1 != $time2;

                    case 'greater_than':
                        return $time1 > $time2;

                    case 'greater_than_equal':
                        return $time1 >= $time2;

                    case 'less_than':
                        return $time1 < $time2;

                    case 'less_than_equal':
                        return $time1 <= $time2;

                    default:
                        return false;
                }

            default:
                return;
        }
    }
}

function attendanceOperation($employee, $attendance, $att_master, $option)
{
    $punch_time     = $attendance->punch_time;
    $late_from      = $employee->late_time;
    $absent_from    = $employee->absent_time;
    $shift_start    = $employee->start_at;
    $shift_end      = $employee->end_at;
    $day_name       = Carbon::parse($attendance->date)->format('l');

    $status = 'P';
    $late_time = null;
    $ot_time = null;

    $is_holiday = is_holiday($attendance->date);
    $is_weekend = is_weekend($day_name);

    if ($is_holiday || $is_weekend) {
        if (
            $att_master &&
            $employee->ot_allowed &&
            (is_null($option) || $option == 'out_time') &&
            timeCalculation(
                $punch_time,
                $att_master->in_time,
                (object) ['comparison' => 'greater_than']
            )
        ) {
            $ot_time = timeCalculation($punch_time, $att_master->in_time, (object) ['diff' => true]);
        }
        $status = 'WH';
        if ($is_holiday) {
            $status = 'H';
        }
    } else {
        if ((is_null($option) || $option == 'in_time')) {
            if (
                $employee->shift->absent_on &&
                !$employee->absence_allowed &&
                timeCalculation(
                    $punch_time,
                    $absent_from,
                    (object) ['comparison' => 'greater_than']
                )
            ) {
                $status = 'LA';
                $late_time = timeCalculation($punch_time, $shift_start, (object) ['diff' => true]);
            } elseif (
                !$employee->late_allowed &&
                timeCalculation(
                    $punch_time,
                    $late_from,
                    (object) ['comparison' => 'greater_than']
                )
            ) {
                $status = 'L';
                $late_time = timeCalculation($punch_time, $shift_start, (object) ['diff' => true]);
            }
        }

        if (
            $employee->ot_allowed &&
            (is_null($option) || $option == 'out_time') &&
            timeCalculation(
                $punch_time,
                $shift_end,
                (object) ['comparison' => 'greater_than']
            )
        ) {
            $ot_time = timeCalculation($punch_time, $shift_end, (object) ['diff' => true]);
        }
    }

    return [$status, $late_time, $ot_time];
}

function is_holiday($date, $return_data = false)
{
    $holiday = DB::connection('mysql')->table('holidays')->whereDate('start', '<=', $date)->whereDate('end', '>=', $date)->where('branch_id', session('branch_id'));
    if ($return_data) {
        return $holiday->get();
    }
    return $holiday->exists();
}

function is_weekend($day_name)
{
    return in_array($day_name, getWeekends());
}

function getWeekends()
{
    return DB::connection('mysql')->table('company_profiles')->select('weekend')->first()->weekend;
}
