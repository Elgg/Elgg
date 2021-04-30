<?php

namespace Elgg\Cli;

use Elgg\Traits\Cli\PluginsHelper;
use Psr\Log\LogLevel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * elgg-cli plugins:activate
 */
class PluginsActivateCommand extends Command {

	use PluginsHelper;

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('plugins:activate')
			->setDescription(elgg_echo('cli:plugins:activate:description'))
			->addOption('force', 'f', InputOption::VALUE_NONE,
				elgg_echo('cli:plugins:activate:option:force')
			)
			->addArgument('plugins', InputArgument::REQUIRED | InputArgument::IS_ARRAY,
				elgg_echo('cli:plugins:activate:argument:plugins')
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		$ids = (array) $this->argument('plugins');
		$force = (bool) $this->option('force');

		$helper = _elgg_services()->cli_progress;

		$progress = $helper->start(elgg_echo('cli:plugins:activate:progress:start'), count($ids));

		foreach ($ids as $id) {
			try {
				$this->activate($id, $force);
			} catch (\Exception $ex) {
				elgg_log($ex, LogLevel::ERROR);
			}

			$progress->advance();
		}

		$helper->finish($progress);

		return 0;
	}
}
