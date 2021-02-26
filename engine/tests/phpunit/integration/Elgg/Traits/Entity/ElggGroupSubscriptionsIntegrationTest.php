<?php

namespace Elgg\Traits\Entity;

class ElggGroupSubscriptionsIntegrationTest extends ElggEntitySubscriptionsIntegrationTestCase {

	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
}
