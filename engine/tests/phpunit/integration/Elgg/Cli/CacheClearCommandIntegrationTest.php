<?php

namespace Elgg\Cli;

class CacheClearCommandIntegrationTest extends ExecuteCommandIntegrationTestCase {

	public function testExecuteWithoutOptions() {
		$this->assertMatchesRegularExpression('/' . elgg_echo('admin:cache:cleared') . '/im', $this->executeCommand(new CacheClearCommand()));
	}

	public function testExecuteWithQuietOutput() {
		$this->assertEmpty($this->executeCommand(new CacheClearCommand(), [
			'--quiet' => true,
		]));
	}
}
