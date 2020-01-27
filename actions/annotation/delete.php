<?php
/**
 * Generic annotation delete action
 */

$id = (int) get_input('id');
if ($id < 1) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

$annotation = elgg_get_annotation_from_id($id);
if (!$annotation instanceof ElggAnnotation) {
	return elgg_error_response(elgg_echo('error:missing_data'));
}

if (!$annotation->canEdit()) {
	return elgg_error_response(elgg_echo('actionunauthorized'));
}

$ok_content = [
	'annotation' => $annotation->toObject(),
];

if (!$annotation->delete()) {
	$lan_key = "annotation:delete:{$annotation->name}:fail";
	if (!elgg_language_key_exists($lan_key)) {
		$lan_key = 'annotation:delete:fail';
	}
	
	return elgg_error_response(elgg_echo($lan_key));
}

$lan_key = "annotation:delete:{$annotation->name}:success";
if (!elgg_language_key_exists($lan_key)) {
	$lan_key = 'annotation:delete:success';
}

return elgg_ok_response($ok_content, elgg_echo($lan_key));
