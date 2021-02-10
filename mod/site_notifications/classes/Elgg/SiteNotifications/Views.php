<?php

namespace Elgg\SiteNotifications;

/**
 * View hooks
 *
 * @since 4.0
 * @internal
 */
class Views {
	
	/**
	 * Mark related site notifications as read when viewing a full view of an entity
	 *
	 * @param \Elgg\Hook $hook 'view_vars', 'object/elemens/full'
	 *
	 * @return void
	 */
	public static function markLinkedEntityRead(\Elgg\Hook $hook) {
		
		$user = elgg_get_logged_in_user_entity();
		if (empty($user)) {
			return;
		}
		
		$vars = $hook->getValue();
		$entity = elgg_extract('entity', $vars);
		if (!$entity instanceof \ElggEntity) {
			return;
		}
		
		/* @var $batch \ElggBatch */
		$batch = elgg_get_entities([
			'type' => 'object',
			'subtype' => 'site_notification',
			'owner_guid' => $user->guid,
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
			'metadata_name_value_pairs' => [
				[
					'name' => 'linked_entity_guid',
					'value' => $entity->guid,
				],
				[
					'name' => 'read',
					'value' => false,
				],
			],
		]);
		/* @var $entity \SiteNotification */
		foreach ($batch as $entity) {
			$entity->read = true;
		}
	}
}
