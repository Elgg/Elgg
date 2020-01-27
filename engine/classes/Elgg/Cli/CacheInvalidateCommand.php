<?php

namespace Elgg\Cli;

/**
 * elgg-cli cache:invalidate
 */
class CacheInvalidateCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('cache:invalidate')
			->setDescription(elgg_echo('cli:cache:invalidate:description'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		elgg_invalidate_caches();
		
		if (!$this->option('quiet')) {
			$this->write(elgg_echo('admin:cache:invalidated'));
		}

		return 0;
	}
}
