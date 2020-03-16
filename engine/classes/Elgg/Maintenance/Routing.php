<?php

namespace Elgg\Maintenance;

/**
 * Maintenance mode
 *
 * @since 4.0
 */
class Routing {
	
	/**
	 * Handle requests when in maintenance mode
	 *
	 * @param \Elgg\Hook $hook 'route', 'all'
	 *
	 * @return void|false
	 */
	public static function redirectRoute(\Elgg\Hook $hook) {
		if (elgg_is_admin_logged_in()) {
			return;
		}
		
		if (!elgg_get_config('elgg_maintenance_mode')) {
			return;
		}
	
		$info = $hook->getValue();
		
		if ($info['identifier'] == 'action' && $info['segments'][0] == 'login') {
			return;
		}
	
		if (self::allowCurrentUrl()) {
			return;
		}
	
		echo elgg_view_resource('maintenance');
	
		return false;
	}
	
	/**
	 * Prevent non-admins from using actions
	 *
	 * @param \Elgg\Hook $hook 'action', 'all'
	 *
	 * @return void|bool
	 */
	public static function preventAction(\Elgg\Hook $hook) {
		if (!elgg_get_config('elgg_maintenance_mode')) {
			return;
		}
		
		if (elgg_is_admin_logged_in()) {
			return true;
		}
	
		if ($hook->getType() == 'login') {
			$username = get_input('username');
	
			$user = get_user_by_username($username);
	
			if (!$user) {
				$users = get_user_by_email($username);
				if (!empty($users)) {
					$user = $users[0];
				}
			}
	
			if ($user && $user->isAdmin()) {
				return true;
			}
		}
	
		if (self::allowCurrentUrl()) {
			return true;
		}
	
		register_error(elgg_echo('actionunauthorized'));
	
		return false;
	}
	
	/**
	 * When in maintenance mode, should the current URL be handled normally?
	 *
	 * @return bool
	 */
	protected static function allowCurrentUrl() {
		$current_url = current_page_url();
		$site_path = preg_replace('~^https?~', '', elgg_get_site_url());
		$current_path = preg_replace('~^https?~', '', $current_url);
		if (0 === elgg_strpos($current_path, $site_path)) {
			$current_path = ($current_path === $site_path) ? '' : elgg_substr($current_path, elgg_strlen($site_path));
		} else {
			$current_path = false;
		}
	
		// allow plugins to control access for specific URLs/paths
		$params = [
			'current_path' => $current_path,
			'current_url' => $current_url,
		];
		return (bool) elgg_trigger_plugin_hook('maintenance:allow', 'url', $params, false);
	}
}
