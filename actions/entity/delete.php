<?php
/**
 * Default entity delete action
 */

$guid = (int) get_input('guid');
$deleter_guid = (int) get_input('deleter_guid');

$entity = get_entity($guid);
if (!$entity instanceof \ElggEntity) {
	return elgg_error_response(elgg_echo('entity:delete:item_not_found'));
}

if (!$entity->canDelete() || $entity instanceof \ElggPlugin || $entity instanceof \ElggSite || $entity instanceof \ElggUser) {
	return elgg_error_response(elgg_echo('entity:delete:permission_denied'));
}

set_time_limit(0);

// determine what name to show on success
$display_name = $entity->getDisplayName() ?: elgg_echo('entity:delete:item');

$type = $entity->getType();
$subtype = $entity->getSubtype();
$container = $entity->getContainerEntity();

$soft_deletable_entities = elgg_entity_types_with_capability('soft_deletable');

//TODO: discuss: above call returns nothing, but searching for 'commentable' does - why?

if ($entity->soft_deleted === 'no' && $entity->hasCapability('soft_deletable')) {
    if (!$entity->softDelete($deleter_guid)) {
        return elgg_error_response(elgg_echo('entity:delete:fail', [$display_name]));
    }
} else {
    if (!$entity->delete()) {
        return elgg_error_response(elgg_echo('entity:delete:fail', [$display_name]));
    }
}

// determine forward URL
$forward_url = get_input('forward_url');
if (!empty($forward_url)) {
	$forward_url = elgg_normalize_site_url((string) $forward_url);
}

if (empty($forward_url)) {
	$forward_url = REFERRER;
	$referrer_url = elgg_extract('HTTP_REFERER', $_SERVER, '');
	$site_url = elgg_get_site_url();
	
	$find_forward_url = function (\ElggEntity $container = null) use ($type, $subtype) {
		$routes = _elgg_services()->routes;
		
		// check if there is a collection route (eg. blog/owner/username)
		$route_name = false;
		if ($container instanceof \ElggUser) {
			$route_name = "collection:{$type}:{$subtype}:owner";
		} elseif ($container instanceof \ElggGroup) {
			$route_name = "collection:{$type}:{$subtype}:group";
		}
		
		if ($route_name && $routes->get($route_name)) {
			$params = $routes->resolveRouteParameters($route_name, $container);
			
			return elgg_generate_url($route_name, $params);
		}
		
		// no route found, fallback to container url
		if ($container instanceof \ElggEntity) {
			return $container->getURL();
		}
		
		// no container
		return '';
	};
	
	if (!empty($referrer_url) && elgg_strpos($referrer_url, $site_url) === 0) {
		// referer is on current site
		$referrer_path = elgg_substr($referrer_url, elgg_strlen($site_url));
		$segments = explode('/', $referrer_path);
		
		if (in_array($guid, $segments)) {
			// referrer URL contains a reference to the entity that will be deleted
			$forward_url = $find_forward_url($container);
		}
	} elseif ($container instanceof \ElggEntity) {
		$forward_url = $find_forward_url($container);
	}
}

$success_keys = [
	"entity:delete:{$type}:{$subtype}:success",
	"entity:delete:{$type}:success",
	'entity:delete:success',
];

$message = '';
if (get_input('show_success', true)) {
	foreach ($success_keys as $success_key) {
		if (elgg_language_key_exists($success_key)) {
			$message = elgg_echo($success_key, [$display_name]);
			break;
		}
	}
}

return elgg_ok_response('', $message, $forward_url);
