<?php

namespace Elgg;

use Elgg\Database\DbConfig;
use ElggSession;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Laminas\Mail\Transport\InMemory;

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
