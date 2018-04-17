<?php

namespace Elgg\Cli;

use Elgg\Hook;
use Elgg\IntegrationTestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @group Cli
 */
class SimpleTestCommandTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testExecute() {

		$hook = $this->registerTestingHook('unit_test', 'system', function (Hook $hook) {
			return [
				CliSimpletest::class,
			];
		});

		$application = new Application();
		$application->add(new SimpletestCommand());

		$command = $application->find('simpletest');
		$commandTester = new CommandTester($command);
		$commandTester->execute([
			'command' => $command->getName(),
			'--quiet' => true,
		]);

		$class = preg_quote(CliSimpletest::class);
		$this->assertRegExp("/{$class}::testMe/im", $commandTester->getDisplay());

		$hook->unregister();
	}

}

class CliSimpletest extends \ElggCoreUnitTest {

	public function up() {

	}

	public function down() {

	}

	public function testMe() {
		system_message(__METHOD__);
	}
}