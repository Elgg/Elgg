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
	
	protected ?array $css_variables = null;

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
	 * @param bool $load_config_vars determines if config values are being loaded
	 *
	 * @return array
	 */
	public function getCssVars(bool $load_config_vars = true): array {
		if (!isset($this->css_variables)) {
			$this->css_variables = Includer::includeFile(Paths::elgg() . 'engine/theme.php');
			$this->loadPluginVars();
		}

		$results = $this->css_variables;
		
		if (!$load_config_vars) {
			return $results;
		}
		
		$custom_vars = (array) elgg_get_config('custom_theme_vars', []);
		if (empty($custom_vars)) {
			return $results;
		}
		
		$first_element = $custom_vars[array_key_first($custom_vars)];
		if (!is_array($first_element)) {
			// assume site config only has default color scheme variables (pre 7.0)
			$custom_vars = ['default' => $custom_vars];
		}

		foreach ($custom_vars as $color_scheme => $variables) {
			$results[$color_scheme] = array_merge(elgg_extract($color_scheme, $results), $variables);
		}
		
		return $results;
	}

	/**
	 * Loads the plugin theme variables
	 *
	 * @return void
	 */
	protected function loadPluginVars(): void {
		$plugins = elgg_get_plugins('active');
		foreach ($plugins as $plugin) {
			$plugin_vars = $plugin->getStaticConfig('theme', []);
			if (empty($plugin_vars)) {
				continue;
			}
			
			$first_item = $plugin_vars[array_key_first($plugin_vars)];
			if (!is_array($first_item)) {
				// assume plugin config only has default color scheme variables
				$plugin_vars = ['default' => $plugin_vars];
			}
			
			foreach ($plugin_vars as $color_scheme => $variables) {
				$merged_variables = array_merge(elgg_extract($color_scheme, $this->css_variables), $variables);
				$this->css_variables[$color_scheme] = $merged_variables;
			}
		}
	}
}
