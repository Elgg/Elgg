<?php
elgg_load_css('elgg.walled_garden');

if (_elgg_view_may_be_altered('walled_garden.js', 'walled_garden.js.php')) {
	elgg_deprecated_notice('elgg.walled_garden JS library is deprecated. Use elgg/walled_garden AMD module instead', '2.3');
	elgg_load_js('elgg.walled_garden');
} else {
	elgg_require_js('elgg/walled_garden');
}

$content = elgg_view('core/walled_garden/login');

$params = array(
	'content' => $content,
	'class' => 'elgg-walledgarden-double',
	'id' => 'elgg-walledgarden-login',
);
$body = elgg_view_layout('walled_garden', $params);
echo elgg_view_page('', $body, 'walled_garden');
