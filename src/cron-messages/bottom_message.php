<?php

require 'Message.php';

function get_all_messages($conf) {
    $messages = array();
    foreach($conf as $key => $value) {
        if (preg_match('/message_.+/', $key)) {
            array_push($messages, new Message($conf['messages_regex_cron'], $value));
        }
    }
    return $messages;
}

function pick_message($messages, $cron_regex) {
    $cron_msgs = array();
    foreach ($messages as $message) {
        if (array_key_exists($message->run_date_key, $cron_msgs)) {
            $spec_our = $message->get_specificity();
            $spec_their = $cron_msgs[$message->run_date_key]->get_specificity();
            if ($spec_our > $spec_their) {
                $cron_msgs[$message->run_date_key] = $message;
            }
        }
        else {
            $cron_msgs[$message->run_date_key] = $message;
        }
    }
    return from($messages)
        ->orderByDescending('$m ==> $m->run_date')
        ->first()
        ->message;
}

function display_bottom_message($conf) {
    $messages = get_all_messages($conf);
    $message = pick_message($messages, $conf['messages_regex_cron']);
    echo $message;
}
