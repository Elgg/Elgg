<?php

namespace Elgg\Friends\Collections;

use Elgg\Event;
use Elgg\Notifications\Event as Event2;
use ElggRelationship;

class DeleteRelationshipHandler {

	/**
	 * Remove user from friend collections, when 'friend' relationships is deleted
	 *
	 * @elgg_event delete relationship
	 *
	 * @param Event2 $event Event
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


		$collections = get_user_access_collections($relationship->guid_one);
		if (!empty($collections)) {
			foreach ($collections as $collection) {
				remove_user_from_access_collection($relationship->guid_two, $collection->id);
			}
		}

	}
}
