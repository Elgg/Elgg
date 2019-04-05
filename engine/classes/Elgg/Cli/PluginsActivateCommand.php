<?php

namespace Elgg\Cli;

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
			->setDescription('Activate plugin(s)')
			->addOption('force', 'f', InputOption::VALUE_NONE,
				'Resolve conflicts by deactivating conflicting plugins and enabling required ones'
			)
			->addArgument('plugins', InputArgument::REQUIRED | InputArgument::IS_ARRAY,
				'Plugin IDs to be activated'
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		$ids = (array) $this->argument('plugins');
		$force = (bool) $this->option('force');

		$helper = _elgg_services()->cli_progress;

		$progress = $helper->start('Activating plugins', count($ids));

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
