<?php

function convertDurationToSecond($duration)  {
    $total_second = 0;
    $parts = explode(":", $duration);
    $count_parts = count($parts);
    if ($count_parts == 2) {
        $minutes = (int) $parts[0];
        $second = (int) $parts[1];
        $total_second = ($minutes * 60) + $second;
    }elseif ($count_parts == 3) {
        $hour = (int) $parts[0];
        $minutes = (int) $parts[1];
        $second = (int) $parts[2];
        $total_second = ($hour * 60 * 60) + ($minutes * 60) +$second;
    }else{
        return "Invalid Duration";
    }
    return $total_second;
}

function convertSecondsToMinutesHours($seconds)  {
    if (!is_numeric($seconds) || $seconds < 0) {
        return "invalild input";
    }
    
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600 ) / 60);
    $seconds  = $seconds % 60;
    $time = [];
    if ($hours>0) {
        $time[] = $hours.'h';
    }
    if ($minutes>0) {
        $time[] = $minutes.'m';
    }
    if ($seconds>0 || empty($time)) {
        $time[] = $seconds.'s';
    }

    return implode(" ", $time);
}

function dd($input)  {
    echo "<pre>";
    var_dump($input);
    die();
}