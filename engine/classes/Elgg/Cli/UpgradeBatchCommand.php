<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Psr\Log\LogLevel;

/**
 * elgg-cli upgrade:batch
 */
class UpgradeBatchCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('upgrade:batch')
			->setDescription(elgg_echo('cli:upgrade:batch:description'))
			->addOption('force', 'f', InputOption::VALUE_NONE,
				elgg_echo('cli:upgrade:batch:option:force')
			)
			->addArgument('upgrades', InputArgument::REQUIRED | InputArgument::IS_ARRAY,
				elgg_echo('cli:upgrade:batch:argument:upgrades')
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {
		$upgrades = (array) $this->argument('upgrades');
		$force = (bool) $this->option('force');

		foreach ($upgrades as $upgrade_class) {
			$upgrade = _elgg_services()->upgradeLocator->getUpgradeByClass($upgrade_class);
			if (!$upgrade instanceof \ElggUpgrade) {
				$this->log(LogLevel::WARNING, elgg_echo('cli:upgrade:batch:notfound', [$upgrade_class]));
				continue;
			}
			
			if (!$force && $upgrade->isCompleted()) {
				continue;
			}
			
			_elgg_services()->upgrades->executeUpgrade($upgrade, false);
		}
		
		if (!$this->option('quiet')) {
			$this->write(elgg_echo('cli:upgrade:batch:finished'));
		}

		return 0;
	}
}
