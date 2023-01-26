<?php

namespace Elgg\Traits\Entity;

class ElggGroupPluginSettingsIntegrationTest extends ElggEntityPluginSettingsIntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
}
