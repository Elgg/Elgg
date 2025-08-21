<?php

namespace Elgg\Cli;

class CacheInvalidateCommandIntegrationTest extends ExecuteCommandIntegrationTestCase {

	public function testExecuteWithoutOptions() {
		$this->assertMatchesRegularExpression('/' . elgg_echo('admin:cache:invalidated') . '/im', $this->executeCommand(new CacheInvalidateCommand()));
	}

	public function testExecuteWithQuietOutput() {
		$this->assertEmpty($this->executeCommand(new CacheInvalidateCommand(), [
			'--quiet' => true,
		]));
	}
}
