<?php

namespace Elgg\Cli;

class UpgradeListCommandIntegrationTest extends ExecuteCommandIntegrationTestCase {

	public function testExecuteWithoutOptions() {
		$this->assertStringContainsStringIgnoringCase(elgg_echo('cli:upgrade:list:completed'), $this->executeCommand(new UpgradeListCommand()));
	}
}
