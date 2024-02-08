<?php

namespace Elgg\Traits\Entity;

use Elgg\Traits\Entity\IconsIntegrationTestCase;

class ElggSiteIconsIntegrationTest extends IconsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createSite();
	}
}
