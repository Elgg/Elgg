<?php

namespace Elgg\Cli;

/**
 * elgg-cli cache:purge
 */
class CachePurgeCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('cache:purge')
			->setDescription(elgg_echo('cli:cache:purge:description'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		elgg_purge_caches();
		
		if (!$this->option('quiet')) {
			$this->write(elgg_echo('admin:cache:purged'));
		}

		return 0;
	}
}
