<?php

namespace Elgg\Cli;

class PluginsListCommandUnitTest extends ExecuteCommandUnitTestCase {

	/**
	 * @dataProvider statusProvider
	 */
	public function testCanExecuteCommand($status, $exit_code) {
		$this->assertEquals($exit_code, $this->executeCommand(new PluginsListCommand(), [
			'--status' => $status,
		], [], true));
	}

	public static function statusProvider() {
		return [
			[null, 1], // should default to all
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

		$output = $this->executeCommand(new PluginsListCommand());

		$this->assertMatchesRegularExpression('/test_plugin/im', $output);
		$this->assertMatchesRegularExpression('/1.9/im', $output);
		$this->assertMatchesRegularExpression('/active/im', $output);
	}

	public function testRefreshOption() {
		$output = $this->executeCommand(new PluginsListCommand());

		$this->assertDoesNotMatchRegularExpression('/test_plugin/im', $output);

		$output = $this->executeCommand(new PluginsListCommand(), [
			'--refresh' => true,
		]);

		$this->assertMatchesRegularExpression('/test_plugin/im', $output);
	}

}
