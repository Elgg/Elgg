<?php

namespace Elgg\Cli;

use Elgg\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 * @group Plugins
 */
class PluginsListCommandTest extends UnitTestCase {

	public function up() {
		_elgg_services()->logger->disable();
	}

	public function down() {
		_elgg_services()->logger->enable();
	}

	/**
	 * @dataProvider statusProvider
	 */
	public function testCanExecuteCommand($status, $exit_code) {
		$application = new Application();

		$command = new PluginsListCommand();
		$application->add($command);

		$command = $application->find('plugins:list');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--status' => $status,
		]);

		$this->assertEquals($exit_code, $commandTester->getStatusCode());
	}

	public function statusProvider() {
		return [
			[null, 0],
			['all', 0],
			['active', 0],
			['inactive', 0],
			['enabled', 1],
		];
	}

	public function testCommandOutputContainsInfo() {
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		$this->assertTrue($plugin->activate());

		_elgg_services()->plugins->addTestingPlugin($plugin);

		$application = new Application();

		$command = new PluginsListCommand();
		$application->add($command);

		$command = $application->find('plugins:list');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
		]);

		$this->assertMatchesRegularExpression('/test_plugin/im', $commandTester->getDisplay());
		$this->assertMatchesRegularExpression('/1.9/im', $commandTester->getDisplay());
		$this->assertMatchesRegularExpression('/active/im', $commandTester->getDisplay());
	}

	public function testRefreshOption() {
		$application = new Application();

		$command = new PluginsListCommand();
		$application->add($command);

		$command = $application->find('plugins:list');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
		]);

		$this->assertDoesNotMatchRegularExpression('/test_plugin/im', $commandTester->getDisplay());

		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--refresh' => true,
		]);

		$this->assertMatchesRegularExpression('/test_plugin/im', $commandTester->getDisplay());
	}

}
