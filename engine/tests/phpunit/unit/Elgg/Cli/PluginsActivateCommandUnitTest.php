<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Exception\RuntimeException;

class PluginsActivateCommandUnitTest extends ExecuteCommandUnitTestCase {

	public function testRequiresPluginIds() {
		$this->expectException(RuntimeException::class);
		$this->executeCommand(new PluginsActivateCommand());
	}

	public function testActivatesPlugins() {
		$plugin = \ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/'));
		_elgg_services()->plugins->addTestingPlugin($plugin);

		$this->assertEquals(0, $this->executeCommand(new PluginsActivateCommand(), [
			'--force' => true,
			'plugins' => ['test_plugin', 'unknown'],
		], [], true));
	}
}
