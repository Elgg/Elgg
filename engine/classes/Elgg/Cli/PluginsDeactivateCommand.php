<?php

namespace Elgg\Cli;

use Psr\Log\LogLevel;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

/**
 * elgg-cli plugins:deactivate
 */
class PluginsDeactivateCommand extends Command {

	use PluginsHelper;

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('plugins:deactivate')
			->setDescription('Deactivate plugin(s)')
			->addOption('force', 'f', InputOption::VALUE_NONE,
				'Force deactivation of all dependent plugins'
			)
			->addArgument('plugins', InputArgument::REQUIRED | InputArgument::IS_ARRAY,
				'Plugin IDs to be deactivated'
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		$ids = (array) $this->argument('plugins');
		$force = (bool) $this->option('force');

		$helper = _elgg_services()->cli_progress;

		$progress = $helper->start('Deactivating plugins', count($ids));

		foreach ($ids as $id) {
			try {
				$this->deactivate($id, $force);
			} catch (\Exception $ex) {
				elgg_log($ex, LogLevel::ERROR);
			}

			$progress->advance();
		}

		$helper->finish($progress);

		return 0;
	}
}
