<?php

namespace Elgg\Cli;

/**
 * elgg-cli seed
 */
class SeedCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('seed')
			->setDescription('Seeds the database with fake entities');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		if (!class_exists('\Faker\Generator')) {
			elgg_log('This is a developer tool currently intended for testing purposes only. Please refrain from using it.', 'ERROR');
			return 1;
		}

		elgg_set_config('debug', 'NOTICE');

		set_time_limit(0);

		if (elgg_is_logged_in()) {
			elgg_log("Seeds should not be run with a logged in user", 'ERROR');
			return 2;
		}

		_elgg_services()->setValue('mailer', new \Zend\Mail\Transport\InMemory());

		_elgg_services()->seeder->seed();
	}

}