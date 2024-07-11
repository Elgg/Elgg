<?php

namespace Elgg\Cli;

use Elgg\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Console\Exception\RuntimeException;

class PluginsDeactivateCommandUnitTest extends UnitTestCase {
	
	/**
	 * @var OutputInterface
	 */
	protected $backup_cli_output;
	
	public function up() {
		_elgg_services()->logger->disable();
		
		$this->backup_cli_output = _elgg_services()->get('cli_output');
		
		$cli_output = new NullOutput();
		$cli_output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
		_elgg_services()->set('cli_output', $cli_output);
	}
	
	public function down() {
		_elgg_services()->logger->enable();
		_elgg_services()->set('cli_output', $this->backup_cli_output);
	}

	public function testRequiresPluginIds() {
		$application = new Application();

		$command = new PluginsDeactivateCommand();
		$application->add($command);

		$command = $application->find('plugins:deactivate');
		$commandTester = new CommandTester($command);
		
		$this->expectException(RuntimeException::class);
		$commandTester->execute([
			'command' => $command->getName(),
		]);
	}

	public function testDeactivatesPlugins() {
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		$plugin->activate();
		_elgg_services()->plugins->addTestingPlugin($plugin);

		$application = new Application();

		$command = new PluginsDeactivateCommand();
		$application->add($command);

		$command = $application->find('plugins:deactivate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
			'plugins' => ['test_plugin', 'unknown'],
		]);

		$this->assertEquals(0, $commandTester->getStatusCode());
	}
}
