<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<title>Magic Mirror</title>
	<meta name="description" content="The Magic Mirror">
	<meta http-equiv="refresh" content="15" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300'>
    <link rel="stylesheet" href="style.css">
    <script type="text/javascript" src="index.js"></script>
    <?php // Includes and variables
        include('src/bottom_message.php');
        include('src/rss.php');
        include('src/sl_feed.php');
        include('src/weather_feed.php');

        $app = parse_ini_file('app.ini');
        $conf = parse_ini_file($app['path_conf']);
    ?>
</head>
<body>
<div class="wrapper">
	<div class="feed left">
		<div id="clock"></div>
        <hr/>
        <?php // RSS side feed
            $feed = get_rss_feed($conf['srss_url']);
            display_rss_side($feed, $conf['srss_limit'], $conf['srss_url']);
		?>
	</div>
	<div class="feed right">
        <?php // SL feed & periodic weather forecast
            display_weather($conf['yr_location'], $conf['yr_limit']);
            echo '<hr/>';
            display_sl($conf['sl_url'], $conf['sl_limit']);
        ?>
	</div>
    <div class="bottom_feed">
        <?php // RSS bottom feed
            $feed = get_rss_feed($conf['brss_url']);
            display_rss_bottom($feed, $conf['brss_limit'], $conf['brss_url']);
        ?>
    </div>
	<div class="bottom">
        <hr/>
        <h3>
            <?php 
                $messages = get_all_messages($conf);
                $message = pick_message($messages, $conf['messages_regex_cron']);
                display_bottom_message(); 
            ?>
        </h3>
	</div>
</div>
</body>
</html>