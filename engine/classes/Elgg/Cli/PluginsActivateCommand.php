<?php

namespace Elgg\Cli;

use ElggPlugin;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Input\InputArgument;

/**
 * plugins:activate CLI command
 */
class PluginsActivateCommand extends Command {

	/**
	 * {@inheritdoc}
	 */
	protected function configure() {
		$this->setName('plugins:activate')
				->setDescription('Activate plugins')
				->addArgument('list', InputArgument::OPTIONAL, 'List of comma separated plugins to activate')
				->addOption('all', null, InputOption::VALUE_NONE, 'If set, will activate all inactive plugins');
	}

	/**
	 * {@inheritdoc}
	 */
	protected function handle() {

		$plugins = elgg_get_plugins('inactive');
		$list = $this->argument('list');

		if (empty($plugins)) {
			system_message('All plugins are active');
			return;
		}

		$ids = array_map(function(ElggPlugin $plugin) {
			return $plugin->getID();
		}, $plugins);
		$ids = array_values($ids);

		if ($this->option('all')) {
			$activate_ids = $ids;
		} else {
			if ($list) {
				$activate_ids = explode(',', $list);
			} else {
				$helper = $this->getHelper('question');
				$question = new ChoiceQuestion('Please select plugins you would like to activate (comma-separated list of indexes)', $ids);
				$question->setMultiselect(true);
				$activate_ids = $helper->ask($this->input, $this->output, $question);
			}
		}

		if (empty($activate_ids)) {
			throw new \RuntimeException('You must select at least one plugin');
		}


		$plugins = [];
		foreach ($activate_ids as $plugin_id) {
			$plugins[] = elgg_get_plugin_from_id($plugin_id);
		}

		do {
			$additional_plugins_activated = false;
			foreach ($plugins as $key => $plugin) {
				if ($plugin->isActive()) {
					unset($plugins[$key]);
					continue;
				}

				if (!$plugin->activate()) {
					// plugin could not be activated in this loop, maybe in the next loop
					continue;
				}

				$ids = array(
					'cannot_start' . $plugin->getID(),
					'invalid_and_deactivated_' . $plugin->getID()
				);

				foreach ($ids as $id) {
					elgg_delete_admin_notice($id);
				}

				// mark that something has changed in this loop
				$additional_plugins_activated = true;
				unset($plugins[$key]);

				system_message("Plugin {$plugin->getFriendlyName()} has been activated");
			}

			if (!$additional_plugins_activated) {
				// no updates in this pass, break the loop
				break;
			}
		} while (count($plugins) > 0);

		if (count($plugins) > 0) {
			foreach ($plugins as $plugin) {
				$msg = $plugin->getError();
				$string = ($msg) ? 'admin:plugins:activate:no_with_msg' : 'admin:plugins:activate:no';
				register_error(elgg_echo($string, array($plugin->getFriendlyName())));
			}
		}

		elgg_flush_caches();
	}

}
