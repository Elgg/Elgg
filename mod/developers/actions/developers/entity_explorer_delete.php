<?php

return elgg_call(ELGG_SHOW_DISABLED_ENTITIES | ELGG_SHOW_DELETED_ENTITIES, function() {

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
				$entity->delete(true, true);
			}
			break;
		case 'metadata':
			$metadata = elgg_get_metadata_from_id((int) $key);
			if ($metadata instanceof \ElggMetadata) {
				$metadata->delete();
			}
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
