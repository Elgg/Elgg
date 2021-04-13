<?php

namespace Elgg\Email;

/**
 * Save the user setting for delayed email interval
 *
 * @since 4.0
 */
class SaveUserSettingsHandler {
	
	/**
	 * Handle the saving of the user settings
	 *
	 * @param \Elgg\Hook $hook 'usersettings:save', 'user'
	 *
	 * @return void
	 */
	public function __invoke(\Elgg\Hook $hook): void {
		$user = $hook->getUserParam();
		if (!$user instanceof \ElggUser || !$user->canEdit() || !(bool) elgg_get_config('enable_delayed_email')) {
			return;
		}
		
		$delayed_email_interval = get_input('delayed_email_interval');
		if (empty($delayed_email_interval)) {
			return;
		}
		
		if ($user->getPrivateSetting('delayed_email_interval') === $delayed_email_interval) {
			// no change
			return;
		}
		
		// save new setting
		$user->setPrivateSetting('delayed_email_interval', $delayed_email_interval);
		
		// update all queued notifications to the new interval
		_elgg_services()->delayedEmailQueueTable->updateRecipientInterval($user->guid, $delayed_email_interval);
	}
}
