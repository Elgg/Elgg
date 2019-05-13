<?php

namespace Elgg\Cli;

use Elgg\Application as ElggApplication;
use Elgg\Application\BootHandler;
use function React\Promise\all;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * elgg-cli upgrade [async]
 */
class UpgradeCommand extends BaseCommand {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('upgrade')
			->addArgument('async', InputOption::VALUE_OPTIONAL,
				'Execute pending asynchronous upgrades'
			)
			->setDescription('Run system upgrade');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$this->input = $input;
		$this->output = $output;

		$async = (bool) $this->argument('async');

		$return = 0;

		ElggApplication::migrate();

		$app = ElggApplication::getInstance();
		$initial_app = clone $app;

		$boot = new BootHandler($app);
		$boot->bootServices();

		$upgrades = _elgg_services()->upgrades->getPendingUpgrades(false);
		$job = _elgg_services()->upgrades->run($upgrades);

		$job->done(
			function () {
				$this->notice('System has been upgraded');
			},
			function ($errors) use (&$return) {
				$this->error('System upgrade has failed');

				if (!is_array($errors)) {
					$errors = [$errors];
				}

				foreach ($errors as $error) {
					$this->error($error);
				}
				$return = 1;
			}
		);

		// We want to reboot the application, because some of the services (e.g. dic) can bootstrap themselves again
		$app = $initial_app;
		ElggApplication::setInstance($initial_app);
		$app->start();

		$upgrades = _elgg_services()->upgrades->getPendingUpgrades($async);
		$job = _elgg_services()->upgrades->run($upgrades);

		$job->done(
			function () {
				$this->notice('Plugins have been upgraded');
			},
			function ($errors) use (&$return) {
				$this->error('Plugin upgrade has failed');

				if (!is_array($errors)) {
					$errors = [$errors];
				}

				foreach ($errors as $error) {
					$this->error($error);
				}
				$return = 1;
			}
		);

		return $return;
	}
}
