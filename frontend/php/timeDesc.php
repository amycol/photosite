<?php
    function getNoon($long, $eqt, $tz) {
        return (720-4*$long+$eqt+$tz*60)/1440; // Noon in days
    }
    
    function getEqOfTime($dt) {
        $dtc = new DateTime('2000-01-01 12:00:00');
        
        $cent = ((abs($dt->getTimestamp() - $dtc->getTimestamp()))/86400)/36525;

        $anomaly = 357.52910 + 35999.05030 * $cent - 0.0001559 * ($cent**2) - 0.00000048 * ($cent**3);
        $eccentricity = 0.016708617 - 0.000042037 * $cent - 0.0000001236 * ($cent**2);
        $x = 280.46646 + 36000.76983 * $cent + 0.0003032 * ($cent**2);
        $x2 = fmod($x, 360);
        $obliquity = 23.43929111 + (-0.01300416667 * $cent - 0.00000016388 * ($cent**2) + 0.00000050361 * $cent**3);
        $obliquity2 = $obliquity+0.00256*cos(deg2rad(125.04-1934.136*$cent));
        $y = tan(deg2rad($obliquity2/2))**2;

        $eq = -4*($y*sin(deg2rad(2*$x2))-2*$eccentricity*sin(deg2rad($anomaly))+4*$eccentricity*$y*sin(deg2rad($anomaly))*cos(deg2rad(2*$x2))-0.5*($y**2)*sin(deg2rad(4*$x2))-1.25*($eccentricity**2)*sin(deg2rad(2*$anomaly)));

        return rad2deg($eq);
    }

    function parseTime($time) {
        $parsed = date_parse($time);
        $seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];
        return $seconds;
    }
    
    function calcSolarAlt($lat, $long, $date, $time, $tz, $dt, $eqt, $sec, $noon) {
        $day = date("z", strtotime($date))+1;
        $stime = $sec + $eqt * 60; // Solar time (time + eqt(mins))

        $ha = ($stime/3600 - $noon*24)*15;
        $dec = -23.45*cos(deg2rad((360/365)*($day+10)));
        $zen = rad2deg(acos(sin(deg2rad($lat))*sin(deg2rad($dec))+cos(deg2rad($lat))*cos(deg2rad($dec))*cos(deg2rad($ha))));
        $alt = 90-$zen;

        return $alt;
    }

    function timeDesc($lat, $long, $date, $time, $tz) {
        $timeDesc = "";
        $sec = parseTime($time);
        $datetime = new DateTime($date . $time, new DateTimeZone($tz));
        $tzoffset = $datetime->format('Z')/3600;
        $eqt = getEqOfTime($datetime);
        $noon = getNoon($long, $eqt, $tzoffset); // Noon in days
        $alt = calcSolarAlt($lat, $long, $date, $time, $tzoffset, $datetime, $eqt, $sec, $noon);
        
        
        switch (true) {
            case $alt <= -6 and $alt > -12 and ($sec / 86400) < $noon:
                $timeDesc = "Early Dawn";
                break;
            case $alt <= -1 and $alt > -6 and ($sec / 86400) < $noon:
                $timeDesc = "Late Dawn";
                break;
            case $alt <= 1 and $alt > -1 and ($sec / 86400) < $noon:
                $timeDesc = "Sunrise";
                break;
            case $alt >= 1 and ($sec / 86400) < $noon:
                $timeDesc = "Morning";
                break;
            case $alt >= 1 and ($sec / 86400) > $noon:
                $timeDesc = "Afternoon";
                break;
            case $alt <= 1 and $alt > -1 and ($sec / 86400) > $noon:
                $timeDesc = "Sunset";
                break;
            case $alt <= -1 and $alt > -6 and ($sec / 86400) > $noon:
                $timeDesc = "Early Dusk";
                break;
            case $alt <= -6 and $alt > -12 and ($sec / 86400) > $noon:
                $timeDesc = "Late Dusk";
                break;
            case $alt < -12:
                $timeDesc = "Night";
                break;
            default:
                $timeDesc = "Error";
                break;
        }
        return $timeDesc;
    }
    //print_r(calcSolarAlt(51.45382, -0.97185, "2024-05-23", "20:31:56", 0));
    // Accurate to within 0.3deg (compared to NOAA spreadsheet)
    // Sun moves at 0.25deg/min making this accurate to within 1 minute and 12 seconds (assuming the NOAA is 100% accurate)

    //TimeDesc does not use scientific time descriptions as the description is for artistic purposes so may differ
    // Apple Weather defines first and last light as -6 deg (Civil Twilight) Check this??
    // sunearthtools.com defines Sunrise and Sunset as -0.833 deg (When the sun touches the horizon) Check this??
?>