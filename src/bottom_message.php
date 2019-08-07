<?php

class Message {
    public $cron_expr;
    public $message;
    public $run_date;
    public $run_date_key;

    function __construct($cron_regex, $cron_entry) {
        $this->cron_expr = preg_match($cron_regex, $cron_entry, $out) ? $out[0] : null;
        $this->message = substr(preg_split($cron_regex, $cron_entry)[1], 1);
        $this->run_date = Cron\CronExpression::factory($this->cron_expr)->getPreviousRunDate('now', 0, true);
        $this->run_date_key = $this->run_date->format('Y-m-d H:i');
    }

    /**
     * A crude function for testing how specific a given cron expression is.
     * 
     * @return  int signifying the cron expression's specificity. Higher int means more specific.
     */
    public function get_specificity() {
        $asterisks = substr_count($this->cron_expr, '*');
        $dashes = substr_count($this->cron_expr, '-');
        $commas = substr_count($this->cron_expr, ',');
        $nths = substr_count($this->cron_expr, '#');
        $close_w = substr_count($this->cron_expr, 'W');
        $lasts = substr_count($this->cron_expr, 'L');

        return 500 - ((100 * $asterisks) + (50 * $dashes) + (25 * $commas) + (12 * $nths) + (6 * $close_w) + (3 * $lasts));
    }
}

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
