<?php

namespace Elgg\Cli;

use Elgg\Helpers\Cli\CliSeeder;
use Elgg\TestableEvent;
use Elgg\UnitTestCase;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Tester\CommandTester;

class DatabaseSeedCommandUnitTest extends UnitTestCase {

	/**
	 * @var string previous log level
	 */
	protected $loglevel;
	
	/**
	 * @var OutputInterface
	 */
	protected $backup_cli_output;
	
	/**
	 * @var TestableEvent
	 */
	protected $event;
	
	public function up() {
		// Need to adjust loglevel to make sure system messages go to screen output and not to logger
		$app = \Elgg\Application::getInstance();
		$this->loglevel = $app->internal_services->logger->getLevel(false);
		$app->internal_services->logger->setLevel(LogLevel::ERROR);
		
		$this->backup_cli_output = $app->internal_services->get('cli_output');
		
		$cli_output = new NullOutput();
		$cli_output->setVerbosity(OutputInterface::VERBOSITY_VERBOSE);
		$app->internal_services->set('cli_output', $cli_output);
	}

	public function down() {
		// restore loglevel
		$app = \Elgg\Application::getInstance();
		$app->internal_services->logger->setLevel($this->loglevel);
		$app->internal_services->set('cli_output', $this->backup_cli_output);
		
		if ($this->event instanceof TestableEvent) {
			$this->event->unregister();
		}
	}

	public function testSeedCommand() {
		$this->event = $this->registerTestingEvent('seeds', 'database', function(\Elgg\Event $event) {
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
	}
	
	public function testUnseedCommand() {
		$this->event = $this->registerTestingEvent('seeds', 'database', function(\Elgg\Event $event) {
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
	}
	
	public function testSeedersCommand() {
		$this->event = $this->registerTestingEvent('seeds', 'database', function(\Elgg\Event $event) {
			$value = $event->getValue();
			$value[] = CliSeeder::class;
			return $value;
		});
		
		$application = new Application();
		$application->add(new DatabaseSeedersCommand());
		
		$command = $application->find('database:seeders');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
		]);
		
		// output should contain the seeder classname and the type of what is seeded
		$seeder = preg_quote(CliSeeder::class);
		$type = preg_quote(CliSeeder::getType());
		
		$this->assertMatchesRegularExpression("/{$seeder}/im", $commandTester->getDisplay());
		$this->assertMatchesRegularExpression("/{$type}/im", $commandTester->getDisplay());
	}
}
