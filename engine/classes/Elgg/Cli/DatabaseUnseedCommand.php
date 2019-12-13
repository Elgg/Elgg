<?php

namespace Elgg\Cli;

/**
 * elgg-cli database:unseed
 */
class DatabaseUnseedCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('database:unseed')
			->setDescription(elgg_echo('cli:database:unseed:description'));
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

		_elgg_services()->setValue('mailer', new \Zend\Mail\Transport\InMemory());

		try {
			_elgg_services()->seeder->unseed();
		} catch (\Exception $e) {
			elgg_log($e->getMessage(), 'ERROR');
			return $e->getCode() ? : 3;
		}

		return 0;
	}
}
