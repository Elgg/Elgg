<?php

namespace Elgg\Traits\Entity;

class ElggUserSubscriptionsIntegrationTest extends ElggEntitySubscriptionsIntegrationTestCase {

	protected function getEntity(): \ElggEntity {
		return $this->createUser();
	}
}
