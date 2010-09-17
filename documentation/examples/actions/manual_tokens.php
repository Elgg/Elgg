<?php

$ts = time();
$token = generate_action_token($ts);

var_dump($ts, $token);
