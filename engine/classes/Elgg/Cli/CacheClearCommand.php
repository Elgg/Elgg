<?php

namespace Elgg\Cli;

/**
 * elgg-cli cache:clear
 */
class CacheClearCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('cache:clear')
			->setDescription(elgg_echo('cli:cache:clear:description'));
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		elgg_clear_caches();
		
		if (!$this->option('quiet')) {
			$this->write(elgg_echo('admin:cache:cleared'));
		}

		return 0;
	}
}
