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
			->setDescription('Flush all caches (deprecated: use "elgg-cli cache:clear")');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {
		elgg_deprecated_notice('"elgg-cli flush" has been deprecated, use "elgg-cli cache:clear"', '3.3');
		
		elgg_flush_caches();

		if (!$this->option('quiet')) {
			$this->write('System caches have been flushed');
		}

		return 0;
	}
}
