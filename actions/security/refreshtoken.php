<?php
$ts = time();
$token = generate_action_token($ts);

echo json_encode(array('__elgg_ts' => $ts, '__elgg_token' => $token));