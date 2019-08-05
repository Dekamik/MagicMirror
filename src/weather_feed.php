<?php

include( __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'yr-php-library'.DIRECTORY_SEPARATOR.'autoload.php');

function to_hour($datetime) {
    return (int) $datetime->format('H');
}

function is_sun_set($time, $sunset_time, $sunrise_time) {
    return (to_hour($time) > to_hour($sunset_time) and to_hour($time) <= 23) or (to_hour($time) >= 0 and to_hour($time) < to_hour($sunrise_time));
}

function get_temperature_icon($temperature) {
    if ($temperature >= 10) {
        return '<i class="fas fa-temperature-high"></i>';
    }
    return '<i class="fas fa-temperature-low"></i>';
}

function get_weather_icon_cloud_cover($metar, $is_night) {
    // Clouds don't care about the moon or the sun
    if (strpos($metar, 'BKN') or strpos($metar, 'OVC')) {
        return '<i class="fas fa-cloud"></i>';
    }
    else if (strpos($metar, 'FEW') or strpos($metar, 'SCT')) {
        return $is_night ? '<i class="fas fa-cloud-moon"></i>' : '<i class="fas fa-cloud-sun"></i>';
    }
    return $is_night ? '<i class="fas fa-moon"></i>' : '<i class="fas fa-sun"></i>';
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
    return get_weather_icon_cloud_cover('', is_sun_set($time, $sunset_time, $sunrise_time));
}

function display_weather($location, $feed_limit) {
    $yr = Yr\Yr::create($location, "/tmp", 5);

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
    echo '<hr/>';

    $forecasts = $yr->getPeriodicForecasts();
    echo '<table>';
    for ($i = 1; $i < $feed_limit + 1; $i++) {
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