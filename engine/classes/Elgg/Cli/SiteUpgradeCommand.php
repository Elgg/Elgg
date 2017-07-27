<?php

namespace Elgg\Cli;

/**
 * site:upgrade CLI command
 */
class SiteUpgradeCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('site:upgrade')
				->setDescription('Run upgrades');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function handle() {
		$upgrader = _elgg_services()->upgrades;
		$result = $upgrader->run();
		if ($result['failure'] == true) {
			register_error($result['reason']);
		} else {
			system_message('Upgrade script ran without failures');
		}
	}

}
