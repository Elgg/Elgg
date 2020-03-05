<?php

namespace Elgg\Comments;

/**
 * Updates email subjects for comments
 *
 * @since 4.0
 */
class EmailSubjectHandler {
	
	/**
	 * Set subject for email notifications about new ElggComment objects
	 *
	 * The "Re: " part is required by some email clients in order to properly
	 * group the notifications in threads.
	 *
	 * @param \Elgg\Hook $hook 'email', 'system'
	 *
	 * @return array Modified mail parameters
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$returnvalue = $hook->getValue();
		if (!is_array($returnvalue) || !is_array($returnvalue['params'])) {
			// another hook handler returned a non-array, let's not override it
			return;
		}
		
		if (empty($returnvalue['params']['notification'])) {
			return;
		}
		
		$notification = $returnvalue['params']['notification'];
		if (!$notification instanceof \Elgg\Notifications\Notification) {
			return;
		}
		
		$object = elgg_extract('object', $notification->params);
		if (!$object instanceof \ElggComment) {
			return;
		}
			
		$container = $object->getContainerEntity();
		if (!$container instanceof \ElggEntity) {
			return;
		}
		
		$returnvalue['subject'] = 'Re: ' . $container->getDisplayName();
		
		return $returnvalue;
	}
}
