<?php

namespace Elgg\Cli;

class InstallCommandUnitTest extends ExecuteCommandUnitTestCase {

	public function testExecute() {
		$this->markTestSkipped('Can\'t test the installer yet');

		$output = $this->executeCommand(new InstallCommand());

		dump($output);
	}
}
