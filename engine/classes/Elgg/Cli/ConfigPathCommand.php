<?php

namespace Elgg\Cli;

use RuntimeException;
use Symfony\Component\Console\Input\InputArgument;

/**
 * config:path CLI command
 */
class ConfigPathCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('config:path')
				->setDescription('Display or change root path')
				->addArgument('path', InputArgument::OPTIONAL, 'New root path');
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

			if (elgg_save_config('path', $path)) {
				system_message("Root path has been changed");
			} else {
				system_message("Root path could not be changed");
			}
		}
		
		system_message("Current root path: " . elgg_get_config('path'));
	}

}
