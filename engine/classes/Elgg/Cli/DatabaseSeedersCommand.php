<?php

namespace Elgg\Cli;

use Elgg\Database\Seeds\Seed;
use Symfony\Component\Console\Helper\Table;

/**
 * elgg-cli database:seeders
 */
class DatabaseSeedersCommand extends Command {
	
	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('database:seeders')
			 ->setDescription(elgg_echo('cli:database:seeders:description'));
	}
	
	/**
	 * {@inheritDoc}
	 */
	protected function command() {
		$table = new Table($this->output);
		
		$table->setHeaders([
			elgg_echo('cli:database:seeders:handler'),
			elgg_echo('cli:database:seeders:type'),
			elgg_echo('cli:database:seeders:count'),
		]);
		
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() use ($table) {
			$seeders = _elgg_services()->seeder->getSeederClasses();
			
			foreach ($seeders as $seeder) {
				/* @var $seed Seed */
				$seed = new $seeder();
				
				$table->addRow([
					$seeder,
					$seed::getType(),
					$seed->getCount(),
				]);
			}
		});
		
		$table->render();
		
		return self::SUCCESS;
	}
}
