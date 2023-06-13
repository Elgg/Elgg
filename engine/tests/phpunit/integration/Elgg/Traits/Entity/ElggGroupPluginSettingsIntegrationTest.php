<?php

namespace Elgg\Traits\Entity;

class ElggGroupPluginSettingsIntegrationTest extends PluginSettingsIntegrationTestCase {

	/**
	 * {@inheritDoc}
	 */
	protected function getEntity(): \ElggEntity {
		return $this->createGroup();
	}
}
