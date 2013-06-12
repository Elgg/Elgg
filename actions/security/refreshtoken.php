<?php
$ts = time();
$token = _elgg_generate_action_token($ts);

echo json_encode(array('__elgg_ts' => $ts, '__elgg_token' => $token));