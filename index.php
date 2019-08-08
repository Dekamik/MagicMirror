<!DOCTYPE html>
<html lang="sv">
<head>
	<meta charset="utf-8">
	<title>Magic Mirror</title>
	<meta name="description" content="The Magic Mirror">
	<meta http-equiv="refresh" content="15" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.9.0/css/all.min.css">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300'>
    <link rel="stylesheet" href="index.css">
    <script type="text/javascript" src="index.js"></script>
    <?php // Includes and variables
        require 'vendor/autoload.php';

        include 'src/cron-messages/bottom_message.php';
        include 'src/rss.php';
        include 'src/sl_feed.php';
        include 'src/weather_feed.php';

        $app = parse_ini_file('app.ini');
        $conf = parse_ini_file($app['path_conf']);
    ?>
</head>
<body>
<div class="wrapper">
	<div class="feed left">
		<div id="clock"></div>
        <hr/>
        <?php display_rss_side($conf); ?>
	</div>
	<div class="feed right">
        <?php // SL feed & periodic weather forecast
            display_weather($conf['yr_location'], $conf['yr_limit']);
            echo '<hr/>';
            display_sl($conf['sl_url'], $conf['sl_limit']);
        ?>
	</div>
    <div class="bottom_feed">
        <?php display_rss_bottom($conf); ?>
    </div>
	<div class="bottom">
        <hr/>
        <h3>
            <?php display_bottom_message($conf); ?>
        </h3>
	</div>
</div>
</body>
</html>