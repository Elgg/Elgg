<?php
/**
* Elgg file delete
*
* @package ElggFile
*/

$guid = (int) get_input('guid');

$file = get_entity($guid);
if (!$file instanceof ElggFile) {
	return elgg_error_response(elgg_echo('file:deletefailed'), 'file/all');
}

$container = $file->getContainerEntity();

if (!$file->delete()) {
	return elgg_error_response(elgg_echo('file:deletefailed'));
}

if ($container instanceof ElggGroup) {
	$forward = "file/group/{$container->guid}/all";
} else {
	$forward = "file/owner/{$container->username}";
}

return elgg_ok_response('', elgg_echo('file:deleted'), $forward);
