<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Exception\RuntimeException;

class PluginsDeactivateCommandUnitTest extends ExecuteCommandUnitTestCase {

	public function testRequiresPluginIds() {
		$this->expectException(RuntimeException::class);
		$this->executeCommand(new PluginsDeactivateCommand());
	}

	public function testDeactivatesPlugins() {
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		$plugin->activate();
		_elgg_services()->plugins->addTestingPlugin($plugin);

		$this->assertEquals(0, $this->executeCommand(new PluginsDeactivateCommand(), [
			'--force' => true,
			'plugins' => ['test_plugin', 'unknown'],
		], [], true));
	}
}
