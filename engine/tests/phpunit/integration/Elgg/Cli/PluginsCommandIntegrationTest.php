<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class PluginsCommandIntegrationTest extends ExecuteCommandIntegrationTestCase {

	public function up() {
		parent::up();
		
		$this->setupApplication([
			'plugins_path' => $this->normalizeTestFilePath('mod/'),
		]);

		$ids = [
			'parent_plugin',
			'dependent_plugin',
			'conflicting_plugin',
			'static_config',
		];

		foreach ($ids as $id) {
			$plugin = \ElggPlugin::fromId($id, $this->normalizeTestFilePath('mod/'));
			$plugin->save();
			$plugin->deactivate();
		}
	}

	public function down() {
		$ids = [
			'parent_plugin',
			'dependent_plugin',
			'conflicting_plugin',
			'static_config',
		];

		foreach ($ids as $id) {
			elgg_call(ELGG_IGNORE_ACCESS, function() use ($id) {
				elgg_get_plugin_from_id($id)->delete();
			});
		}
		
		parent::down();
	}

	public function testActivatesPluginsWithDependencies() {
		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertFalse(elgg_is_active_plugin('dependent_plugin'));
		
		$this->assertEquals(SymfonyCommand::SUCCESS, $this->executeCommand(new PluginsActivateCommand(), [
			'--force' => true,
			'plugins' => ['dependent_plugin'],
		], [], true));

		$this->assertTrue(elgg_is_active_plugin('parent_plugin'));
		$this->assertTrue(elgg_is_active_plugin('dependent_plugin'));
	}

	public function testActivatesPluginsWithOrder() {
		$static = elgg_get_plugin_from_id('static_config');
		
		$static->deactivate();
		$static->setPriority('first');
		$current_priority = $static->getPriority();
		
		$parent = elgg_get_plugin_from_id('parent_plugin');
		
		$this->assertLessThan($parent->getPriority(), $static->getPriority());
		
		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertFalse(elgg_is_active_plugin('static_config'));
		
		$this->assertEquals(SymfonyCommand::SUCCESS, $this->executeCommand(new PluginsActivateCommand(), [
			'--force' => true,
			'plugins' => ['static_config:last', 'parent_plugin'],
		], [], true));
		
		$this->assertTrue(elgg_is_active_plugin('parent_plugin'));
		$this->assertTrue(elgg_is_active_plugin('static_config'));
		
		$static = elgg_get_plugin_from_id('static_config');
		$parent = elgg_get_plugin_from_id('parent_plugin');
		
		$this->assertNotEquals($current_priority, $static->getPriority());
		$this->assertGreaterThan($parent->getPriority(), $static->getPriority());
	}

	public function testDeactivatesConflictingPlugins() {
		elgg_get_plugin_from_id('parent_plugin')->activate();

		$this->assertTrue(elgg_is_active_plugin('parent_plugin'));
		$this->assertFalse(elgg_is_active_plugin('conflicting_plugin'));

		$this->assertEquals(SymfonyCommand::SUCCESS, $this->executeCommand(new PluginsActivateCommand(), [
			'--force' => true,
			'plugins' => ['conflicting_plugin'],
		], [], true));

		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertTrue(elgg_is_active_plugin('conflicting_plugin'));
	}

	public function testDeactivatesDependentPlugins() {
		elgg_get_plugin_from_id('parent_plugin')->activate();
		elgg_get_plugin_from_id('dependent_plugin')->activate();

		$this->assertEquals(SymfonyCommand::SUCCESS, $this->executeCommand(new PluginsDeactivateCommand(), [
			'--force' => true,
			'plugins' => ['parent_plugin'],
		], [], true));

		$this->assertFalse(elgg_is_active_plugin('parent_plugin'));
		$this->assertFalse(elgg_is_active_plugin('dependent_plugin'));
	}
}
