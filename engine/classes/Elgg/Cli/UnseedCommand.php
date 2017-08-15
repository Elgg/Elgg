<?php

namespace Elgg\Cli;

/**
 * elgg-cli unseed
 */
class UnseedCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('unseed')
			->setDescription('Removes seeded fake entities from the database');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		if (!class_exists('\Faker\Generator')) {
			elgg_log('This is a developer tool currently intended for testing purposes only. Please refrain from using it.', 'ERROR');
			return 1;
		}

		set_time_limit(0);

		_elgg_services()->setValue('mailer', new \Zend\Mail\Transport\InMemory());

		_elgg_services()->seeder->unseed();
	}

}