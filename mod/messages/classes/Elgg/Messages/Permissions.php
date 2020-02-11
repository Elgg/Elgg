<?php

namespace Elgg\Messages;

/**
 * Hook callbacks for messages permissions
 *
 * @since 4.0
 * @internal
 */
class Permissions {

	/**
	 * Override the canEdit function to return true for messages within a particular context
	 *
	 * @param \Elgg\Hook $hook 'permissions_check', 'object'
	 *
	 * @return void|true
	 */
	public static function canEdit(\Elgg\Hook $hook) {
	
		global $messagesendflag;
		if ($messagesendflag !== 1) {
			return;
		}
		
		$entity = $hook->getEntityParam();
		if ($entity instanceof \ElggObject && $entity->getSubtype() == 'messages') {
			return true;
		}
	}
	
	/**
	 * Override the canEdit function to return true for messages within a particular context
	 *
	 * @param \Elgg\Hook $hook 'container_permissions_check', 'object'
	 *
	 * @return void|true
	 */
	public static function canEditContainer(\Elgg\Hook $hook) {
	
		global $messagesendflag;
		if ($messagesendflag == 1) {
			return true;
		}
	}
}
