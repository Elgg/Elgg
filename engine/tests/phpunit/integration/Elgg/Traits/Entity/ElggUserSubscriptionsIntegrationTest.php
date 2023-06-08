<?php

namespace Elgg\Traits\Entity;

class ElggUserSubscriptionsIntegrationTest extends SubscriptionsIntegrationTestCase {

	protected function getEntity(): \ElggEntity {
		return $this->createUser();
	}
}
