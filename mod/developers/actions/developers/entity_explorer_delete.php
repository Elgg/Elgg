<?php

return elgg_call(ELGG_SHOW_DISABLED_ENTITIES, function() {

	$guid = (int) get_input('guid');
	$type = get_input('type');
	$key = get_input('key');
	
	$entity = get_entity($guid);
	if (empty($entity) || empty($type) || $key === null) {
		return elgg_error_response(elgg_echo('error:missing_data'));
	}
	
	if (!$entity->canEdit()) {
		return elgg_error_response(elgg_echo('actionunauthorized'));
	}
	
	switch ($type) {
		case 'entity':
			if (!($entity instanceof ElggSite)) {
				$entity->delete();
			}
			break;
		case 'metadata':
			unset($entity->$key);
			break;
		case 'relationship':
			get_relationship($key)->delete();
			break;
		case 'private_setting':
			$entity->removePrivateSetting($key);
			break;
		case 'acl':
			delete_access_collection($key);
			break;
	}
	
	return elgg_ok_response();
});
