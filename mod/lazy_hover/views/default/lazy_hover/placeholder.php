<?php

$spans_present = (array)elgg_get_config('lazy_hover:spans');

$user = elgg_extract("entity", $vars);
if (!elgg_instanceof($user, 'user')) {
	return;
}

$guid = (int)$user->guid;
$page_owner_guid = (int)elgg_get_page_owner_guid();
$contexts = (array)elgg_get_config("context");

// generate MAC so we don't have to trust the client's choice of contexts
$data = serialize(array($guid, $page_owner_guid, $contexts));
$mac = hash_hmac('sha256', $data, get_site_secret());

echo "<div rel='$mac' class='hidden lazy-hover-placeholder'>";

if (empty($spans_present[$mac])) {
	$attrs = array(
		'data-json' => json_encode(array(
			'g' => $guid,
			'pog' => $page_owner_guid,
			'c' => $contexts,
			'm' => $mac,
		)),
	);
	echo "<span " . elgg_format_attributes($attrs) . '></span>';

	$spans_present[$mac] = true;
	elgg_set_config('lazy_hover:spans', $spans_present);
}
	
echo "</div>";
