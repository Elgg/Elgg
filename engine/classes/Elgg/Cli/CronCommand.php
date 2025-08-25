<?php

namespace Elgg\Cli;

use Elgg\Exceptions\CronException;
use Psr\Log\LogLevel;
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
			->setDescription(elgg_echo('cli:cron:description'))
			->addOption('interval', 'i', InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:cron:option:interval')
			)
			->addOption('force', 'f', InputOption::VALUE_NONE,
				elgg_echo('cli:cron:option:force')
			)
			->addOption('time', 't', InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:cron:option:time')
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {
		$intervals = null;
		$interval = $this->option('interval');
		if ($interval) {
			$intervals = [elgg_strtolower($interval)];
		}

		$time = $this->option('time');
		if (!$time) {
			$time = 'now';
		}

		$time = new \DateTime($time);
		_elgg_services()->cron->setCurrentTime($time);
		
		try {
			_elgg_services()->cron->run($intervals, $this->option('force'));
		} catch (CronException $e) {
			elgg_log($e->getMessage(), LogLevel::ERROR);
			return self::FAILURE;
		}

		return self::SUCCESS;
	}
}
