<?php

namespace Elgg\Cli;

use ElggPlugin;
use RuntimeException;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;

/**
 * plugins:deactivate CLI command
 */
class PluginsDeactivateCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('plugins:deactivate')
				->setDescription('Deactivate plugins')
				->addOption('all', null, InputOption::VALUE_NONE, 'If set, will deactivate all active plugins');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function handle() {

		$plugins = elgg_get_plugins('active');

		if (empty($plugins)) {
			system_message('All plugins are inactive');
			return;
		}

		$ids = array_map(function(ElggPlugin $plugin) {
			return $plugin->getID();
		}, $plugins);
		$ids = array_values($ids);

		if ($this->option('all')) {
			$deactivate_ids = $ids;
		} else {
			$helper = $this->getHelper('question');
			$question = new ChoiceQuestion('Please select plugins you would like to deactivate (comma-separated list of indexes)', $ids);
			$question->setMultiselect(true);

			$deactivate_ids = $helper->ask($this->input, $this->output, $question);
		}

		if (empty($deactivate_ids)) {
			throw new RuntimeException('You must select at least one plugin');
		}

		$plugins = [];
		foreach ($deactivate_ids as $plugin_id) {
			$plugins[] = elgg_get_plugin_from_id($plugin_id);
		}

		do {
			foreach ($plugins as $key => $plugin) {
				if (!$plugin->isActive()) {
					unset($plugins[$key]);
					continue;
				}

				if (!$plugin->deactivate()) {
					$msg = $plugin->getError();
					$string = ($msg) ? 'admin:plugins:deactivate:no_with_msg' : 'admin:plugins:deactivate:no';
					register_error(elgg_echo($string, array($plugin->getFriendlyName(), $plugin->getError())));
				} else {
					system_message("Plugin {$plugin->getFriendlyName()} has been deactivated");
				}
			}
		} while (count($plugins) > 0);

		elgg_flush_caches();
	}

}
