<?php

namespace Elgg;

/**
 * Integration test abstraction
 *
 * Extend this class to run tests against an actual database
 * DO NOT RUN ON PRODUCTION
 */
abstract class IntegrationTestCase extends BaseIntegrationTestCase {

	/**
	 * {@inheritdoc}
	 */
	final protected function setUp(): void {
		parent::setUp();

		$this->up();
	}
}
