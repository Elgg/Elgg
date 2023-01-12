<?php

namespace Elgg;

use Elgg\Di\PublicContainer;

/**
 * Plugin bootstrap
 */
abstract class PluginBootstrap implements PluginBootstrapInterface {

	/**
	 * @var \ElggPlugin
	 */
	protected $plugin;

	/**
	 * @var PublicContainer
	 */
	protected $dic;

	/**
	 * Constructor
	 *
	 * @param \ElggPlugin     $plugin The plugin
	 * @param PublicContainer $dic    Public services
	 */
	public function __construct(\ElggPlugin $plugin, PublicContainer $dic) {
		$this->plugin = $plugin;
		$this->dic = $dic;
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
