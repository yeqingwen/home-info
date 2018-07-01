<?php
require_once('../lib/Meter.php');
require_once('../lib/User.php');

define('MAX_USER', 1800);
define('MAX_METER', 2078);

function index() {
    User::getAllUser('../output/user.txt', 1, MAX_USER);
    User::getAllMeter('../output/meter.txt', 1, MAX_USER);
    Meter::getAllMeterInfo('../output/meter_info.txt', 1, MAX_METER);
    Meter::getAllMeterHistory('../output/meter_history_info.txt', 1, MAX_METER);
    Meter::getAllMeterCharge('../output/meter_charge_info.txt', 1, MAX_METER);
}

index();