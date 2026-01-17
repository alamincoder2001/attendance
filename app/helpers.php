<?php

use Rats\Zkteco\Lib\ZKTeco;

//credentials check
function credentials($username, $password)
{
    if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        return ['email' => $username, 'password' => $password];
    } else {
        return ['username' => $username, 'password' => $password];
    }
}

function getAttendance($ip = '192.168.0.234')
{
    $zk = new ZKTeco($ip);

    if ($zk->connect()) {
        $allAttendance = $zk->getAttendance();
        $zk->disconnect();

        return $allAttendance;
    }
}

function getEmployee($ip = '192.168.0.234')
{
    $zk = new ZKTeco($ip);

    if ($zk->connect()) {
        $allEmployee = $zk->getUser();
        $zk->disconnect();
        return $allEmployee;
    }
}
