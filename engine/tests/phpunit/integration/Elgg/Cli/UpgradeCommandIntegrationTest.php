<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class UpgradeCommandIntegrationTest extends ExecuteCommandIntegrationTestCase {

	public function down() {
		if (_elgg_services()->mutex->isLocked('upgrade')) {
			_elgg_services()->mutex->unlock('upgrade');
		}
	}

	public function testExecute() {
		$this->assertStringContainsStringIgnoringCase(elgg_echo('cli:upgrade:system:upgraded'), $this->executeCommand(new UpgradeCommand()));
	}
	
	public function testExecuteFailsWhenLocked() {
		_elgg_services()->mutex->lock('upgrade');
		
		$this->assertEquals(SymfonyCommand::FAILURE, $this->executeCommand(new UpgradeCommand(), [], [], true));
	}
	
	public function testExecuteForceWhenLocked() {
		_elgg_services()->mutex->lock('upgrade');
		
		$this->assertStringContainsStringIgnoringCase(elgg_echo('cli:upgrade:system:upgraded'), $this->executeCommand(new UpgradeCommand(), [
			'--force' => true,
		]));
	}

	public function testExecuteAsyncUpgrades() {
		$output = $this->executeCommand(new UpgradeCommand(), [
			'async' => ['async'],
		]);

		$this->assertStringContainsStringIgnoringCase(elgg_echo('cli:upgrade:system:upgraded'), $output);
		$this->assertStringContainsStringIgnoringCase(elgg_echo('cli:upgrade:async:upgraded'), $output);
	}
}
