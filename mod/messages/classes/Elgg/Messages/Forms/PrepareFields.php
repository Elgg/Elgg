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
			'recipient' => null,
		];
		
		$vars = array_merge($values, $vars);
		
		// make sure the recipient is a user
		if (!empty($vars['recipient']) && !get_user((int) $vars['recipient']) instanceof \ElggUser) {
			unset($vars['recipient']);
		}

		return $vars;
	}
}
