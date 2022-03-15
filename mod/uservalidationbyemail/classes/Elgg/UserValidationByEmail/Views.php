<?php

namespace Elgg\UserValidationByEmail;

/**
 * Make changes to view vars and or output
 *
 * @since 4.2
 */
class Views {
	
	/**
	 * Add buttons to the unvalidated users admin listing
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'forms/admin/users/bulk_actions'
	 *
	 * @return void|array
	 * @since 4.2
	 */
	public static function addResendBulkAction(\Elgg\Hook $hook) {
		$vars = $hook->getValue();
		if (elgg_extract('filter', $vars) !== 'unvalidated') {
			return;
		}
		
		$buttons = elgg_extract('buttons', $vars, []);
		array_unshift($buttons, [
			'#type' => 'submit',
			'icon' => 'envelope',
			'value' => elgg_echo('uservalidationbyemail:admin:resend_validation'),
			'formaction' => elgg_generate_action_url('uservalidationbyemail/resend_validation', [], false),
			'confirm' => elgg_echo('uservalidationbyemail:confirm_resend_validation_checked'),
		]);
		
		$vars['buttons'] = $buttons;
		
		return $vars;
	}
}
