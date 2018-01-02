<?php

namespace Elgg\Database;

use DatabaseException;
use ElggCache;
use Elgg\Database;
use Elgg\Profilable;
use ElggPlugin;
use ElggUser;
use Exception;

/**
 * Persistent, installation-wide key-value storage.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @access private
 *
 * @since  1.10.0
 */
class Plugins {

	use Profilable;

	/**
	 * @var ElggPlugin[]
	 */
	protected $boot_plugins;

	/**
	 * @var array|null
	 */
	protected $provides_cache;

	/**
	 * @var string[] Active plugins, with plugin ID => GUID. Missing keys imply inactive plugins.
	 */
	protected $active_guids = [];

	/**
	 * @var bool Has $active_guids been populated?
	 */
	protected $active_guids_known = false;

	/**
	 * @var ElggCache
	 */
	protected $cache;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * Constructor
	 *
	 * @param ElggCache $cache Cache for referencing plugins by ID
	 * @param Database  $db    Database
	 */
	public function __construct(ElggCache $cache, Database $db) {
		$this->cache = $cache;
		$this->db = $db;
	}

	/**
	 * Set the list of active plugins according to the boot data cache
	 *
	 * @param ElggPlugin[]|null $plugins Set of active plugins
	 *
	 * @return void
	 */
	public function setBootPlugins($plugins) {
		$this->boot_plugins = $plugins;
		if (is_array($plugins)) {
			foreach ($plugins as $plugin) {
				if (!$plugin instanceof ElggPlugin || !$plugin->getID()) {
					continue;
				}
				$this->cache->save($plugin->getID(), $plugin);
			}
		}
	}

	/**
	 * Clear plugin caches
	 * @return void
	 */
	public function clear() {
		$this->cache->clear();
		$this->invalidateProvidesCache();
		$this->invalidateIsActiveCache();
	}

	/**
	 * Returns a list of plugin directory names from a base directory.
	 *
	 * @param string $dir A dir to scan for plugins. Defaults to config's plugins_path.
	 *                    Must have a trailing slash.
	 *
	 * @return array Array of directory names (not full paths)
	 * @access private
	 */
	public function getDirsInDir($dir = null) {
		if (!$dir) {
			$dir = elgg_get_plugins_path();
		}

		$plugin_dirs = [];
		$handle = opendir($dir);

		if ($handle) {
			while ($plugin_dir = readdir($handle)) {
				// must be directory and not begin with a .
				if (substr($plugin_dir, 0, 1) !== '.' && is_dir($dir . $plugin_dir)) {
					$plugin_dirs[] = $plugin_dir;
				}
			}
		}

		sort($plugin_dirs);

		return $plugin_dirs;
	}

	/**
	 * Discovers plugins in the plugins_path setting and creates \ElggPlugin
	 * entities for them if they don't exist.  If there are plugins with entities
	 * but not actual files, will disable the \ElggPlugin entities and mark as inactive.
	 * The \ElggPlugin object holds config data, so don't delete.
	 *
	 * @return bool
	 * @throws DatabaseException
	 * @throws \PluginException
	 * @access private
	 */
	public function generateEntities() {

		$mod_dir = elgg_get_plugins_path();

		// ignore access in case this is called with no admin logged in - needed for creating plugins perhaps?
		$old_ia = elgg_set_ignore_access(true);

		// show hidden entities so that we can enable them if appropriate
		$old_access = access_show_hidden_entities(true);

		$known_plugins = $this->find('all');
		/* @var \ElggPlugin[] $known_plugins */

		if (!$known_plugins) {
			$known_plugins = [];
		}

		// map paths to indexes
		$id_map = [];
		foreach ($known_plugins as $i => $plugin) {
			// if the ID is wrong, delete the plugin because we can never load it.
			$id = $plugin->getID();
			if (!$id) {
				$plugin->delete();
				unset($known_plugins[$i]);
				continue;
			}
			$id_map[$plugin->getID()] = $i;
			$plugin->cache();
		}

		$physical_plugins = $this->getDirsInDir($mod_dir);
		if (!$physical_plugins) {
			elgg_set_ignore_access($old_ia);

			return false;
		}

		// check real plugins against known ones
		foreach ($physical_plugins as $plugin_id) {
			// is this already in the db?
			if (array_key_exists($plugin_id, $id_map)) {
				$index = $id_map[$plugin_id];
				$plugin = $known_plugins[$index];
				// was this plugin deleted and its entity disabled?
				if (!$plugin->isEnabled()) {
					$plugin->enable();
					$plugin->deactivate();
					$plugin->setPriority('last');
				}

				// remove from the list of plugins to disable
				unset($known_plugins[$index]);
			} else {
				// create new plugin
				// priority is forced to last in save() if not set.
				$plugin = ElggPlugin::fromId($plugin_id);
				$plugin->cache();
			}
		}

		// everything remaining in $known_plugins needs to be disabled
		// because they are entities, but their dirs were removed.
		// don't delete the entities because they hold settings.
		foreach ($known_plugins as $plugin) {
			if ($plugin->isActive()) {
				$plugin->deactivate();
			}
			// remove the priority.
			$name = $this->namespacePrivateSetting('internal', 'priority');
			remove_private_setting($plugin->guid, $name);
			if ($plugin->isEnabled()) {
				$plugin->disable();
			}
		}

		access_show_hidden_entities($old_access);
		elgg_set_ignore_access($old_ia);

		return true;
	}

	/**
	 * Cache a reference to this plugin by its ID
	 *
	 * @param ElggPlugin $plugin the plugin to cache
	 *
	 * @return void
	 *
	 * @access private
	 */
	public function cache(ElggPlugin $plugin) {
		if (!$plugin->getID()) {
			return;
		}
		$this->cache->save($plugin->getID(), $plugin);
	}

	/**
	 * Remove plugin from cache
	 *
	 * @param string $plugin_id Plugin ID
	 *
	 * @return void
	 */
	public function invalidateCache($plugin_id) {
		try {
			$this->cache->delete($plugin_id);
			$this->invalidateProvidesCache();
		} catch (\InvalidArgumentException $ex) {
			// A plugin must have been deactivated due to missing folder
			// without proper cleanup
			elgg_flush_caches();
		}
	}

	/**
	 * Returns an \ElggPlugin object with the path $path.
	 *
	 * @param string $plugin_id The id (dir name) of the plugin. NOT the guid.
	 *
	 * @return ElggPlugin|null
	 */
	public function get($plugin_id) {
		if (!$plugin_id) {
			return;
		}

		$fallback = function () use ($plugin_id) {
			$plugins = elgg_get_entities([
				'type' => 'object',
				'subtype' => 'plugin',
				'metadata_name_value_pairs' => [
					'name' => 'title',
					'value' => $plugin_id,
				],
				'limit' => 1,
				'distinct' => false,
			]);

			if ($plugins) {
				return $plugins[0];
			}

			return null;
		};

		$plugin = $this->cache->load($plugin_id);
		if (!isset($plugin)) {
			$plugin = $fallback();
			if ($plugin instanceof ElggPlugin) {
				$plugin->cache();
			}
		}

		return $plugin;
	}

	/**
	 * Returns if a plugin exists in the system.
	 *
	 * @warning This checks only plugins that are registered in the system!
	 * If the plugin cache is outdated, be sure to regenerate it with
	 * {@link _elgg_generate_plugin_objects()} first.
	 *
	 * @param string $id The plugin ID.
	 *
	 * @return bool
	 */
	function exists($id) {
		return (bool) $this->get($id);
	}

	/**
	 * Returns the highest priority of the plugins
	 *
	 * @return int
	 * @access private
	 * @throws DatabaseException
	 */
	public function getMaxPriority() {
		$priority = $this->namespacePrivateSetting('internal', 'priority');

		$qb = Select::fromTable('entities', 'e');
		$qb->select('MAX(CAST(ps.value AS unsigned)) as max')
			->join('e', 'private_settings', 'ps', 'e.guid = ps.entity_guid')
			->where($qb->compare('ps.name', '=', $priority, ELGG_VALUE_STRING))
			->andWhere($qb->compare('e.type', '=', 'object', ELGG_VALUE_STRING))
			->andWhere($qb->compare('e.subtype', '=', 'plugin', ELGG_VALUE_STRING));

		$data = _elgg_services()->db->getDataRow($qb);

		$max = 1;
		if ($data) {
			$max = (int) $data->max;
		}

		return max(1, $max);
	}

	/**
	 * Returns if a plugin is active for a current site.
	 *
	 * @param string $plugin_id The plugin ID
	 *
	 * @return bool
	 */
	function isActive($plugin_id) {
		if ($this->active_guids_known) {
			return isset($this->active_guids[$plugin_id]);
		}

		$site = elgg_get_site_entity();

		if (!($site instanceof \ElggSite)) {
			return false;
		}

		$plugin = $this->get($plugin_id);

		if (!$plugin) {
			return false;
		}

		return $plugin->isActive();
	}

	/**
	 * Loads all active plugins in the order specified in the tool admin panel.
	 *
	 * @note   This is called on every page load. If a plugin is active and problematic, it
	 * will be disabled and a visible error emitted. This does not check the deps system because
	 * that was too slow.
	 *
	 * @return bool
	 * @access private
	 * @throws \PluginException
	 */
	function load() {
		if ($this->timer) {
			$this->timer->begin([__METHOD__]);
		}

		$plugins_path = elgg_get_plugins_path();
		$start_flags = ELGG_PLUGIN_INCLUDE_START |
			ELGG_PLUGIN_REGISTER_VIEWS |
			ELGG_PLUGIN_REGISTER_ACTIONS |
			ELGG_PLUGIN_REGISTER_LANGUAGES |
			ELGG_PLUGIN_REGISTER_WIDGETS |
			ELGG_PLUGIN_REGISTER_CLASSES |
			ELGG_PLUGIN_REGISTER_SERVICES;

		if (!$plugins_path) {
			return false;
		}

		// temporary disable all plugins if there is a file called 'disabled' in the plugin dir
		if (file_exists("$plugins_path/disabled")) {
			if (elgg_is_admin_logged_in() && elgg_in_context('admin')) {
				system_message(_elgg_services()->translator->translate('plugins:disabled'));
			}

			return false;
		}

		$config = _elgg_config();

		if ($config->system_cache_loaded) {
			$start_flags = $start_flags & ~ELGG_PLUGIN_REGISTER_VIEWS;
		}

		if (_elgg_services()->translator->wasLoadedFromCache()) {
			$start_flags = $start_flags & ~ELGG_PLUGIN_REGISTER_LANGUAGES;
		}

		$plugins = $this->boot_plugins;
		if (!$plugins) {
			$this->active_guids_known = true;

			return true;
		}

		$return = true;
		foreach ($plugins as $plugin) {
			$id = $plugin->getID();
			try {
				$plugin->start($start_flags);
				$this->active_guids[$id] = $plugin->guid;
			} catch (Exception $e) {
				$disable_plugins = _elgg_config()->auto_disable_plugins;
				if ($disable_plugins === null) {
					$disable_plugins = true;
				}
				if ($disable_plugins) {
					$plugin->deactivate();

					$msg = _elgg_services()->translator->translate('PluginException:CannotStart',
						[$id, $plugin->guid, $e->getMessage()]);
					elgg_add_admin_notice("cannot_start $id", $msg);
					$return = false;
				}
			}
		}

		$this->active_guids_known = true;

		if ($this->timer) {
			$this->timer->end([__METHOD__]);
		}

		return $return;
	}

	/**
	 * Returns an ordered list of plugins
	 *
	 * @param string $status The status of the plugins. active, inactive, or all.
	 *
	 * @return ElggPlugin[]
	 */
	public function find($status = 'active') {
		if (!_elgg_services()->db) {
			return [];
		}

		if ($status === 'active' && isset($this->boot_plugins)) {
			$plugins = $this->boot_plugins;
		} else {
			$priority = $this->namespacePrivateSetting('internal', 'priority');
			$site_guid = 1;

			// grab plugins
			$options = [
				'type' => 'object',
				'subtype' => 'plugin',
				'limit' => 0,
				'selects' => ['ps.value'],
				'private_setting_names' => [$priority],
				// ORDER BY CAST(ps.value) is super slow. We usort() below.
				'order_by' => false,
			];

			switch ($status) {
				case 'active':
					$options['relationship'] = 'active_plugin';
					$options['relationship_guid'] = $site_guid;
					$options['inverse_relationship'] = true;
					break;

				case 'inactive':
					$options['wheres'][] = function (QueryBuilder $qb) use ($site_guid) {
						$subquery = $qb->subquery('entity_relationships', 'active_er');
						$subquery->select('*')
							->where($qb->compare('active_er.guid_one', '=', 'e.guid'))
							->andWhere($qb->compare('active_er.relationship', '=', 'active_plugin', ELGG_VALUE_STRING))
							->andWhere($qb->compare('active_er.guid_two', '=', 1));

						return "NOT EXISTS ({$subquery->getSQL()})";
					};
					break;

				case 'all':
				default:
					break;
			}

			$old_ia = elgg_set_ignore_access(true);
			$plugins = elgg_get_entities($options) ? : [];
			elgg_set_ignore_access($old_ia);
		}

		usort($plugins, function (ElggPlugin $a, ElggPlugin $b) {
			$a_value = $a->getVolatileData('select:value');
			$b_value = $b->getVolatileData('select:value');

			if ($b_value !== $a_value) {
				return $a_value - $b_value;
			} else {
				return $a->guid - $b->guid;
			}
		});

		return $plugins;
	}

	/**
	 * Reorder plugins to an order specified by the array.
	 * Plugins not included in this array will be appended to the end.
	 *
	 * @note   This doesn't use the \ElggPlugin->setPriority() method because
	 *       all plugins are being changed and we don't want it to automatically
	 *       reorder plugins.
	 * @todo   Can this be done in a single sql command?
	 *
	 * @param array $order An array of plugin ids in the order to set them
	 *
	 * @return bool
	 * @access private
	 */
	function setPriorities(array $order) {
		$name = $this->namespacePrivateSetting('internal', 'priority');

		$plugins = $this->find('any');
		if (!$plugins) {
			return false;
		}

		// reindex to get standard counting. no need to increment by 10.
		// though we do start with 1
		$order = array_values($order);

		$missing_plugins = [];
		/* @var ElggPlugin[] $missing_plugins */

		$priority = 0;
		foreach ($plugins as $plugin) {
			$plugin_id = $plugin->getID();

			if (!in_array($plugin_id, $order)) {
				$missing_plugins[] = $plugin;
				continue;
			}

			$priority = array_search($plugin_id, $order) + 1;

			if (!$plugin->setPrivateSetting($name, $priority)) {
				return false;
			}
		}

		// set the missing plugins' priorities
		if (empty($missing_plugins)) {
			return true;
		}

		foreach ($missing_plugins as $plugin) {
			$priority++;
			if (!$plugin->setPrivateSetting($name, $priority)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Reindexes all plugin priorities starting at 1.
	 *
	 * @return bool
	 * @access private
	 */
	function reindexPriorities() {
		return $this->setPriorities([]);
	}

	/**
	 * Namespaces a string to be used as a private setting name for a plugin.
	 *
	 * For user_settings, two namespaces are added: a user setting namespace and the
	 * plugin id.
	 *
	 * For internal (plugin priority), there is a single internal namespace added.
	 *
	 * @param string $type The type of setting: user_setting or internal.
	 * @param string $name The name to namespace.
	 * @param string $id   The plugin's ID to namespace with.  Required for user_setting.
	 *
	 * @return string
	 * @access private
	 */
	function namespacePrivateSetting($type, $name, $id = null) {
		switch ($type) {
			case 'user_setting':
				if (!$id) {
					throw new \InvalidArgumentException("You must pass the plugin id for user settings");
				}
				$name = ELGG_PLUGIN_USER_SETTING_PREFIX . "$id:$name";
				break;

			case 'internal':
				$name = ELGG_PLUGIN_INTERNAL_PREFIX . $name;
				break;
		}

		return $name;
	}


	/**
	 * Returns an array of all provides from all active plugins.
	 *
	 * Array in the form array(
	 *    'provide_type' => array(
	 *        'provided_name' => array(
	 *            'version' => '1.8',
	 *            'provided_by' => 'provider_plugin_id'
	 *    )
	 *  )
	 * )
	 *
	 * @param string $type The type of provides to return
	 * @param string $name A specific provided name to return. Requires $provide_type.
	 *
	 * @return array|false
	 * @access private
	 */
	function getProvides($type = null, $name = null) {
		if ($this->provides_cache === null) {
			$active_plugins = $this->find('active');

			$provides = [];

			foreach ($active_plugins as $plugin) {
				$plugin_provides = [];
				$manifest = $plugin->getManifest();
				if ($manifest instanceof \ElggPluginManifest) {
					$plugin_provides = $plugin->getManifest()->getProvides();
				}
				if ($plugin_provides) {
					foreach ($plugin_provides as $provided) {
						$provides[$provided['type']][$provided['name']] = [
							'version' => $provided['version'],
							'provided_by' => $plugin->getID()
						];
					}
				}
			}

			$this->provides_cache = $provides;
		}

		if ($type && $name) {
			if (isset($this->provides_cache[$type][$name])) {
				return $this->provides_cache[$type][$name];
			} else {
				return false;
			}
		} else if ($type) {
			if (isset($this->provides_cache[$type])) {
				return $this->provides_cache[$type];
			} else {
				return false;
			}
		}

		return $this->provides_cache;
	}

	/**
	 * Deletes all cached data on plugins being provided.
	 *
	 * @return boolean
	 * @access private
	 */
	function invalidateProvidesCache() {
		$this->provides_cache = null;

		return true;
	}

	/**
	 * Delete the cache holding whether plugins are active or not
	 *
	 * @return void
	 * @access private
	 */
	public function invalidateIsActiveCache() {
		$this->active_guids = [];
		$this->active_guids_known = false;
	}

	/**
	 * Checks if a plugin is currently providing $type and $name, and optionally
	 * checking a version.
	 *
	 * @param string $type       The type of the provide
	 * @param string $name       The name of the provide
	 * @param string $version    A version to check against
	 * @param string $comparison The comparison operator to use in version_compare()
	 *
	 * @return array An array in the form array(
	 *    'status' => bool Does the provide exist?,
	 *    'value' => string The version provided
	 * )
	 * @access private
	 */
	function checkProvides($type, $name, $version = null, $comparison = 'ge') {
		$provided = $this->getProvides($type, $name);
		if (!$provided) {
			return [
				'status' => false,
				'value' => ''
			];
		}

		if ($version) {
			$status = version_compare($provided['version'], $version, $comparison);
		} else {
			$status = true;
		}

		return [
			'status' => $status,
			'value' => $provided['version']
		];
	}

	/**
	 * Returns an array of parsed strings for a dependency in the
	 * format: array(
	 *    'type'            =>    requires, conflicts, or provides.
	 *    'name'            =>    The name of the requirement / conflict
	 *    'value'            =>    A string representing the expected value: <1, >=3, !=enabled
	 *    'local_value'    =>    The current value, ("Not installed")
	 *    'comment'        =>    Free form text to help resovle the problem ("Enable / Search for plugin <link>")
	 * )
	 *
	 * @param array $dep An \ElggPluginPackage dependency array
	 *
	 * @return array
	 * @access private
	 */
	function getDependencyStrings($dep) {
		$translator = _elgg_services()->translator;
		$dep_system = elgg_extract('type', $dep);
		$info = elgg_extract('dep', $dep);
		$type = elgg_extract('type', $info);

		if (!$dep_system || !$info || !$type) {
			return false;
		}

		// rewrite some of these to be more readable
		$comparison = elgg_extract('comparison', $info);
		switch ($comparison) {
			case 'lt':
				$comparison = '<';
				break;
			case 'gt':
				$comparison = '>';
				break;
			case 'ge':
				$comparison = '>=';
				break;
			case 'le':
				$comparison = '<=';
				break;
			default:
				//keep $comparison value intact
				break;
		}

		/*
		'requires'	'plugin oauth_lib'	<1.3	1.3		'downgrade'
		'requires'	'php setting bob'	>3		3		'change it'
		'conflicts'	'php setting'		>3		4		'change it'
		'conflicted''plugin profile'	any		1.8		'disable profile'
		'provides'	'plugin oauth_lib'	1.3		--		--
		'priority'	'before blog'		--		after	'move it'
		*/
		$strings = [];
		$strings['type'] = $translator->translate('ElggPlugin:Dependencies:' . ucwords($dep_system));

		switch ($type) {
			case 'elgg_release':
				// 'Elgg Version'
				$strings['name'] = $translator->translate('ElggPlugin:Dependencies:Elgg');
				$strings['expected_value'] = "$comparison {$info['version']}";
				$strings['local_value'] = $dep['value'];
				$strings['comment'] = '';
				break;

			case 'php_version':
				// 'PHP version'
				$strings['name'] = $translator->translate('ElggPlugin:Dependencies:PhpVersion');
				$strings['expected_value'] = "$comparison {$info['version']}";
				$strings['local_value'] = $dep['value'];
				$strings['comment'] = '';
				break;

			case 'php_extension':
				// PHP Extension %s [version]
				$strings['name'] = $translator->translate('ElggPlugin:Dependencies:PhpExtension', [$info['name']]);
				if ($info['version']) {
					$strings['expected_value'] = "$comparison {$info['version']}";
					$strings['local_value'] = $dep['value'];
				} else {
					$strings['expected_value'] = '';
					$strings['local_value'] = '';
				}
				$strings['comment'] = '';
				break;

			case 'php_ini':
				$strings['name'] = $translator->translate('ElggPlugin:Dependencies:PhpIni', [$info['name']]);
				$strings['expected_value'] = "$comparison {$info['value']}";
				$strings['local_value'] = $dep['value'];
				$strings['comment'] = '';
				break;

			case 'plugin':
				$strings['name'] = $translator->translate('ElggPlugin:Dependencies:Plugin', [$info['name']]);
				$expected = $info['version'] ? "$comparison {$info['version']}" : $translator->translate('any');
				$strings['expected_value'] = $expected;
				$strings['local_value'] = $dep['value'] ? $dep['value'] : '--';
				$strings['comment'] = '';
				break;

			case 'priority':
				$expected_priority = ucwords($info['priority']);
				$real_priority = ucwords($dep['value']);
				$strings['name'] = $translator->translate('ElggPlugin:Dependencies:Priority');
				$strings['expected_value'] = $translator->translate("ElggPlugin:Dependencies:Priority:$expected_priority", [$info['plugin']]);
				$strings['local_value'] = $translator->translate("ElggPlugin:Dependencies:Priority:$real_priority", [$info['plugin']]);
				$strings['comment'] = '';
				break;
		}

		if ($dep['type'] == 'suggests') {
			if ($dep['status']) {
				$strings['comment'] = $translator->translate('ok');
			} else {
				$strings['comment'] = $translator->translate('ElggPlugin:Dependencies:Suggests:Unsatisfied');
			}
		} else {
			if ($dep['status']) {
				$strings['comment'] = $translator->translate('ok');
			} else {
				$strings['comment'] = $translator->translate('error');
			}
		}

		return $strings;
	}

	/**
	 * Get all settings (excluding user settings) for a plugin
	 *
	 * @param \ElggPlugin $plugin Plugin
	 *
	 * @return string[]
	 * @throws DatabaseException
	 */
	public function getAllSettings(ElggPlugin $plugin) {
		if (!$plugin->guid) {
			return [];
		}

		$values = _elgg_services()->privateSettingsCache->load($plugin->guid);
		if (isset($values)) {
			return $values;
		}

		$us_prefix = $this->namespacePrivateSetting('user_setting', '', $plugin->getID());

		// Get private settings for user
		$qb = Select::fromTable('private_settings');
		$qb->select('name')
			->addSelect('value')
			->where($qb->compare('name', 'not like', "$us_prefix%", ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '=', $plugin->guid, ELGG_VALUE_GUID));

		$rows = $this->db->getData($qb);

		$settings = [];

		if (!empty($rows)) {
			foreach ($rows as $row) {
				$settings[$row->name] = $row->value;
			}
		}

		_elgg_services()->privateSettingsCache->save($plugin->guid, $settings);

		return $settings;
	}

	/**
	 * Returns an array of all plugin user settings for a user
	 *
	 * @param ElggPlugin $plugin Plugin
	 * @param ElggUser   $user   User
	 *
	 * @return array
	 * @see  ElggPlugin::getAllUserSettings()
	 * @throws DatabaseException
	 */
	public function getAllUserSettings(ElggPlugin $plugin, ElggUser $user = null) {

		// send an empty name so we just get the first part of the namespace
		$prefix = $this->namespacePrivateSetting('user_setting', '', $plugin->getID());

		$qb = Select::fromTable('private_settings');
		$qb->select('name')
			->addSelect('value')
			->where($qb->compare('name', 'like', "{$prefix}%"));

		if ($user) {
			$qb->andWhere($qb->compare('entity_guid', '=', $user->guid, ELGG_VALUE_INTEGER));
		}

		$rows = $this->db->getData($qb);

		$settings = [];

		if (!empty($rows)) {
			foreach ($rows as $rows) {
				$name = substr($rows->name, strlen($prefix));
				$value = $rows->value;

				$settings[$name] = $value;
			}
		}

		return $settings;
	}

	/**
	 * Set a user specific setting for a plugin.
	 *
	 * @param string $name      The name. Note: cannot be "title".
	 * @param mixed  $value     The value.
	 * @param int    $user_guid The user GUID or 0 for the currently logged in user.
	 * @param string $plugin_id The plugin ID (Required)
	 *
	 * @return bool
	 * @see \ElggPlugin::setUserSetting()
	 */
	function setUserSetting($name, $value, $user_guid = 0, $plugin_id = null) {
		$plugin = $this->get($plugin_id);
		if (!$plugin) {
			return false;
		}

		return $plugin->setUserSetting($name, $value, (int) $user_guid);
	}

	/**
	 * Unsets a user-specific plugin setting
	 *
	 * @param string $name      Name of the setting
	 * @param int    $user_guid The user GUID or 0 for the currently logged in user.
	 * @param string $plugin_id The plugin ID (Required)
	 *
	 * @return bool
	 * @see \ElggPlugin::unsetUserSetting()
	 */
	function unsetUserSetting($name, $user_guid = 0, $plugin_id = null) {
		$plugin = $this->get($plugin_id);
		if (!$plugin) {
			return false;
		}

		return $plugin->unsetUserSetting($name, (int) $user_guid);
	}

	/**
	 * Get a user specific setting for a plugin.
	 *
	 * @param string $name      The name of the setting.
	 * @param int    $user_guid The user GUID or 0 for the currently logged in user.
	 * @param string $plugin_id The plugin ID (Required)
	 * @param mixed  $default   The default value to return if none is set
	 *
	 * @return mixed
	 * @see \ElggPlugin::getUserSetting()
	 */
	function getUserSetting($name, $user_guid = 0, $plugin_id = null, $default = null) {
		$plugin = $this->get($plugin_id);
		if (!$plugin) {
			return false;
		}

		return $plugin->getUserSetting($name, (int) $user_guid, $default);
	}

	/**
	 * Set a setting for a plugin.
	 *
	 * @param string $name      The name of the setting - note, can't be "title".
	 * @param mixed  $value     The value.
	 * @param string $plugin_id The plugin ID (Required)
	 *
	 * @return bool
	 * @see \ElggPlugin::setSetting()
	 */
	function setSetting($name, $value, $plugin_id) {
		$plugin = $this->get($plugin_id);
		if (!$plugin) {
			return false;
		}

		return $plugin->setSetting($name, $value);
	}

	/**
	 * Get setting for a plugin.
	 *
	 * @param string $name      The name of the setting.
	 * @param string $plugin_id The plugin ID (Required)
	 * @param mixed  $default   The default value to return if none is set
	 *
	 * @return mixed
	 * @see \ElggPlugin::getSetting()
	 */
	function getSetting($name, $plugin_id, $default = null) {
		$plugin = $this->get($plugin_id);
		if (!$plugin) {
			return false;
		}

		return $plugin->getSetting($name, $default);
	}

	/**
	 * Unsets a plugin setting.
	 *
	 * @param string $name      The name of the setting.
	 * @param string $plugin_id The plugin ID (Required)
	 *
	 * @return bool
	 * @see \ElggPlugin::unsetSetting()
	 */
	function unsetSetting($name, $plugin_id) {
		$plugin = $this->get($plugin_id);
		if (!$plugin) {
			return false;
		}

		return $plugin->unsetSetting($name);
	}

	/**
	 * Unsets all plugin settings for a plugin.
	 *
	 * @param string $plugin_id The plugin ID (Required)
	 *
	 * @return bool
	 * @see \ElggPlugin::unsetAllSettings()
	 */
	function unsetAllSettings($plugin_id) {
		$plugin = $this->get($plugin_id);
		if (!$plugin) {
			return false;
		}

		return $plugin->unsetAllSettings();
	}

	/**
	 * Returns entities based upon plugin user settings.
	 * Takes all the options for {@link elgg_get_entities_from_private_settings()}
	 * in addition to the ones below.
	 *
	 * @param array $options Array in the format:
	 *
	 *    plugin_id => STR The plugin id. Required.
	 *
	 *    plugin_user_setting_names => null|ARR private setting names
	 *
	 *    plugin_user_setting_values => null|ARR metadata values
	 *
	 *    plugin_user_setting_name_value_pairs => null|ARR (
	 *                                         name => 'name',
	 *                                         value => 'value',
	 *                                         'operand' => '=',
	 *                                        )
	 *                                 Currently if multiple values are sent via
	 *                               an array (value => array('value1', 'value2')
	 *                               the pair's operand will be forced to "IN".
	 *
	 *    plugin_user_setting_name_value_pairs_operator => null|STR The operator to use for combining
	 *                                        (name = value) OPERATOR (name = value); default AND
	 *
	 * @return mixed int If count, int. If not count, array. false on errors.
	 */
	public function getEntitiesFromUserSettings(array $options = []) {
		$singulars = [
			'plugin_user_setting_name',
			'plugin_user_setting_value',
			'plugin_user_setting_name_value_pair'
		];

		$options = _elgg_normalize_plural_options_array($options, $singulars);

		// rewrite plugin_user_setting_name_* to the right PS ones.
		$map = [
			'plugin_user_setting_names' => 'private_setting_names',
			'plugin_user_setting_values' => 'private_setting_values',
			'plugin_user_setting_name_value_pairs' => 'private_setting_name_value_pairs',
			'plugin_user_setting_name_value_pairs_operator' => 'private_setting_name_value_pairs_operator',
		];

		foreach ($map as $plugin => $private) {
			if (!isset($options[$plugin])) {
				continue;
			}

			if (isset($options[$private])) {
				if (!is_array($options[$private])) {
					$options[$private] = [$options[$private]];
				}

				$options[$private] = array_merge($options[$private], $options[$plugin]);
			} else {
				$options[$private] = $options[$plugin];
			}
		}

		$prefix = $this->namespacePrivateSetting('user_setting', '', $options['plugin_id']);
		$options['private_setting_name_prefix'] = $prefix;

		return elgg_get_entities($options);
	}

	/**
	 * Set plugin priority and adjust the priorities of other plugins
	 *
	 * @param ElggPlugin $plugin   Plugin
	 * @param int        $priority New priority
	 *
	 * @return int|false
	 * @throws DatabaseException
	 */
	public function setPriority(ElggPlugin $plugin, $priority) {

		$old_priority = $plugin->getPriority() ? : 1;

		$name = $this->namespacePrivateSetting('internal', 'priority');

		if (!$plugin->guid) {
			return false;
		}

		if (!$plugin->setPrivateSetting($name, $priority)) {
			return false;
		}

		$qb = Update::table('private_settings');
		$qb->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->between('CAST(value AS UNSIGNED)', $old_priority, $priority, ELGG_VALUE_INTEGER))
			->andWhere($qb->compare('entity_guid', '!=', $plugin->guid, ELGG_VALUE_INTEGER));

		if ($priority > $old_priority) {
			$qb->set('value', "CAST(value AS UNSIGNED) - 1");
		} else {
			$qb->set('value', "CAST(value AS UNSIGNED) + 1");
		}

		if (!$this->db->updateData($qb)) {
			return false;
		}

		return $priority;
	}
}
