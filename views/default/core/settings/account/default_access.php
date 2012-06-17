<?php
/**
 * Provide a way of setting your default access
 *
 * @package Elgg
 * @subpackage Core
 */
if (elgg_get_config('allow_user_default_access')) {
	$user = elgg_get_page_owner_entity();

	if ($user) {
		if (false === ($default_access = $user->getPrivateSetting('elgg_default_access'))) {
			$default_access = elgg_get_config('default_access');
		}

		$title = elgg_echo('default_access:settings');
		$content = elgg_echo('default_access:label') . ': ';
		$content .= elgg_view('input/access', array(
			'name' => 'default_access',
			'value' => $default_access,
		));

		echo elgg_view_module('info', $title, $content);
	}
}
