<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<title>Magic Mirror</title>
	<meta name="description" content="The Magic Mirror">
	<meta http-equiv="refresh" content="15" />
	<link rel="stylesheet" href="style.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto:300' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="index.js"></script>
</head>
<body>
<div id="wrapper">
	<div id="left">
		<div id="clock"></div>
        <br/>
        <hr/>
        <h2>...</h2>
        <?php // RSS feed
            $ini = parse_ini_file('conf.ini');
            $rss = new DOMDocument();
            $rss->load($ini['feed_rss_url']);
            $feed = array();
            foreach ($rss->getElementsByTagName('item') as $node) {
                $item = array (
                    'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
                    'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
                    'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
                );
                array_push($feed, $item);
            }
    
            $limit = $ini['feed_rss_limit'];
            for($i=0; $i<$limit; $i++) {
                $title = str_replace(' & ', ' &amp; ', $feed[$i]['title']);
                $description = $feed[$i]['desc'];
                $date = date('j F', strtotime($feed[$i]['date']));
                echo '<h2 class="smaller">'.$title.'</h2>';
                echo '<p class="date">'.$date.'</p>';
                echo '<p>'.strip_tags($description, '<p><b>').'</p><h2>...</h2>';
            }
            echo '<p>'.parse_url($ini['feed_rss_url'], PHP_URL_HOST).'</p>'
		?>
	</div>
	<div id="right">
        <?php // SL Feed
            $ini = parse_ini_file('conf.ini');
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $ini['feed_sl_url']);
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

            $limit = count($feed) > $ini['feed_sl_limit'] ? $ini['feed_sl_limit'] : count($feed);
            if ($limit > 0) echo '<h2>...</h2>';
            else echo '<br/><h2>Inga bussar går den närmsta timmen.</h2>';

            for($i = 0; $i < $limit; $i++) {
                echo '<h2 class="smaller">'.$feed[$i]['title'].'</h2>';
                echo '<br/>';
                echo '<h2 class="time">'.$feed[$i]['time'].'</h2>';
                echo '<h2>...</h2>';
            }
            curl_close($ch);
        ?>
	</div>
	<div id="bottom">
    <hr/>
    <h3>
        <?php // Bottom message
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
    </h3>
	</div>
</div>
</body>
</html>