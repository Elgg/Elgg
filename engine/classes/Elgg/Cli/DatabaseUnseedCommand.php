<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Input\InputOption;

/**
 * elgg-cli database:unseed
 */
class DatabaseUnseedCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$seeders = _elgg_services()->seeder->getSeederClasses();
		$types = [];
		foreach ($seeders as $seed) {
			$types[] = $seed::getType();
		}
		
		$this->setName('database:unseed')
			->setDescription(elgg_echo('cli:database:unseed:description'))
			->addOption('type', 't', InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:database:seed:option:type', [implode('|', $types)])
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		if (!class_exists('\Faker\Generator')) {
			elgg_log(elgg_echo('cli:database:seed:log:error:faker'), 'ERROR');
			return 1;
		}

		set_time_limit(0);

		_elgg_services()->setValue('mailer', new \Laminas\Mail\Transport\InMemory());

		$options = [
			'type' => $this->option('type'),
		];
		
		try {
			_elgg_services()->seeder->unseed($options);
		} catch (\Exception $e) {
			elgg_log($e->getMessage(), 'ERROR');
			return $e->getCode() ? : 3;
		}

		return 0;
	}
}
