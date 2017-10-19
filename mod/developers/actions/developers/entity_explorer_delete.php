<?php

$guid = (int) get_input('guid');
$type = get_input('type');
$key = get_input('key');

$show_hidden = access_show_hidden_entities(true);

$entity = get_entity($guid);
if (empty($entity) || empty($type) || $key === null) {
	access_show_hidden_entities($show_hidden);
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!$entity->canEdit()) {
	access_show_hidden_entities($show_hidden);
	return elgg_error_response(elgg_echo('action:unauthorized'));
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
}

access_show_hidden_entities($show_hidden);

return elgg_ok_response();
