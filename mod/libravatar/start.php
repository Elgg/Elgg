<?php
/**
 * Libravatar plugin
 *
 * Based on Elgg's Gravatar plugin.
 */

elgg_register_event_handler('init', 'system', 'libravatar_init');

function libravatar_init() {
	elgg_register_plugin_hook_handler('entity:icon:url', 'user', 'libravatar_avatar_hook', 900);
}

/**
 * This hooks into the getIcon API and returns a libravatar icon
 */
function libravatar_avatar_hook($hook, $type, $url, $params) {

	// check if user already has an icon
	if (!$params['entity']->icontime) {
		$icon_sizes = elgg_get_config('icon_sizes');
		$size = $params['size'];
		if (!in_array($size, array_keys($icon_sizes))) {
			$size = 'small';
		}

		// avatars must be square
		$size = $icon_sizes[$size]['w'];

		// default image
		$default = elgg_get_site_url() . "_graphics/icons/user/default{$params['size']}.gif";

		$hash = md5($params['entity']->email);
		return "https://seccdn.libravatar.org/avatar/$hash.jpg?d=$default&s=$size";
	}
}
