<?php

/**
 * Default entity delete action
 */
$guid = get_input('guid');
$entity = get_entity($guid);

if (!$entity instanceof ElggEntity) {
	register_error(elgg_echo('entity:delete:item_not_found'));
	forward(REFERRER);
}

if (!$entity->canDelete() || $entity instanceof ElggPlugin || $entity instanceof ElggSite) {
	register_error(elgg_echo('entity:delete:permission_denied'));
	forward(REFERRER);
}

set_time_limit(0);

// determine what name to show on success
$display_name = $entity->getDisplayName();
if (!$display_name) {
	$display_name = elgg_echo('entity:delete:item');
}

$type = $entity->getType();
$subtype = $entity->getSubtype();
$container = $entity->getContainerEntity();

if (!$entity->delete()) {
	register_error(elgg_echo('entity:delete:fail', array($display_name)));
	forward(REFERRER);
}

// determine forward URL
$forward_url = get_input('forward_url');
if (!$forward_url) {
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

$success_keys = array(
	"entity:delete:$type:$subtype:success",
	"entity:delete:$type:success",
	"entity:delete:success",
);

foreach ($success_keys as $success_key) {
	if (elgg_language_key_exists($success_key)) {
		system_message(elgg_echo($success_key, array($display_name)));
		break;
	}
}
forward($forward_url);
