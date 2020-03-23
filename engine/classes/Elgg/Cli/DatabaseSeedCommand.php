<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * elgg-cli database:seed [--limit]
 */
class DatabaseSeedCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$seeders = _elgg_services()->seeder->getSeederClasses();
		$types = [];
		foreach ($seeders as $seed) {
			$types[] = $seed::getType();
		}
		
		$this->setName('database:seed')
			->setDescription(elgg_echo('cli:database:seed:description'))
			->addOption('limit', 'l', InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:database:seed:option:limit')
			)
			->addOption('image_folder', null, InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:database:seed:option:image_folder')
			)
			->addOption('type', 't', InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:database:seed:option:type', [implode('|', $types)])
			)
			->addOption('create_since', null, InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:database:seed:option:create_since'), 'now'
			)
			->addOption('create_until', null, InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:database:seed:option:create_until'), 'now'
			)
			->addArgument('create', InputArgument::OPTIONAL,
				elgg_echo('cli:database:seed:argument:create')
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

		if (elgg_is_logged_in()) {
			elgg_log(elgg_echo('cli:database:seed:log:error:logged_in'), 'ERROR');

			return 2;
		}

		_elgg_services()->setValue('mailer', new \Laminas\Mail\Transport\InMemory());

		$options = [
			'limit' => (int) $this->option('limit') ? : 20,
			'image_folder' => $this->option('image_folder'),
			'type' => $this->option('type'),
			'create_since' => $this->option('create_since'),
			'create_until' => $this->option('create_until'),
			'create' => (bool) $this->argument('create'),
		];
		
		try {
			_elgg_services()->seeder->seed($options);
		} catch (\Exception $e) {
			elgg_log($e->getMessage(), 'ERROR');

			return $e->getCode() ? : 3;
		}

		return 0;
	}
}
