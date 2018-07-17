<?php

namespace Elgg\Cli;

use Elgg\Cli\CronCommand;
use Elgg\IntegrationTestCase;
use Elgg\UnitTestCase;
use hypeJunction\Twig\App;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 * @group Plugins
 */
class PluginsCommandTest extends IntegrationTestCase {

	public function up() {
		$this->createApplication([
			'isolate' => true,
			'plugins_path' => $this->normalizeTestFilePath('mod/'),
		]);

		_elgg_services()->logger->disable();

		$ids = [
			'parent_plugin',
			'dependent_plugin',
			'conflicting_plugin',
		];

		foreach ($ids as $id) {
			$plugin = \ElggPlugin::fromId($id, $this->normalizeTestFilePath('mod/'));
			$plugin->deactivate();
		}
	}

	public function down() {
		$ids = [
			'parent_plugin',
			'dependent_plugin',
			'conflicting_plugin',
		];

		foreach ($ids as $id) {
			elgg_call(ELGG_IGNORE_ACCESS, function() use ($id) {
				elgg_get_plugin_from_id($id)->delete();
			});
		}

		_elgg_services()->logger->enable();
	}

	public function testActivatesPluginsWithDependencies() {
		$application = new Application();

		$command = new PluginsActivateCommand();
		$application->add($command);

		$command = $application->find('plugins:activate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
			'plugins' => ['dependent_plugin'],
		]);

		$this->assertEquals(0, $commandTester->getStatusCode());

		$this->assertTrue(elgg_is_active_plugin('parent_plugin'));
		$this->assertTrue(elgg_is_active_plugin('dependent_plugin'));
	}

	public function testDeactivatesConflictingPlugins() {
		elgg_get_plugin_from_id('parent_plugin')->activate();

		$application = new Application();

		$command = new PluginsActivateCommand();
		$application->add($command);

		$command = $application->find('plugins:activate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
			'plugins' => ['conflicting_plugin'],
		]);

		$this->assertEquals(0, $commandTester->getStatusCode());

		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertTrue(elgg_is_active_plugin('conflicting_plugin'));
	}

	public function testDeactivatesDependentPlugins() {
		elgg_get_plugin_from_id('parent_plugin')->activate();
		elgg_get_plugin_from_id('dependent_plugin')->activate();

		$application = new Application();

		$command = new PluginsDeactivateCommand();
		$application->add($command);

		$command = $application->find('plugins:deactivate');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--force' => true,
			'plugins' => ['parent_plugin'],
		]);

		$this->assertEquals(0, $commandTester->getStatusCode());

		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertFalse(elgg_is_active_plugin('dependent_plugin'));
	}
}