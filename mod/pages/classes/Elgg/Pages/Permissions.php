<?php

namespace Elgg\Pages;

/**
 * Event callbacks for views
 *
 * @since 4.0
 * @internal
 */
class Permissions {

	/**
	 * Extend permissions checking to extend can-edit for write users.
	 *
	 * @param \Elgg\Event $event 'permissions_check', 'object'
	 *
	 * @return void|bool
	 */
	public static function allowWriteAccess(\Elgg\Event $event) {
		
		$entity = $event->getEntityParam();
		if (!$entity instanceof \ElggPage) {
			return;
		}
		
		$write_permission = (int) $entity->write_access_id;
		$user = $event->getUserParam();
	
		if (empty($write_permission) || !$user instanceof \ElggUser) {
			return;
		}
		
		switch ($write_permission) {
			case ACCESS_PRIVATE:
				// Elgg's default decision is what we want
				return;
			default:
				$list = elgg_get_access_array($user->guid);
				if (in_array($write_permission, $list)) {
					// user in the access collection
					return true;
				}
				break;
		}
	}
	
	/**
	 * Extend container permissions checking to extend container write access for write users, needed for personal pages
	 *
	 * @param \Elgg\Event $event 'container_permissions_check', 'object'
	 *
	 * @return void|bool
	 */
	public static function allowContainerWriteAccess(\Elgg\Event $event) {
		
		if ($event->getValue()) {
			// already have access
			return;
		}
		
		// check type/subtype
		if ($event->getType() !== 'object' || $event->getParam('subtype') !== 'page') {
			return;
		}
		
		$user = $event->getUserParam();
		if (!$user instanceof \ElggUser) {
			return;
		}
		
		// look up a page object given via input
		$page_guid = (int) get_input('guid'); // defined by route
		if (empty($page_guid)) {
			// try the parent guid for use in the action
			$page_guid = (int) get_input('parent_guid');
		}
		
		if (empty($page_guid)) {
			return;
		}
		
		$page = get_entity($page_guid);
		if (!$page instanceof \ElggPage) {
			return;
		}
		
		// check if the page write access is in the users read access array
		return in_array($page->write_access_id, elgg_get_access_array($user->guid));
	}
}
