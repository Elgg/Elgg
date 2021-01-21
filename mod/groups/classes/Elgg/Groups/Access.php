<?php

namespace Elgg\Groups;

/**
 * Handle access related hooks
 *
 * @since 3.2
 * @internal
 */
class Access {

	/**
	 * Set the default access for content in a group
	 *
	 * @param \Elgg\Hook $hook 'default', 'access'
	 *
	 * @return void|int
	 */
	public static function getDefaultAccess(\Elgg\Hook $hook) {
		
		$group = self::getGroupFromDefaultAccessHook($hook);
		if (!$group instanceof \ElggGroup) {
			return;
		}
		
		if (!isset($group->content_default_access)) {
			return;
		}
		
		$acl = $group->getOwnedAccessCollection('group_acl');
		
		$access = (int) $group->content_default_access;
		if ($access === ACCESS_PRIVATE) {
			// stored access private means group members
			if ($acl instanceof \ElggAccessCollection) {
				return $acl->id;
			}
			
			// something is wrong here, bail
			return;
		}
		
		if ($group->getContentAccessMode() !== \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
			// access isn't limited by content access mode
			return $access;
		}
		
		// default access is set higher that content access mode allows, so return group acl
		if ($acl instanceof \ElggAccessCollection) {
			return $acl->id;
		}
	}
	
	/**
	 * Get the correct group for default access
	 *
	 * @param \Elgg\Hook $hook 'default', 'access'
	 *
	 * @return false|\ElggGroup
	 */
	protected static function getGroupFromDefaultAccessHook(\Elgg\Hook $hook) {
		
		$input_params = $hook->getParam('input_params');
		
		// try supplied container guid
		$container_guid = elgg_extract('container_guid', $input_params);
		$container = get_entity($container_guid);
		if ($container_guid instanceof \ElggGroup) {
			return $container;
		}
		
		// try page owner
		$page_owner = elgg_get_page_owner_entity();
		if ($page_owner instanceof \ElggGroup) {
			return $page_owner;
		}
		
		return false;
	}
	
	/**
	 * Return the write access for the current group if the user has write access to it
	 *
	 * @param \Elgg\Hook $hook 'access:collection:write' 'all'
	 * @return void|array
	 */
	public static function getWriteAccess(\Elgg\Hook $hook) {
	
		$user_guid = $hook->getParam('user_id');
		$user = get_user($user_guid);
		if (!$user) {
			return;
		}
	
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggGroup) {
			return;
		}
	
		if (!$page_owner->isMember($user) && !$page_owner->canEdit($user_guid)) {
			return;
		}
	
		$allowed_access = [ACCESS_PRIVATE];
		$acl = $page_owner->getOwnedAccessCollection('group_acl');
		if ($acl instanceof \ElggAccessCollection) {
			$allowed_access[] = $acl->id;
		}
	
		if ($page_owner->getContentAccessMode() !== \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
			// don't allow content sharing with higher levels than group access level
			// see https://github.com/Elgg/Elgg/issues/10285
			if (in_array($page_owner->access_id, [ACCESS_PUBLIC, ACCESS_LOGGED_IN])) {
				// at least logged in is allowed
				$allowed_access[] = ACCESS_LOGGED_IN;
				
				if ($page_owner->access_id === ACCESS_PUBLIC && !elgg_get_config('walled_garden')) {
					// public access is allowed
					$allowed_access[] = ACCESS_PUBLIC;
				}
			}
		}
	
		$write_acls = $hook->getValue();
	
		// add write access to the group
		if ($acl) {
			$write_acls[$acl->id] = $acl->getDisplayName();
		}
	
		// remove all but the allowed access levels
		foreach (array_keys($write_acls) as $access_id) {
			if (!in_array($access_id, $allowed_access)) {
				unset($write_acls[$access_id]);
			}
		}
	
		return $write_acls;
	}
	
	/**
	 * Return the write access for the current group if the user has write access to it
	 *
	 * @param \Elgg\Hook $hook 'access_collection:name' 'access_collection'
	 * @return void|string
	 */
	public static function getAccessCollectionName(\Elgg\Hook $hook) {
	
		$access_collection = $hook->getParam('access_collection');
		if (!$access_collection instanceof \ElggAccessCollection) {
			return;
		}
	
		$owner = $access_collection->getOwnerEntity();
		if (!$owner instanceof \ElggGroup) {
			return;
		}
		
		$page_owner = elgg_get_page_owner_entity();
	
		if ($page_owner && $page_owner->guid === $owner->guid) {
			return elgg_echo('groups:acl:in_context');
		}
	
		$user = elgg_get_logged_in_user_entity();
		if (empty($user)) {
			return;
		}
		
		if ($owner->isMember($user) || $owner->canEdit($user->guid)) {
			return elgg_echo('groups:acl', [$owner->getDisplayName()]);
		}
	}
	

	/**
	 * Allow users to visit the group profile page even if group content access mode is set to group members only
	 *
	 * @param \Elgg\Hook $hook 'gatekeeper' 'group:group'
	 *
	 * @return void|true
	 */
	public static function allowProfilePage(\Elgg\Hook $hook) {
	
		$entity = $hook->getEntityParam();
		if (!has_access_to_entity($entity)) {
			return;
		}
	
		$route = $hook->getParam('route');
		if ($route === 'view:group' || $route === 'view:group:group') {
			return true;
		}
	}
	
	/**
	 * The default access for members only content is this group only. This makes
	 * for better display of access (can tell it is group only), but does not change
	 * access to the content.
	 *
	 * @param \Elgg\Hook $hook 'default', 'access'
	 *
	 * @return int|void
	 */
	public static function overrideDefaultAccess(\Elgg\Hook $hook) {
		$page_owner = elgg_get_page_owner_entity();
		if (!$page_owner instanceof \ElggGroup) {
			return;
		}
				
		if ($page_owner->getContentAccessMode() !== \ElggGroup::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
			return;
		}
		
		$acl = $page_owner->getOwnedAccessCollection('group_acl');
		if (!$acl instanceof \ElggAccessCollection) {
			return;
		}
		
		return $acl->id;
	}
}
