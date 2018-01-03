<?php

namespace Elgg\Assets;

use Elgg\Config;
use Elgg\Includer;
use Elgg\PluginHooksService;
use Elgg\Project\Paths;

/**
 * Compile CSS with CSSCrush
 *
 * @internal
 */
class CssCompiler {

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * Constructor
	 *
	 * @param Config             $config Config
	 * @param PluginHooksService $hooks  Hooks service
	 */
	public function __construct(Config $config, PluginHooksService $hooks) {
		$this->config = $config;
		$this->hooks = $hooks;
	}

	/**
	 * Compile CSS
	 *
	 * @param string $css     CSS string
	 * @param array  $options CssCrush options
	 *
	 * @return string
	 */
	public function compile($css, array $options = []) {
		$defaults = [
			'minify' => false, // minify handled by _elgg_views_minify
			'formatter' => 'single-line', // shows lowest byte size
			'versioning' => false, // versioning done by Elgg
			'rewrite_import_urls' => false,
		];

		$config = (array) $this->config->css_compiler_options;

		$options = array_merge($defaults, $config, $options);

		$default_vars = $this->getDefaultVars();
		$custom_vars = (array) elgg_extract('vars', $options, []);
		$vars = array_merge($default_vars, $custom_vars);

		$options['vars'] = $this->hooks->trigger('vars:compiler', 'css', $options, $vars);

		return csscrush_string($css, $options);
	}

	/**
	 * Default Elgg theme variables
	 * @return array
	 */
	protected function getDefaultVars() {
		$file = Paths::elgg() . 'engine/theme.php';
		return Includer::includeFile($file);
	}
}