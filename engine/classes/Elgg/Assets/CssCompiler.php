<?php

namespace Elgg\Assets;

use Elgg\EventsService;
use Elgg\Includer;
use Elgg\Project\Paths;

/**
 * Gives access to CSS variables in the system
 *
 * @internal
 */
class CssCompiler {

	/**
	 * Constructor
	 *
	 * @param EventsService $events Events service
	 */
	public function __construct(protected EventsService $events) {
	}
	
	/**
	 * Fetches a combined set of CSS variables and their value
	 *
	 * @param array $compiler_options compiler arguments with potential custom variables
	 * @param bool  $load_config_vars (internal) determines if config values are being loaded
	 *
	 * @return array
	 */
	public function getCssVars(array $compiler_options = [], bool $load_config_vars = true): array {
		$default_vars = array_merge($this->getCoreVars(), $this->getPluginVars());
		$custom_vars = (array) elgg_extract('vars', $compiler_options, []);
		$vars = array_merge($default_vars, $custom_vars);
		
		$results = (array) $this->events->triggerResults('vars:compiler', 'css', $compiler_options, $vars);
		
		if (!$load_config_vars) {
			return $results;
		}
		
		return array_merge($results, (array) elgg_get_config('custom_theme_vars', []));
	}

	/**
	 * Default Elgg theme variables
	 *
	 * @return array
	 */
	protected function getCoreVars(): array {
		$file = Paths::elgg() . 'engine/theme.php';
		return Includer::includeFile($file);
	}

	/**
	 * Plugin theme variables
	 *
	 * @return array
	 */
	protected function getPluginVars(): array {
		$return = [];
		
		$plugins = elgg_get_plugins('active');
		foreach ($plugins as $plugin) {
			$plugin_vars = $plugin->getStaticConfig('theme', []);
			if (empty($plugin_vars)) {
				continue;
			}
			
			$return = array_merge($return, $plugin_vars);
		}
		
		return $return;
	}
}
