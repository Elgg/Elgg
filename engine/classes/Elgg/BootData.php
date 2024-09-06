<?php

namespace Elgg;

use Elgg\Database\EntityTable;
use Elgg\Database\Plugins;
use Elgg\Exceptions\Configuration\InstallationException;

/**
 * Serializable collection of data used to boot Elgg
 *
 * @internal
 * @since 2.1
 */
class BootData {

	/**
	 * @var \ElggSite|false
	 */
	private $site = false;

	/**
	 * @var \ElggPlugin[]
	 */
	private $active_plugins;

	private array $plugin_metadata = [];

	/**
	 * Populate the boot data
	 *
	 * @param EntityTable $entities  Entities service
	 * @param Plugins     $plugins   Plugins service
	 * @param bool        $installed Is the site installed?
	 *
	 * @return void
	 * @throws InstallationException
	 */
	public function populate(EntityTable $entities, Plugins $plugins, bool $installed) {
		// get site entity
		$this->site = $entities->get(1, 'site');
		if (!$this->site && $installed) {
			throw new InstallationException('Unable to handle this request. This site is not configured or the database is down.');
		}

		$this->active_plugins = $plugins->find('active');
		if (empty($this->active_plugins)) {
			return;
		}
		
		$this->plugin_metadata = _elgg_services()->metadataCache->populateFromEntities($this->active_plugins);
	}

	/**
	 * Get the site entity
	 *
	 * @return \ElggSite|false False if not installed
	 */
	public function getSite() {
		return $this->site;
	}

	/**
	 * Get active plugins
	 *
	 * @return \ElggPlugin[]
	 */
	public function getActivePlugins() {
		return $this->active_plugins;
	}

	/**
	 * Get plugin metadata
	 *
	 * @return array
	 */
	public function getPluginMetadata(): array {
		return $this->plugin_metadata;
	}
}
