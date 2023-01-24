<?php

namespace Elgg\Messages\Forms;

/**
 * Prepare the fields for the messages/send form
 *
 * @since 5.0
 */
class PrepareFields {
	
	/**
	 * Prepare fields
	 *
	 * @param \Elgg\Event $event 'form:prepare:fields', 'messages/send'
	 *
	 * @return array
	 */
	public function __invoke(\Elgg\Event $event): array {
		$vars = $event->getValue();
		
		// input names => defaults
		$values = [
			'subject' => '',
			'body' => '',
			'recipients' => [],
		];
		
		$vars = array_merge($values, $vars);
		
		// make sure the recipients are users
		if (!is_array($vars['recipients'])) {
			$vars['recipients'] = (array) $vars['recipients'];
		}
		
		$vars['recipients'] = array_filter($vars['recipients'], function ($guid) {
			$user = get_user((int) $guid);
			return $user instanceof \ElggUser;
		});
		
		return $vars;
	}
}
