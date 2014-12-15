<?php
elgg_load_css('elgg.walled_garden');
elgg_load_js('elgg.walled_garden');

$content = elgg_view('core/walled_garden/login');

$params = array(
	'content' => $content,
	'class' => 'elgg-walledgarden-double',
	'id' => 'elgg-walledgarden-login',
);
$body = elgg_view_layout('walled_garden', $params);
echo elgg_view_page('', $body, 'walled_garden');
