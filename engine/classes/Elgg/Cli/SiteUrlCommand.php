<?php

namespace Elgg\Cli;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;

/**
 * site:url CLI command
 */
class SiteUrlCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('site:url')
				->setDescription('Display or change site url')
				->addArgument('url', InputArgument::OPTIONAL, 'New site url');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function handle() {

		$url = $this->argument('url');

		$site = elgg_get_site_entity();
		if ($url) {
			if (!filter_var($url, FILTER_VALIDATE_URL)) {
				throw new RuntimeException("$url is not a valid URL");
			}

			// make sure the URL ends with a slash
			$url = rtrim($url, '/');
			$url .= '/';

			$ia = elgg_set_ignore_access(true);
			$site->url = $url;
			if ($site->save()) {
				system_message("Site URL has been changed");
			} else {
				system_message("Site URL could not be changed");
			}
			elgg_set_ignore_access($ia);
		}

		system_message("Current site URL: $site->url");
	}

}
