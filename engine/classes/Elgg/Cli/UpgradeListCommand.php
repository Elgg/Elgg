<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Helper\Table;

/**
 * elgg-cli upgrade:list
 */
class UpgradeListCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('upgrade:list')
			->setDescription(elgg_echo('cli:upgrade:list:description'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {
		$completed_upgrades = _elgg_services()->upgrades->getCompletedUpgrades();
		if (!empty($completed_upgrades)) {
			$table = new Table($this->output);
		
			$this->write(elgg_echo('cli:upgrade:list:completed'));
		
			foreach ($completed_upgrades as $upgrade) {
				$table->addRow([
					$upgrade->class,
					$upgrade->getDisplayName(),
				]);
			}
			
			$table->render();
		}
		
		$pending_upgrades = _elgg_services()->upgrades->getPendingUpgrades();
		if (!empty($pending_upgrades)) {
			$table = new Table($this->output);
		
			$this->write(elgg_echo('cli:upgrade:list:pending'));
		
			foreach ($pending_upgrades as $upgrade) {
				$table->addRow([
					$upgrade->class,
					$upgrade->getDisplayName(),
				]);
			}
			
			$table->render();
		}
		
		if (empty($completed_upgrades) && empty($pending_upgrades)) {
			$this->write(elgg_echo('cli:upgrade:list:notfound'));
		}

		return 0;
	}
}
