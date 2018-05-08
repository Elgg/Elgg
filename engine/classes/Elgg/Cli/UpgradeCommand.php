<?php

namespace Elgg\Cli;

use Elgg\Application;
use Elgg\Upgrade\Result;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputOption;

/**
 * elgg-cli upgrade [async]
 */
class UpgradeCommand extends Command {

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
	protected function command() {

		$async = in_array('async', $this->argument('async'));

		if (_elgg_services()->mutex->isLocked('upgrade')) {
			_elgg_services()->mutex->unlock('upgrade');
		}

		$results = _elgg_services()->upgrades->run($async, $this->output);

		$success = true;

		foreach ($results as $id => $result) {
			/* @var $result Result */

			if ($errors = $result->getErrors()) {
				$success = false;
			}
		}

		if ($success) {
			$this->notice('Your system has been upgraded');

			return 0;
		} else {
			$this->error('System upgrade failed');

			return 1;
		}
	}

}