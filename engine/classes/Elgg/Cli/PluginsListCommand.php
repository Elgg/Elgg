<?php

namespace Elgg\Cli;

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
			->setDescription('List all plugins installed on the site')
			->addOption('status', 's', InputOption::VALUE_OPTIONAL,
				'Plugin status ( all | active | inactive )'
			);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function command() {

		$status = $this->option('status') ? : 'all';
		if (!in_array($status, ['all', 'active', 'inactive'])) {
			$this->error("$status is not a valid status. Use 'all', 'active' or 'inactive'");
			return 1;
		}

		$table = new Table($this->output);
		$table->setHeaders(['GUID', 'ID', 'Version', 'Status', 'Priority']);

		try {
			$plugins = elgg_get_plugins($status);

			foreach ($plugins as $plugin) {
				$manifest = $plugin->getManifest();

				$table->addRow([
					$plugin->guid,
					$plugin->getID(),
					$manifest ? $manifest->getVersion() : 'INVALID PACKAGE',
					$plugin->isActive() ? 'active' : 'inactive',
					$plugin->getPriority(),
				]);
			}

			$table->render();
		} catch (\PluginException $ex) {
			return 2;
		}

		return 0;
	}
}
