<?php 

function display_rss($feed_url, $feed_limit) {
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

    for($i=0; $i<$feed_limit; $i++) {
        $title = str_replace(' & ', ' &amp; ', $feed[$i]['title']);
        $description = $feed[$i]['desc'];
        $date = date('j F', strtotime($feed[$i]['date']));
        echo '<h2 class="smaller">'.$title.'</h2>';
        echo '<p class="date">'.$date.'</p>';
        echo '<p>'.strip_tags($description, '<p><b>').'</p><h2>...</h2>';
    }
    echo '<p>'.parse_url($feed_url, PHP_URL_HOST).'</p>';
}

?>