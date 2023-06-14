<?php

namespace Elgg\Cli;

use Elgg\Helpers\Cli\CliSeeder;
use Elgg\Logger;
use Elgg\UnitTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class DatabaseSeedCommandUnitTest extends UnitTestCase {

	/**
	 * @var int previous log level
	 */
	protected $loglevel;
	
	public function up() {
		// Need to adjust loglevel to make sure system messages go to screen output and not to logger
		$app = \Elgg\Application::getInstance();
		$this->loglevel = $app->internal_services->logger->getLevel();
		$app->internal_services->logger->setLevel(Logger::ERROR);
	}

	public function down() {
		// restore loglevel
		$app = \Elgg\Application::getInstance();
		$app->internal_services->logger->setLevel($this->loglevel);
	}

	public function testSeedCommand() {
		$event = $this->registerTestingEvent('seeds', 'database', function(\Elgg\Event $event) {
			$value = $event->getValue();
			$value[] = CliSeeder::class;
			return $value;
		});

		$application = new Application();
		$application->add(new DatabaseSeedCommand());

		$command = $application->find('database:seed');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--limit' => '2',
		]);

		$seeder = preg_quote(CliSeeder::class);
		$this->assertMatchesRegularExpression("/{$seeder}::seed/im", $commandTester->getDisplay());

		$event->unregister();
	}
	
	public function testUnseedCommand() {
		$event = $this->registerTestingEvent('seeds', 'database', function(\Elgg\Event $event) {
			$value = $event->getValue();
			$value[] = CliSeeder::class;
			return $value;
		});

		$application = new Application();
		$application->add(new DatabaseUnseedCommand());

		$command = $application->find('database:unseed');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
		]);

		$seeder = preg_quote(CliSeeder::class);
		$this->assertMatchesRegularExpression("/{$seeder}::unseed/im", $commandTester->getDisplay());

		$event->unregister();
	}
}
