<?php

namespace Elgg\Messages;

/**
 * Callbacks for users
 *
 * @since 4.0
 * @internal
 */
class User {
	
	/**
	 * Delete messages from a user who is being deleted
	 *
	 * @param \Elgg\Event $event 'delete', 'user'
	 *
	 * @return void
	 */
	public static function purgeMessages(\Elgg\Event $event) {
		$user = $event->getObject();
		if (!$user->guid) {
			return;
		}
	
		// make sure we delete them all
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($user) {
			$batch = new \ElggBatch('elgg_get_entities', [
				'type' => 'object',
				'subtype' => 'messages',
				'metadata_name_value_pairs' => [
					'fromId' => $user->guid,
				],
				'limit' => false,
			]);
			$batch->setIncrementOffset(false);
			foreach ($batch as $e) {
				$e->delete();
			}
		});
	}
}
