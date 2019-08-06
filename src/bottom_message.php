<?php 

function display_bottom_message() {
    $hour = date('H');
    $min = date('i');
    $day = date('w');
    // Everyone knows it's party-time between 22:00 and 03:00 on fridays and saturdays
    $isParty = ((($day == 5 or $day == 6) and ($hour == 22 or $hour == 23)) or (($day == 6 or $day == 0) and ($hour >= 00 and $hour <= 02)));

    /**
     * 00:00 - 05:59 = Shh, sover...
     * 06:00 - 07:29 = God morgon!
     * 07:30 - 11:59 = Ha en bra dag!
     * 12:00 - 12:59 = Lunchdags!
     * 13:00 - 16:59 = Kom och kolla!
     * 17:00 - 19:59 = Dags för middag!
     * 20:00 - 21:59 = Ha en bra kväll!
     * 22:00 - 23:59 = God natt, ses i morgon!
     */
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
