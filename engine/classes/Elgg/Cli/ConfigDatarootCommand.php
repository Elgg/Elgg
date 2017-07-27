<?php

namespace Elgg\Cli;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;

/**
 * config:dataroot CLI command
 */
class ConfigDatarootCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('config:dataroot')
				->setDescription('Display or change data directory path')
				->addArgument('path', InputArgument::OPTIONAL, 'New data directory path');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function handle() {

		$path = $this->argument('path');

		if ($path) {
			// make sure the path ends with a slash
			$path = rtrim($path, DIRECTORY_SEPARATOR);
			$path .= DIRECTORY_SEPARATOR;

			if (!is_dir($path)) {
				throw new RuntimeException("$path is not a valid directory");
			}

			if (elgg_save_config('dataroot', $path)) {
				system_message("Data directory path has been changed");
			} else {
				system_message("Data directory path could not be changed");
			}
		}

		system_message("Current data directory path: " . elgg_get_config('dataroot'));
	}

}
