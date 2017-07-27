<?php

namespace Elgg\Cli;

/**
 * site:flush_cache CLI command
 */
class SiteFlushCacheCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('site:flush_cache')
				->setDescription('Flush site caches');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function handle() {
		elgg_flush_caches();
		_elgg_services()->autoloadManager->deleteCache();
		system_message(elgg_echo('admin:cache:flushed'));
	}

}
