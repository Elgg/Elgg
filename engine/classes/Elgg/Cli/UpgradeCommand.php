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
			->setDescription(elgg_echo('cli:upgrade:description'))
			->addOption('force', 'f', InputOption::VALUE_NONE,
				elgg_echo('cli:upgrade:option:force')
			)
			->addArgument('async', InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:upgrade:argument:async')
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output) {
		$this->input = $input;
		$this->output = $output;

		$async = (bool) $this->argument('async');
		$force = $this->option('force');

		$return = 0;

		// run Phinx (database) migrations
		ElggApplication::migrate();

		$app = ElggApplication::getInstance();
		$initial_app = clone $app;

		$boot = new BootHandler($app);
		$boot->bootServices();

		// check if upgrade is locked
		$is_locked = _elgg_services()->mutex->isLocked('upgrade');
		if ($is_locked && !$force) {
			$this->error(elgg_echo('upgrade:locked'));
			
			return 1;
		} elseif ($is_locked && $force) {
			_elgg_services()->mutex->unlock('upgrade');
			
			$this->notice(elgg_echo('upgrade:unlock:success'));
		}

		// run system upgrades
		$upgrades = _elgg_services()->upgrades->getPendingUpgrades(false);
		$job = _elgg_services()->upgrades->run($upgrades);

		$job->done(
			function () {
				$this->notice(elgg_echo('cli:upgrade:system:upgraded'));
			},
			function ($errors) use (&$return) {
				$this->error(elgg_echo('cli:upgrade:system:failed'));

				if (!is_array($errors)) {
					$errors = [$errors];
				}

				foreach ($errors as $error) {
					$this->error($error);
				}
				$return = 1;
			}
		);

		if ($return !== 0 || !$async) {
			return $return;
		}

		// We want to reboot the application, because some of the services (e.g. dic) can bootstrap themselves again
		$app = $initial_app;
		ElggApplication::setInstance($initial_app);
		$app->start();

		// run async upgrades
		$upgrades = _elgg_services()->upgrades->getPendingUpgrades($async);
		$job = _elgg_services()->upgrades->run($upgrades);

		$job->done(
			function () {
				$this->notice(elgg_echo('cli:upgrade:async:upgraded'));
			},
			function ($errors) use (&$return) {
				$this->error(elgg_echo('cli:upgrade:aysnc:failed'));

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
