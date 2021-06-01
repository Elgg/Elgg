<?php

namespace Elgg\Traits\Entity;

class ElggGroupPluginSettingsIntegrationTest extends ElggEntityPluginSettingsTestCase {

	/**
	 * {@inheritDoc}
	 */
	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
}
