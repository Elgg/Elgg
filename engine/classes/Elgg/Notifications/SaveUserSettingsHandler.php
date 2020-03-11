<?php

namespace Elgg\Notifications;

/**
 * Saves user notification settings
 *
 * @since 4.0
 */
class SaveUserSettingsHandler {
	
	/**
	 * Save personal notification settings - input comes from request
	 *
	 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$user = elgg_get_logged_in_user_entity();
		if (!$user) {
			return;
		}
	
		$method = get_input('method');
	
		$current_settings = $user->getNotificationSettings();
	
		$result = false;
		foreach ($method as $k => $v) {
			// check if setting has changed and skip if not
			if ($current_settings[$k] == ($v == 'yes')) {
				continue;
			}
	
			$result = $user->setNotificationSetting($k, ($v == 'yes'));
			if (!$result) {
				register_error(elgg_echo('notifications:usersettings:save:fail'));
			}
		}
	
		if ($result) {
			system_message(elgg_echo('notifications:usersettings:save:ok'));
		}
	}
}
