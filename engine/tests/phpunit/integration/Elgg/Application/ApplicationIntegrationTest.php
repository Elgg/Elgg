<?php

namespace Elgg\Application;

use Elgg\Application;
use Elgg\IntegrationTestCase;

/**
 * @group Application
 * @group IntegrationTests
 */
class ApplicationIntegrationTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * Parent class will also make assertions that hooks and events
	 * are identical after multiple bootstraps, which will indicate
	 * that plugins can start multiple times
	 */
	public function testCanCreateMultipleApplications() {
		self::createApplication();
		$app2 = self::createApplication();

		$this->assertSame(Application::$_instance, $app2);
	}
}