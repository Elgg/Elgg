<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Input\InputOption;

/**
 * elgg-cli cron [--interval] [--quiet]
 */
class CronCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('cron')
			->setDescription('Execute cron handlers for all or specified interval')
			->addOption('interval', 'i', InputOption::VALUE_OPTIONAL,
				'Name of the interval (e.g. hourly)'
			)
			->addOption('force', 'f', InputOption::VALUE_NONE,
				'Force cron commands to run even if they are not yet due'
			)
			->addOption('time', 't', InputOption::VALUE_OPTIONAL,
				'Time of the cron initialization'
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		$intervals = null;
		$interval = $this->option('interval');
		if ($interval) {
			$intervals = [strtolower($interval)];
		}

		$time = $this->option('time');
		if (!$time) {
			$time = 'now';
		}

		$time = new \DateTime($time);

		_elgg_services()->cron->setCurrentTime($time);
		$jobs = _elgg_services()->cron->run($intervals, $this->option('force'));

		if (!$this->option('quiet')) {
			foreach ($jobs as $job) {
				$this->write($job->getOutput());
			}
		}

		return 0;
	}
}
