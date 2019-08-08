<?php

function display_sl($feed_url, $feed_limit, $tmp_dir = './') {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $feed_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $response = curl_exec($ch);
    $json = json_decode($response, true);
    $feed = array();
    foreach($json['ResponseData']['Buses'] as $node) {
        $item = array(
            'line' => $node['LineNumber'],
            'dest' => $node['Destination'],
            'time' => $node['DisplayTime'],
        );
        array_push($feed, $item);
    }

    // We don't want outdated data to persist, in case no fares exist.
    // That's why the cache works like a stack; in order to only handle one-time outages.
    if (count($feed) == 0) {
        if (file_exists($tmp_dir.'sl_feed.cache')) {
            $feed = unserialize(file_get_contents($tmp_dir.'/sl_feed.cache'));
            unlink($tmp_dir.'sl_feed.cache');
        }
    }
    else {
        if (count($feed) > 0) {
            file_put_contents($tmp_dir.'/sl_feed.cache', serialize($feed));
        }
    }

    echo '<h2 class="smaller">SL - Ursviks Holme</h2>';

    $limit = count($feed) > $feed_limit ? $feed_limit : count($feed);
    if ($limit > 0) echo '<table class="sl"><thead><td align="left">Linje</td><td align="left">Destination</td><td>Avgångstid</td></thead>';
    else echo '<h2>Inga bussar går den närmsta timmen.</h2>';

    for($i = 0; $i < $limit; $i++) {
        echo '<tr><td><i class="fas fa-bus"></i>&nbsp;'.$feed[$i]['line'].'</td><td>'.$feed[$i]['dest'].'</td><td>'.$feed[$i]['time'].'</td></tr>';
    }

    if ($limit > 0) echo '</table>';

    curl_close($ch);
}
