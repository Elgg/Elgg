<?php

namespace Elgg\Cli;

use Symfony\Component\Console\Input\InputOption;

/**
 * elgg-cli database:seed [--limit]
 */
class DatabaseSeedCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('database:seed')
			->setDescription(elgg_echo('cli:database:seed:description'))
			->addOption('limit', 'l', InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:database:seed:option:limit')
			)
			->addOption('image_folder', null, InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:database:seed:option:image_folder')
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

		_elgg_services()->setValue('mailer', new \Zend\Mail\Transport\InMemory());

		$limit = $this->option('limit') ? : 20;
		$image_folder = $this->option('image_folder');
		if (!empty($image_folder)) {
			elgg_set_config('seeder_local_image_folder', $image_folder);
		}
		
		try {
			_elgg_services()->seeder->seed($limit);
		} catch (\Exception $e) {
			elgg_log($e->getMessage(), 'ERROR');

			return $e->getCode() ? : 3;
		}

		return 0;
	}
}
