<?php
/**
 * Elgg profile icon cache/bypass
 * 
 * 
 * @package ElggProfile
 * @deprecated 2.2
 */

$autoload_root = dirname(dirname(__DIR__));
if (!is_file("$autoload_root/vendor/autoload.php")) {
	$autoload_root = dirname(dirname(dirname($autoload_root)));
}
require_once "$autoload_root/vendor/autoload.php";

\Elgg\Application::start();

elgg_deprecated_notice("icondirect.php is no longer used and will be removed, do not include and require it. Use elgg_get_inline_url() instead.", '2.2');

$guid = get_input('guid');
$size = get_input('size') ? : 'medium';

elgg_entity_gatekeeper($guid, 'user');

$user = get_entity($guid);

if ($user) {
	$filehandler = new ElggFile();
	$filehandler->owner_guid = $user->guid;
	$filehandler->setFilename("profile/{$user->guid}{$size}.jpg");
	$avatar_url = elgg_get_inline_url($filehandler);
}

if (!$avatar_url) {
	$avatar_url = elgg_get_simplecache_url("icons/user/default{$size}.gif");
}

forward($avatar_url);
