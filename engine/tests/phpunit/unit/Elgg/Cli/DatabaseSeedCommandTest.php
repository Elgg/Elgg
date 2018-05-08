<?php

namespace Elgg\Cli;

use Elgg\Database\Seeds\Seed;
use Elgg\Hook;
use Elgg\UnitTestCase;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 * @group Database
 */
class DatabaseSeedCommandTest extends UnitTestCase {

	public function up() {
	}

	public function down() {

	}

	public function testExecute() {
		$hook = $this->registerTestingHook('seeds', 'database', function(Hook $hook) {
			$value = $hook->getValue();
			$value[] = CliSeeder::class;
			return $value;
		});

		$application = new \Elgg\Cli\Application();
		$application->add(new DatabaseSeedCommand());
		$application->add(new DatabaseUnseedCommand());

		$application = $application;

		$command = $application->find('database:seed');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--limit' => '2',
		]);

		$command = $application->find('database:unseed');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
		]);

		$seeder = preg_quote(CliSeeder::class);
		$this->assertRegExp("/{$seeder}::seed/im", $commandTester->getDisplay());
		$this->assertRegExp("/{$seeder}::unseed/im", $commandTester->getDisplay());

		$hook->unregister();
	}

}

class CliSeeder extends Seed {

	/**
	 * Populate database
	 * @return mixed
	 */
	function seed() {
		system_message(__METHOD__);
	}

	/**
	 * Removed seeded rows from database
	 * @return mixed
	 */
	function unseed() {
		system_message(__METHOD__);
	}
}