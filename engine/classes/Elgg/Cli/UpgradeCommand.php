<?php

namespace Elgg\Cli;

use Elgg\Application;
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

		Application::migrate();
		$app = Application::start();

		$app->_services->upgrades->run($async);

		system_message('Your system has been upgraded');

		return 0;
	}

}