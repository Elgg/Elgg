<?php

return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() {

	$guid = (int) get_input('guid');
	$type = get_input('type');
	$key = get_input('key');
	
	$entity = get_entity($guid);
	if (!$entity instanceof \ElggEntity || empty($type) || $key === null) {
		return elgg_error_response(elgg_echo('error:missing_data'));
	}
	
	if (!$entity->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
	
	switch ($type) {
		case 'entity':
			if (!$entity instanceof \ElggSite) {
				$entity->delete();
			}
			break;
		case 'metadata':
			unset($entity->$key);
			break;
		case 'relationship':
			$relationship = elgg_get_relationship((int) $key);
			if ($relationship instanceof \ElggRelationship) {
				$relationship->delete();
			}
			break;
		case 'acl':
			$acl = elgg_get_access_collection((int) $key);
			if ($acl instanceof \ElggAccessCollection) {
				$acl->delete();
			}
			break;
	}
	
	return elgg_ok_response();
});
