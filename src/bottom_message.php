<?php

/**
 * A crude function for testing how specific a given cron expression is.
 * 
 * @param   string  $cron_expr   Cron expression to test
 * @return  int signifying the cron expression's specificity. Higher int means more specific.
 */
function calculate_cron_specificity($cron_expr) {
    $spec = 500;
    
    $asterisks = substr_count($cron_expr, '*');
    $dashes = substr_count($cron_expr, '-');
    $commas = substr_count($cron_expr, ',');
    $nths = substr_count($cron_expr, '#');
    $close_w = substr_count($cron_expr, 'W');
    $lasts = substr_count($cron_expr, 'L');

    $spec -= 100 * $asterisks;
    $spec -= 50 * $dashes;
    $spec -= 25 * $commas;
    $spec -= 12 * $nths;
    $spec -= 6 * $close_w;
    $spec -= 3 * $lasts;

    return $spec;
}

function get_all_messages($conf) {
    $messages = array();
    foreach($conf as $key => $value) {
        if (preg_match('^message_.+', $key)) {
            $messages[$key] = $value;
        }
    }
    return $messages;
}

function pick_message($messages, $date) {
    
}

function display_bottom_message() {
    $hour = date('H');
    $min = date('i');
    $day = date('w');
    // Everyone knows it's party-time between 22:00 and 03:00 on fridays and saturdays
    $isParty = ((($day == 5 or $day == 6) and ($hour == 22 or $hour == 23)) or (($day == 6 or $day == 0) and ($hour >= 00 and $hour <= 02)));

    if (($hour >= 00) and ($hour < 06)) echo 'Shh, sover...';
    else if (($hour >= 06) and (($hour == 7 and $min < 30) or $hour < 8)) echo 'God morgon!';
    else if ((($hour == 7 and $min >= 30) or $hour >= 8) and ($hour < 12)) echo 'Ha en bra dag!';
    else if (($hour >= 12) and ($hour < 13)) echo 'Lunchdags!';
    else if (($hour >= 13) and ($hour < 17)) echo 'Kom och kolla!';
    else if (($hour >= 17) and ($hour < 20)) echo 'Dags för middag!';
    else if (($hour >= 20) and ($hour < 22)) echo 'Ha en bra kväll!';
    else if ($isParty) echo 'FEKKE, WOO!!';
    else if (($hour >= 22) and ($hour <= 23)) echo 'God natt, ses i morgon!';
}
