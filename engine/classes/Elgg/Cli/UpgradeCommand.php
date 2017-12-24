<?php

namespace Elgg\Cli;
use Elgg\Application;

/**
 * elgg-cli upgrade
 */
class UpgradeCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('upgrade')
			->setDescription('Run upgrade scripts');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {
		Application::upgrade();
		return 1;
	}
}
