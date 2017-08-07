<?php
/**
 * Default entity delete action
 */
$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof ElggEntity) {
	return elgg_error_response(elgg_echo('entity:delete:item_not_found'));
}

if (!$entity->canDelete() || $entity instanceof ElggPlugin || $entity instanceof ElggSite) {
	return elgg_error_response(elgg_echo('entity:delete:permission_denied'));
}

set_time_limit(0);

// determine what name to show on success
$display_name = $entity->getDisplayName() ?: elgg_echo('entity:delete:item');

$type = $entity->getType();
$subtype = $entity->getSubtype();
$container = $entity->getContainerEntity();

if (!$entity->delete()) {
	return elgg_error_response(elgg_echo('entity:delete:fail', [$display_name]));
}

// determine forward URL
$forward_url = get_input('forward_url');
if (!$forward_url) {
	
	// @todo rewrite this to be more readable
	$forward_url = REFERRER;
	$referrer_url = !empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	$site_url = elgg_get_site_url();
	if ($referrer_url && 0 == strpos($referrer_url, $site_url)) {
		$referrer_path = substr($referrer_url, strlen($site_url));
		$segments = explode('/', $referrer_path);
		if (in_array($guid, $segments)) {
			// referrer URL contains a reference to the entity that will be deleted
			$forward_url = ($container) ? $container->getURL() : '';
		}
	} else if ($container) {
		$forward_url = $container->getURL() ? : '';
	}
}

$success_keys = [
	"entity:delete:$type:$subtype:success",
	"entity:delete:$type:success",
	"entity:delete:success",
];

$message = '';
foreach ($success_keys as $success_key) {
	if (elgg_language_key_exists($success_key)) {
		$message = elgg_echo($success_key, [$display_name]);
		break;
	}
}

return elgg_ok_response('', $message, $forward_url);
