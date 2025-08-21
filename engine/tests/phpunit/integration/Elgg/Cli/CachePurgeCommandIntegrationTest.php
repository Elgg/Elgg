<?php

namespace Elgg\Cli;

class CachePurgeCommandIntegrationTest extends ExecuteCommandIntegrationTestCase {

	public function testExecuteWithoutOptions() {
		$this->assertMatchesRegularExpression('/' . elgg_echo('admin:cache:purged') . '/im', $this->executeCommand(new CachePurgeCommand()));
	}

	public function testExecuteWithQuietOutput() {
		$this->assertEmpty($this->executeCommand(new CachePurgeCommand(), [
			'--quiet' => true,
		]));
	}
}
