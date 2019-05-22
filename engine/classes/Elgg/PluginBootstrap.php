<?php

namespace Elgg;

use Elgg\Di\PublicContainer;
use ElggPlugin;

/**
 * Plugin bootstrap
 */
abstract class PluginBootstrap implements PluginBootstrapInterface {

	/**
	 * @var ElggPlugin
	 */
	protected $plugin;

	/**
	 * @var PublicContainer
	 */
	protected $dic;

	/**
	 * Constructor
	 *
	 * @param ElggPlugin      $plugin
	 * @param PublicContainer $dic
	 */
	public function __construct(ElggPlugin $plugin, PublicContainer $dic) {
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
