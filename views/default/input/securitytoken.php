<?php
/**
 * CSRF security token view for use with secure forms.
 *
 * It is still recommended that you use input/form.
 */

$ts = elgg()->csrf->getCurrentTime()->getTimestamp();
$token = elgg()->csrf->generateActionToken($ts);

echo elgg_view('input/hidden', ['name' => '__elgg_token', 'value' => $token]);
echo elgg_view('input/hidden', ['name' => '__elgg_ts', 'value' => $ts]);
