<?php

namespace Elgg\GarbageCollector;

use Elgg\Plugins\IntegrationTestCase;

class OptimizeCommandTest extends IntegrationTestCase {

	public function testExecute() {
		$this->assertStringContainsStringIgnoringCase(elgg_echo('garbagecollector:done'), $this->executeCliCommand(new OptimizeCommand()));
	}
}
