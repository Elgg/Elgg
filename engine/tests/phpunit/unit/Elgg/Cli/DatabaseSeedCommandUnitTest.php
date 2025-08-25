<?php

namespace Elgg\Cli;

use Elgg\Helpers\Cli\CliSeeder;

class DatabaseSeedCommandUnitTest extends ExecuteCommandUnitTestCase {

	public function testSeedCommand() {
		$this->registerTestingEvent('seeds', 'database', function(\Elgg\Event $event) {
			$value = $event->getValue();
			$value[] = CliSeeder::class;
			return $value;
		});

		$output = $this->executeCommand(new DatabaseSeedCommand(), [
			'--limit' => '2',
		]);

		$seeder = preg_quote(CliSeeder::class);
		$this->assertMatchesRegularExpression("/{$seeder}::seed/im", $output);
	}
	
	public function testUnseedCommand() {
		$this->registerTestingEvent('seeds', 'database', function(\Elgg\Event $event) {
			$value = $event->getValue();
			$value[] = CliSeeder::class;
			return $value;
		});

		$output = $this->executeCommand(new DatabaseUnseedCommand());

		$seeder = preg_quote(CliSeeder::class);
		$this->assertMatchesRegularExpression("/{$seeder}::unseed/im", $output);
	}
	
	public function testSeedersCommand() {
		$this->registerTestingEvent('seeds', 'database', function(\Elgg\Event $event) {
			$value = $event->getValue();
			$value[] = CliSeeder::class;
			return $value;
		});
		
		$output = $this->executeCommand(new DatabaseSeedCommand());
		
		// output should contain the seeder classname and the type of what is seeded
		$seeder = preg_quote(CliSeeder::class);
		$type = preg_quote(CliSeeder::getType());
		
		$this->assertMatchesRegularExpression("/{$seeder}/im", $output);
		$this->assertMatchesRegularExpression("/{$type}/im", $output);
	}
}
