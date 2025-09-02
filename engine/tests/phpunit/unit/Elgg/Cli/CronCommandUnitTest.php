<?php

namespace Elgg\Cli;

class CronCommandUnitTest extends ExecuteCommandUnitTestCase {

	public function up() {
		parent::up();
		
		// need to disable testing mode otherwise there is no cron log output
		_elgg_services()->config->testing_mode = false;
	}
	
	public function testExecuteWithoutOptions() {
		$output = $this->executeCommand(new CronCommand());
		
		$this->assertMatchesRegularExpression('/Cron jobs for .* started/im', $output);
		$this->assertMatchesRegularExpression('/Cron jobs for .* completed/im', $output);
	}

	public function testExecuteWithPeriod() {
		$output = $this->executeCommand(new CronCommand(), [
			'--interval' => "hourly",
			'--time' => '2017-12-31 0:00:00',
		]);

		$this->assertMatchesRegularExpression('/Cron jobs for \"hourly\" started/im', $output);
		$this->assertMatchesRegularExpression('/Cron jobs for \"hourly\" completed/im', $output);
	}

	public function testExecuteWithQuietOutput() {
		$output = $this->executeCommand(new CronCommand(), [
			'--quiet' => true,
		]);

		$this->assertEmpty($output);
	}
}
