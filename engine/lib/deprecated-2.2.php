<?php

/**
 * Determine if a given user can write to an entity container.
 *
 * An entity can be a container for any other entity by setting the
 * container_guid.  container_guid can differ from owner_guid.
 *
 * A plugin hook container_permissions_check:$entity_type is emitted to allow granular
 * access controls in plugins.
 *
 * @param int    $user_guid      The user guid, or 0 for logged in user.
 * @param int    $container_guid The container, or 0 for the current page owner.
 * @param string $type           The type of entity we want to create (default: 'all')
 * @param string $subtype        The subtype of the entity we want to create (default: 'all')
 *
 * @return bool
 * @deprecated 2.2
 */
function can_write_to_container($user_guid = 0, $container_guid = 0, $type = 'all', $subtype = 'all') {
	elgg_deprecated_notice(__FUNCTION__ . ' is deprecated. Use ElggEntity::canWriteToContainer()', '2.2');
	if (!$container_guid) {
		$container_guid = elgg_get_page_owner_guid();
	}
	$container = get_entity($container_guid);
	if (!$container) {
		return false;
	}

	return $container->canWriteToContainer($user_guid, $type, $subtype);
}
