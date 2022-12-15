<?php

namespace Elgg\Cli;

use Elgg\Exceptions\PluginException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputOption;

/**
 * elgg-cli plugins:list [--status]
 */
class PluginsListCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('plugins:list')
			->setDescription(elgg_echo('cli:plugins:list:description'))
			->addOption('status', 's', InputOption::VALUE_OPTIONAL,
				elgg_echo('cli:plugins:list:option:status', ['all | active | inactive']),
				'all'
			)
			->addOption('refresh', 'r', InputOption::VALUE_NONE,
				elgg_echo('cli:plugins:list:option:refresh')
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		$status = $this->option('status');
		if (!in_array($status, ['all', 'active', 'inactive'])) {
			$this->error(elgg_echo('cli:plugins:list:error:status', [$status, 'all | active | inactive']));
			return self::FAILURE;
		}

		if ($this->option('refresh') !== false) {
			_elgg_services()->plugins->generateEntities();
		}

		$table = new Table($this->output);
		$table->setHeaders([
			'GUID',
			elgg_echo('admin:plugins:label:id'),
			elgg_echo('admin:plugins:label:version'),
			elgg_echo('status'),
			elgg_echo('admin:plugins:label:priority'),
		]);

		try {
			$plugins = elgg_get_plugins($status);

			foreach ($plugins as $plugin) {
				$table->addRow([
					$plugin->guid,
					$plugin->getID(),
					$plugin->getVersion(),
					$plugin->isActive() ? elgg_echo('status:active') : elgg_echo('status:inactive'),
					$plugin->getPriority(),
				]);
			}

			$table->render();
		} catch (PluginException $ex) {
			return self::INVALID;
		}

		return self::SUCCESS;
	}
}
