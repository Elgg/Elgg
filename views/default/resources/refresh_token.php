<?php

$ts = time();
$token = generate_action_token($ts);
$data = array(
	'__elgg_ts' => $ts,
	'__elgg_token' => $token,
	'logged_in' => elgg_is_logged_in(),
);

header("Content-Type: application/json");
echo json_encode($data);
