<?php 

function get_rss_feed($feed_url) {
    $rss = new DOMDocument();
    $rss->load($feed_url);
    $feed = array();
    foreach ($rss->getElementsByTagName('item') as $node) {
        $item = array (
            'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
            'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
            'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
        );
        array_push($feed, $item);
    }
    return $feed;
}

function display_rss_side($conf) {
    $url = $conf['srss_url'];
    $limit = $conf['srss_limit'];
    $feed = get_rss_feed($url);
    echo '<h2>...</h2>';
    for($i = 0; $i < $limit; $i++) {
        $title = str_replace(' & ', ' &amp; ', $feed[$i]['title']);
        $description = $feed[$i]['desc'];
        $date = date('j F', strtotime($feed[$i]['date']));
        echo '<h2 class="smaller">'.$title.'</h2>';
        echo '<p class="date">'.$date.'</p>';
        echo '<p>'.strip_tags($description, '<p><b>').'</p><h2>...</h2>';
    }
    if ($url !== '')
    {
        echo '<p>'.parse_url($url, PHP_URL_HOST).'</p>';
    }
}

function display_rss_bottom($conf) {
    $url = $conf['brss_url'];
    $limit = $conf['brss_limit'];
    $feed = get_rss_feed($url);
    for ($i = 0; $i < $limit; $i++) {
        $title = str_replace(' & ', ' &amp; ', $feed[$i]['title']);
        $description = $feed[$i]['desc'];
        $date = date('j F', strtotime($feed[$i]['date']));

        echo '<div>';
        if ($url !== '' and $i == 0) echo '<p>'.parse_url($url, PHP_URL_HOST).'</p><br/>';
        echo '<h2 class="smaller">'.$title.'</h2>';
        echo '<p class="date">'.$date.'</p>';
        echo '<p>'.strip_tags($description, '<p><b>').'</p>';
        echo '</div>';
    }
}
