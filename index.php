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
</head>
<body>
<div class="wrapper">
	<div class="feed left">
		<div id="clock"></div>
        <hr/>
        <?php // RSS feed
            include('src/rss_feed.php');
            $ini = parse_ini_file(parse_ini_file('app.ini')['path_conf']);
            display_rss($ini['rss_url'], $ini['rss_limit']);
		?>
	</div>
	<div class="feed right">
        <?php // SL feed & periodic weather forecast
            include('src/sl_feed.php');
            include('src/weather_feed.php');

            $ini = parse_ini_file(parse_ini_file('app.ini')['path_conf']);

            display_weather($ini['yr_location'], $ini['yr_limit'], $ini['yr_prec_low_med'], $ini['yr_prec_med_hi']);
            echo '<br/><hr/>';
            display_sl($ini['sl_url'], $ini['sl_limit']);
        ?>
	</div>
	<div class="bottom">
    <hr/>
    <h3>
        <?php
            include('src/bottom_message.php');
        ?>
    </h3>
	</div>
</div>
</body>
</html>