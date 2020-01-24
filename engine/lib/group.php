<?php
/**
 * Elgg Groups.
 * Groups contain other entities, or rather act as a placeholder for other entities to
 * mark any given container as their container.
 */

/**
 * Checks if a group has a specific tool enabled.
 * Forward to the group if the tool is disabled.
 *
 * @param string $option     The group tool option to check
 * @param int    $group_guid The group that owns the page. If not set, this
 *                           will be pulled from elgg_get_page_owner_guid().
 *
 * @return void
 * @throws \Elgg\Http\Exception\GroupToolGatekeeperException
 * @since 3.0.0
 */
function elgg_group_tool_gatekeeper($option, $group_guid = null) {
	$group_guid = $group_guid ?: elgg_get_page_owner_guid();
	
	$group = get_entity($group_guid);
	if (!$group instanceof \ElggGroup) {
		return;
	}
	
	if ($group->isToolEnabled($option)) {
		return;
	}

	$ex = new \Elgg\Http\Exception\GroupToolGatekeeperException();
	$ex->setRedirectUrl($group->getURL());
	$ex->setParams([
		'entity' => $group,
		'tool' => $option,
	]);

	throw $ex;
}

/**
 * Allow group members to write to the group container
 *
 * @param \Elgg\Hook $hook 'container_permissions_check', 'all'
 *
 * @return bool
 * @internal
 */
function _elgg_groups_container_override(\Elgg\Hook $hook) {
	$container = $hook->getParam('container');
	$user = $hook->getUserParam();

	if ($container instanceof ElggGroup && $user) {
		if ($container->isMember($user)) {
			return true;
		}
	}
}

/**
 * Don't allow users to comment on content in a group they aren't a member of
 *
 * @param \Elgg\Hook $hook 'permissions_check:comment', 'object'
 *
 * @return void|false
 * @internal
 * @since 3.1
 */
function _elgg_groups_comment_permissions_override(\Elgg\Hook $hook) {
	
	if ($hook->getValue() === false) {
		// already not allowed, no need to check further
		return;
	}
	
	$entity = $hook->getEntityParam();
	$user = $hook->getUserParam();
	
	if (!$entity instanceof ElggObject || !$user instanceof ElggUser) {
		return;
	}
	
	$container = $entity->getContainerEntity();
	if (!$container instanceof ElggGroup) {
		return;
	}
	
	if ($container->isMember($user)) {
		return;
	}
	
	return false;
}

/**
 * init the groups library
 *
 * @return void
 *
 * @internal
 */
function _elgg_groups_init() {
	elgg_register_plugin_hook_handler('container_permissions_check', 'all', '_elgg_groups_container_override');
	elgg_register_plugin_hook_handler('permissions_check:comment', 'object', '_elgg_groups_comment_permissions_override', 999);
}

/**
 * @see \Elgg\Application::loadCore Do not do work here. Just register for events.
 */
return function(\Elgg\EventsService $events, \Elgg\HooksRegistrationService $hooks) {
	$events->registerHandler('init', 'system', '_elgg_groups_init');
};
