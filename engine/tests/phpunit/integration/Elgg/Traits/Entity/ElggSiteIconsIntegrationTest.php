<?php

namespace Elgg\Traits\Entity;

class ElggSiteIconsIntegrationTest extends IconsIntegrationTestCase {
	
	protected function getEntity(): \ElggEntity {
		return $this->createSite();
	}
}
