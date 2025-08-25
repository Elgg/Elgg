<?php

namespace Elgg\Cli;

class UpgradeBatchCommandIntegrationTest extends ExecuteCommandIntegrationTestCase {

	public function testExecuteWithoutOptions() {
		$this->assertStringContainsString(elgg_echo('cli:upgrade:batch:finished'), $this->executeCommand(new UpgradeBatchCommand(), [
			'upgrades' => ['RandomNonExistingClass'],
		]));
	}

	public function testExecuteWithQuietOutput() {
		$this->assertEmpty($this->executeCommand(new UpgradeBatchCommand(), [
			'upgrades' => ['RandomNonExistingClass'],
			'--quiet' => true,
		]));
	}
}
