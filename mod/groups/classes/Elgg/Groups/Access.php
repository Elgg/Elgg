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
		
		$acl = _groups_get_group_acl($group);
		
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
}
