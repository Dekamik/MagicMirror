<?php

include( __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'yr-php-library'.DIRECTORY_SEPARATOR.'autoload.php');

function get_temperature_icon($temperature) {
    if ($temperature >= 10) {
        return '<i class="fas fa-temperature-high"></i>';
    }
    return '<i class="fas fa-temperature-low"></i>';
}

function get_weather_icon($temperature, $precipitation) {
    if ($temperature > 0) {
        if ($precipitation == 0) {
            return '<i class="fas fa-sun"></i>';
        }
        else if ($precipitation >= 10) {
            return '<i class="fas fa-cloud-showers-heavy"></i>';
        }
        else {
            return '<i class="fas fa-cloud-rain"></i>';
        }
    }
    else {
        if ($precipitation == 0) {
            return '<i class="fas fa-snowman"></i>';
        }
        else {
            return '<i class="fas fa-snowflake"></i>';
        }
    }
}

function display_weather($location, $feed_limit) {
    $yr = Yr\Yr::create($location, "/tmp");

    $current = $yr->getCurrentForecast();
    $temp = $current->getTemperature();
    $prec = $current->getPrecipitation();
    echo '<div class="weather">';
    echo '<h1>'.get_weather_icon($temp, $prec).$temp.'°C</h1>';
    echo '<h2><i class="fas fa-wind"></i> '.$current->getWindSpeed().' m/s</h2>';
    echo '<br/><hr/><br/>';
    $forecasts = $yr->getPeriodicForecasts();
    echo '<table>';
    for ($i = 0; $i < $feed_limit; $i++) {
        $for_time = $forecasts[$i]->getFrom()->format("H:i");
        $for_temp = $forecasts[$i]->getTemperature();
        $for_prec = $forecasts[$i]->getPrecipitation();
        echo '<tr><td align="left"><i class="far fa-clock"></i> '.$for_time.'</td><td>'.get_weather_icon($for_temp, $for_prec).' '.$for_temp.'°C</td><td><i class="fas fa-wind"></i> '.$forecasts[$i]->getWindSpeed().' m/s</td></tr>';
    }
    echo '</table>';
    echo '</div>';
}

?>