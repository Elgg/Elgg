<?php

namespace Elgg;

use Elgg\Di\PublicContainer;

/**
 * Plugin bootstrap
 */
abstract class PluginBootstrap implements PluginBootstrapInterface {

	/**
	 * Constructor
	 *
	 * @param \ElggPlugin     $plugin The plugin
	 * @param PublicContainer $dic    Public services
	 */
	public function __construct(protected \ElggPlugin $plugin, protected PublicContainer $dic) {
	}

	/**
	 * {@inheritdoc}
	 */
	public function elgg() {
		return $this->dic;
	}

	/**
	 * {@inheritdoc}
	 */
	public function plugin() {
		return $this->plugin;
	}
}
