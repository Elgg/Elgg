<?php
/**
* Elgg file delete
* 
* @package ElggFile
*/

$guid = (int) get_input('guid');

$file = new FilePluginFile($guid);
if (!$file->guid) {
	register_error(elgg_echo("file:deletefailed"));
	forward('pg/file/all');
}

if (!$file->canEdit()) {
	register_error(elgg_echo("file:deletefailed"));
	forward($file->getURL());
}

$container = $file->getContainerEntity();

if (!$file->delete()) {
	register_error(elgg_echo("file:deletefailed"));
} else {
	system_message(elgg_echo("file:deleted"));
}

if (elgg_instanceof($container, 'group')) {
	forward("pg/file/group/$container->guid/owner");
} else {
	forward("pg/file/owner/$container->username");
}
