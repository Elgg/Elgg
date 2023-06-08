<?php

namespace Elgg\Traits\Entity;

class ElggGroupSubscriptionsIntegrationTest extends SubscriptionsIntegrationTestCase {

	protected function getEntity(): \ElggEntity {
		$owner = $this->createUser();
		$group = $this->createGroup([
			'owner_guid' => $owner->guid,
		]);
		
		$group->removeSubscriptions($owner->guid);
		
		return $group;
	}
}
