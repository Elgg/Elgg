<?php

namespace Elgg\Cli;

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

		$results = _elgg_services()->upgrades->run($async);

		$success = true;

		foreach ($results as $id => $result) {
			/* @var $result \Elgg\Upgrade\Result */

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