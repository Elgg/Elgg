<?php

namespace Elgg\Cli;

/**
 * elgg-cli flush
 */
class FlushCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('flush')
			->setDescription('Flush all caches');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		elgg_flush_caches();

		$this->write('System caches have been flushed');

		return 0;
	}
}
