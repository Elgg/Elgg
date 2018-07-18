<?php

namespace Elgg\Cli;

use Elgg\Cli\CronCommand;
use Elgg\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 * @group Plugins
 */
class PluginsActivateCommandTest extends UnitTestCase {

	public function up() {
		_elgg_services()->logger->disable();
	}

	public function down() {
		_elgg_services()->logger->enable();
	}

	/**
	 * @expectedException \Symfony\Component\Console\Exception\RuntimeException
	 */
	public function testRequiresPluginIds() {
		$application = new Application();

		$command = new PluginsActivateCommand();
		$application->add($command);

		$command = $application->find('plugins:activate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
		]);
	}

	public function testActivatesPlugins() {
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		_elgg_services()->plugins->addTestingPlugin($plugin);

		$application = new Application();

		$command = new PluginsActivateCommand();
		$application->add($command);

		$command = $application->find('plugins:activate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
			'plugins' => ['test_plugin', 'unknown'],
		]);

		$this->assertEquals(0, $commandTester->getStatusCode());
	}

}