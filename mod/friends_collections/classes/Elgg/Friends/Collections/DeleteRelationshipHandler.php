<?php

namespace Elgg\Friends\Collections;

use Elgg\Event;
use ElggRelationship;

/**
 * Delete friend relationship handler
 */
class DeleteRelationshipHandler {

	/**
	 * Remove user from friend collections, when 'friend' relationships is deleted
	 *
	 * @param \Elgg\Event $event 'delete' 'relationship'
	 * @return void
	 */
	public function __invoke(Event $event) {

		$relationship = $event->getObject();
		if (!$relationship instanceof ElggRelationship) {
			return;
		}

		if ($relationship->relationship != 'friend') {
			return;
		}

		$collections = elgg_get_access_collections([
			'owner_guid' => $relationship->guid_one,
			'subtype' => 'friends_collection',
		]);
		
		if (empty($collections)) {
			return;
		}
		
		foreach ($collections as $collection) {
			remove_user_from_access_collection($relationship->guid_two, $collection->id);
		}
	}
}
