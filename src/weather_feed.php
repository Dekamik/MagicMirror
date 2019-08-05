<?php

include( __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'yr-php-library'.DIRECTORY_SEPARATOR.'autoload.php');

function is_sun_set($time, $sunset_time, $sunrise_time) {
    $sunrise_time->add(new DateInterval('P1D'));
    return $time > $sunset_time and $time < $sunrise_time;
}

function get_temperature_icon($temperature) {
    if ($temperature >= 10) {
        return '<i class="fas fa-temperature-high"></i>';
    }
    return '<i class="fas fa-temperature-low"></i>';
}

function get_weather_icon($time, $sunset_time, $sunrise_time, $temperature, $precipitation) {
    // Precipitation
    if ($temperature > 0) {
        if ($precipitation > 0 and $precipitation < 10) {
            return '<i class="fas fa-cloud-rain"></i>';
        }
        else if ($precipitation >= 10) {
            return '<i class="fas fa-cloud-showers-heavy"></i>';
        }
    }
    else {
        if ($precipitation > 0) {
            return '<i class="fas fa-snowflake"></i>';   
        }
    }

    // No precipitation
    if (is_sun_set($time, $sunset_time, $sunrise_time)) {
        return '<i class="fas fa-moon"></i>';
    }
    else {
        return '<i class="fas fa-sun"></i>';
    }
}

function display_weather($location, $feed_limit) {
    $yr = Yr\Yr::create($location, "/tmp");

    $current = $yr->getCurrentForecast();
    $temp = $current->getTemperature();
    $prec = $current->getPrecipitation();
    $sunset = $yr->getSunset();
    $sunrise = $yr->getSunrise();
    $icon = get_weather_icon($current->getFrom(), $sunset, $sunrise, $temp, $prec);
    $wind = $current->getWindSpeed();
    echo '<div class="weather">';
    echo '<h1>'.$icon.$temp.'°C</h1>';
    echo '<h2><i class="fas fa-wind"></i> '.$wind.' m/s</h2>';
    echo '<br/><hr/><br/>';

    $forecasts = $yr->getPeriodicForecasts();
    echo '<table>';
    for ($i = 0; $i < $feed_limit; $i++) {
        $for_time = $forecasts[$i]->getFrom()->format("H:i");
        $for_temp = $forecasts[$i]->getTemperature();
        $for_prec = $forecasts[$i]->getPrecipitation();
        $for_icon = get_weather_icon($forecasts[$i]->getFrom(), $sunset, $sunrise, $for_temp, $for_prec);
        $for_wind = $forecasts[$i]->getWindSpeed();
        echo '<tr><td align="left"><i class="far fa-clock"></i> '.$for_time.'</td><td>'.$for_icon.' '.$for_temp.'°C</td><td><i class="fas fa-wind"></i> '.$for_wind.' m/s</td></tr>';
    }
    echo '</table>';
    echo '</div>';
}

?>