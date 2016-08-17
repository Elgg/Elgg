<?php

namespace Elgg\Mocks\Database;

use Elgg\Config;
use Elgg\Database;
use Elgg\Database\AccessCollections as DbAccessCollections;
use Elgg\Database\EntityTable as DbEntityTable;
use Elgg\I18n\Translator;
use Elgg\PluginHooksService;
use ElggSession;
use ElggStaticVariableCache;

class AccessCollections extends DbAccessCollections {

	/**
	 * {@inheritdoc}
	 */
	public function __construct(
			Config $config,
			Database $db,
			DbEntityTable $entities,
			ElggStaticVariableCache $cache,
			PluginHooksService $hooks,
			ElggSession $session,
			Translator $translator) {
		parent::__construct($config, $db, $entities, $cache, $hooks, $session, $translator);
	}

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
