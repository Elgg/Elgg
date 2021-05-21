<?php

namespace Elgg\Di;
use Elgg\Database\Plugins;
use Elgg\Project\Paths;

/**
 * DI definition loader
 *
 * @internal
 */
class DefinitionLoader {

	/**
	 * @var Plugins
	 */
	protected $plugins;

	/**
	 * Constructor
	 *
	 * @param Plugins $plugins Plugins service
	 */
	public function __construct(Plugins $plugins) {
		$this->plugins = $plugins;
	}

	/**
	 * Returns a list of definition files
	 * @return array
	 */
	public function getDefinitions() {
		// add core services
		$sources = [
			Paths::elgg() . 'engine/services.php',
		];

		$plugins = $this->plugins->find('active');

		foreach ($plugins as $plugin) {
			$plugin->autoload(); // make sure all classes are loaded
			$sources[] = $plugin->getPath() . 'elgg-services.php';
		}

		return array_filter($sources, function($source) {
			return is_file($source) && is_readable($source);
		});
	}
}
