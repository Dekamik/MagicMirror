<?php 
$now = date('H');
$day = date('w');
// Everyone knows it's party-time between 22:00 and 03:00 on fridays and saturdays
$isParty = ((($day == 5 or $day == 6) and ($now == 22 or $now == 23)) or (($day == 6 or $day == 0) and ($now >= 00 and $now <= 02)));

if (($now > 06) and ($now < 8)) echo 'God morgon!';
else if (($now >= 8) and ($now < 12)) echo 'Ha en bra dag!';
else if (($now >= 12) and ($now < 13)) echo 'Lunchdags!';
else if (($now >= 13) and ($now < 17)) echo 'Kom och kolla!';
else if (($now >= 17) and ($now < 20)) echo 'Dags för middag!';
else if (($now >= 20) and ($now < 22)) echo 'Ha en bra kväll!';
else if ($isParty) echo 'FEKKE, WOO!!';
else if (($now >= 22) and ($now <= 23)) echo 'God natt, ses i morgon!';
else if (($now >= 00) and ($now < 06)) echo 'Shh, sover...';
?>