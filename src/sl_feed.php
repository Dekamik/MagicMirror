<?php

function display_sl($feed_url, $feed_limit) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $feed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    $json = json_decode($response, true);
    $feed = array();
    foreach($json['ResponseData']['Buses'] as $node) {
        $item = array(
            'title' => 'Buss '.$node['LineNumber'].' mot '.$node['Destination'],
            'time' => $node['DisplayTime'],
        );
        array_push($feed, $item);
    }

    $limit = count($feed) > $feed_limit ? $feed_limit : count($feed);
    if ($limit > 0) echo '<h2>...</h2>';
    else echo '<br/><h2>Inga bussar går den närmsta timmen.</h2>';

    for($i = 0; $i < $limit; $i++) {
        echo '<h2 class="smaller"><i class="fas fa-bus"></i> '.$feed[$i]['title'].'</h2>';
        echo '<br/>';
        echo '<h2 class="time"><i class="far fa-clock"></i> '.$feed[$i]['time'].'</h2>';
        echo '<h2>...</h2>';
    }
    curl_close($ch);
}

?>