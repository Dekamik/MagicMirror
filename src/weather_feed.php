<?php

include( __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'yr-php-library'.DIRECTORY_SEPARATOR.'autoload.php');

function get_minute($datetime) {
    return (int) $datetime->format('i');
}

function get_hour($datetime) {
    return (int) $datetime->format('H');
}

function is_night($time, $sunset_time, $sunrise_time) {
    // return true if time is between sunset and midnight, or if time is between midnight and sunrise
    return ((get_hour($time) > get_hour($sunset_time) and get_minute($time) > get_minute($sunset_time)) and (get_hour($time) <= 23 and get_hour($time) <= 59))
        or ((get_hour($time) >= 0 and get_minute($time) >= 0) and (get_hour($time) < get_hour($sunrise_time) and get_minute($time) < get_minute($sunrise_time)));
}

function get_icon_from_desc($desc, $is_night) {
    $thunder = stristr($desc, 'thunder') ? '<i class="fas fa-bolt"></i>' : '';
    switch ($desc) {
        case stristr($desc, 'clear sky'):
            return $is_night ? $thunder.'<i class="fas fa-moon"></i>' : $thunder.'<i class="fas fa-sun"></i>';
        case stristr($desc, 'partly cloudy'):
        case stristr($desc, 'fair'):
            return $is_night ? $thunder.'<i class="fas fa-cloud-moon"></i>' : $thunder.'<i class="fas fa-cloud-sun"></i>';
        case stristr($desc, 'fog'):
            return $thunder.'<i class="fas fa-smog"></i>';
        case stristr($desc, 'cloudy'):
            return $thunder.'<i class="fas fa-cloud"></i>';
        case stristr($desc, 'heavy rain'):
            return $thunder.'<i class="fas fa-water"></i>';
        case stristr($desc, 'light rain'):
            return $thunder.'<i class="fas fa-cloud-rain"></i>';
        case stristr($desc, 'rain'):
            return $thunder.'<i class="fas fa-cloud-showers-heavy"></i>';
        case stristr($desc, 'snow'):
            return $thunder.'<i class="fas fa-snowflake"></i>';
        case stristr($desc, 'sleet'):
        default:
            return $thunder.'<i class="fas fa-poo-storm"></i>';
    }
}

function display_weather($location, $feed_limit) {
    $yr = Yr\Yr::create($location, "/tmp", 5);

    $current = $yr->getCurrentForecast();
    $temp = $current->getTemperature();
    $sunset = $yr->getSunset();
    $sunrise = $yr->getSunrise();
    $icon = get_icon_from_desc($current->getSymbol(), is_night($current->getFrom(), $sunset, $sunrise));
    $wind = $current->getWindSpeed();
    echo '<h1>'.$icon.$temp.'°C</h1>';
    echo '<h2><i class="fas fa-wind"></i> '.$wind.' m/s</h2>';
    echo '<hr/>';

    $forecasts = $yr->getPeriodicForecasts();
    echo '<table>';
    for ($i = 1; $i < $feed_limit + 1; $i++) {
        $for_time = $forecasts[$i]->getFrom()->format("H:i");
        $for_temp = $forecasts[$i]->getTemperature();
        $for_icon = get_icon_from_desc($forecasts[$i]->getSymbol(), is_night($forecasts[$i]->getFrom(), $sunset, $sunrise));
        $for_wind = $forecasts[$i]->getWindSpeed();
        echo '<tr><td align="left"><i class="fas fa-clock"></i> '.$for_time.'</td><td>'.$for_icon.' '.$for_temp.'°C</td><td><i class="fas fa-wind"></i> '.$for_wind.' m/s</td></tr>';
    }
    echo '</table>';
}
