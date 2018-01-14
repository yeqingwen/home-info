<?php
require_once('../lib/Meter.php');
require_once('../lib/User.php');

function index() {
    User::getAllUser('../output/user.txt', 1, 1763);
    User::getAllMeter('../output/meter.txt', 1, 1763);
    Meter::getAllMeterInfo('../output/meter_info.txt', 1, 2072);
    Meter::getAllMeterHistory('../output/meter_history_info.txt', 1, 2072);
    Meter::getAllMeterCharge('../output/meter_charge_info.txt', 1, 2072);
}

index();