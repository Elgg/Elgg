<?php

namespace Elgg\Mocks\Database;

use Elgg\Database\AccessCollections as DbAccessCollections;

class AccessCollections extends DbAccessCollections {

	/**
	 * {@inheritdoc}
	 */
	public function hasAccessToEntity($entity, $user = null) {
		if ($entity->access_id == ACCESS_PUBLIC) {
			return true;
		}
		if ($entity->access_id == ACCESS_LOGGED_IN && elgg_is_logged_in()) {
			return true;
		}
		if (!$user) {
			$user = elgg_get_logged_in_user_entity();
		}
		if (!$user) {
			return false;
		}
		if ($user->isAdmin()) {
			return true;
		}
		if ($entity->owner_guid == $user->guid) {
			return true;
		}
		if ($entity->access_id == ACCESS_PRIVATE && $entity->owner_guid == $user->guid) {
			return true;
		}
		return false;
	}

}
