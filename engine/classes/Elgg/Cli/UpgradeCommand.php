<?php

namespace Elgg\Cli;

use Elgg\Application as ElggApplication;
use Elgg\Application\BootHandler;
use Psr\Log\LogLevel;
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
	protected function execute(InputInterface $input, OutputInterface $output): int {
		$this->input = $input;
		$this->output = $output;

		$async = (bool) $this->argument('async');
		$force = $this->option('force');

		$return = self::SUCCESS;

		// run Phinx (database) migrations
		ElggApplication::migrate();

		$app = ElggApplication::getInstance();
		$initial_app = clone $app;

		$boot = new BootHandler($app);
		$boot->bootServices();
		$boot->bootPlugins();

		// check if upgrade is locked
		$is_locked = _elgg_services()->mutex->isLocked('upgrade');
		if ($is_locked && !$force) {
			elgg_log(elgg_echo('upgrade:locked'), LogLevel::ERROR);
			
			return self::FAILURE;
		} elseif ($is_locked && $force) {
			_elgg_services()->mutex->unlock('upgrade');
			
			$this->write(elgg_echo('upgrade:unlock:success'));
		}

		// run system upgrades
		$upgrades = _elgg_services()->upgrades->getPendingUpgrades(false);
		$job = _elgg_services()->upgrades->run($upgrades);

		$job->then(
			function () {
				$this->write(elgg_echo('cli:upgrade:system:upgraded'));
			},
			function ($errors) use (&$return) {
				$this->write(elgg_echo('cli:upgrade:system:failed'), 'error');

				if (!is_array($errors)) {
					$errors = [$errors];
				}

				foreach ($errors as $error) {
					$this->write($error, 'error');
				}
				
				$return = self::FAILURE;
			}
		);

		_elgg_services()->plugins->generateEntities();
		
		if ($return !== self::SUCCESS || !$async) {
			return $return;
		}

		// We want to reboot the application, because some of the services (e.g. dic) can bootstrap themselves again
		$app = $initial_app;
		ElggApplication::setInstance($initial_app);
		$app->start();

		// run async upgrades
		$upgrades = _elgg_services()->upgrades->getPendingUpgrades(true);
		$job = _elgg_services()->upgrades->run($upgrades);

		$job->then(
			function () {
				$this->write(elgg_echo('cli:upgrade:async:upgraded'));
			},
			function ($errors) use (&$return) {
				$this->write(elgg_echo('cli:upgrade:aysnc:failed'), 'error');

				if (!is_array($errors)) {
					$errors = [$errors];
				}

				foreach ($errors as $error) {
					$this->write($error, 'error');
				}
				
				$return = self::FAILURE;
			}
		);

		return $return;
	}
}
