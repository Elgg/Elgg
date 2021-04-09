<?php

namespace Elgg\Traits\Entity;

class ElggObjectSubscriptionsIntegrationTest extends ElggEntitySubscriptionsIntegrationTestCase {

	protected function getEntity(): \ElggEntity {
		$owner = $this->createUser();
		$object = $this->createObject([
			'owner_guid' => $owner->guid,
		]);
		
		$object->removeSubscriptions($owner->guid);
		
		return $object;
	}
}
