<?php

namespace Elgg\Database;

use Elgg\Cache\PrivateSettingsCache;
use Elgg\Config;
use Elgg\Context;
use Elgg\Database;
use Elgg\EventsService;
use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\PluginException;
use Elgg\I18n\Translator;
use Elgg\Project\Paths;
use Elgg\SystemMessagesService;
use Elgg\Traits\Cacheable;
use Elgg\Traits\Debug\Profilable;
use Elgg\Traits\Loggable;
use Elgg\ViewsService;
use Psr\Log\LogLevel;

/**
 * Persistent, installation-wide key-value storage.
 *
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * @internal
 * @since  1.10.0
 */
class Plugins {

	use Profilable;
	use Cacheable;
	use Loggable;
	
	const BUNDLED_PLUGINS = [
		'activity',
		'blog',
		'bookmarks',
		'ckeditor',
		'custom_index',
		'dashboard',
		'developers',
		'discussions',
		'embed',
		'externalpages',
		'file',
		'friends',
		'friends_collections',
		'garbagecollector',
		'groups',
		'invitefriends',
		'likes',
		'login_as',
		'members',
		'messageboard',
		'messages',
		'notifications',
		'pages',
		'profile',
		'reportedcontent',
		'search',
		'site_notifications',
		'system_log',
		'tagcloud',
		'thewire',
		'uservalidationbyemail',
		'web_services',
	];

	/**
	 * @var \ElggPlugin[]
	 */
	protected $boot_plugins;

	/**
	 * @var Database
	 */
	protected $db;

	/**
	 * @var \ElggSession
	 */
	protected $session;

	/**
	 * @var EventsService
	 */
	protected $events;

	/**
	 * @var Translator
	 */
	protected $translator;

	/**
	 * @var ViewsService
	 */
	protected $views;

	/**
	 * @var PrivateSettingsCache
	 */
	protected $private_settings_cache;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var SystemMessagesService
	 */
	protected $system_messages;

	/**
	 * @var Context
	 */
	protected $context;


	/**
	 * Constructor
	 *
	 * @param \ElggCache            $cache                  Cache for referencing plugins by ID
	 * @param Database              $db                     Database
	 * @param \ElggSession          $session                Session
	 * @param EventsService         $events                 Events
	 * @param Translator            $translator             Translator
	 * @param ViewsService          $views                  Views service
	 * @param PrivateSettingsCache  $private_settings_cache Settings cache
	 * @param Config                $config                 Config
	 * @param SystemMessagesService $system_messages        System messages
	 * @param Context               $context                Context
	 */
	public function __construct(
		\ElggCache $cache,
		Database $db,
		\ElggSession $session,
		EventsService $events,
		Translator $translator,
		ViewsService $views,
		PrivateSettingsCache $private_settings_cache,
		Config $config,
		SystemMessagesService $system_messages,
		Context $context
	) {
		$this->cache = $cache;
		$this->db = $db;
		$this->session = $session;
		$this->events = $events;
		$this->translator = $translator;
		$this->views = $views;
		$this->private_settings_cache = $private_settings_cache;
		$this->config = $config;
		$this->system_messages = $system_messages;
		$this->context = $context;
	}

	/**
	 * Get the plugin path for this installation, ending with slash.
	 *
	 * @return string
	 */
	public function getPath() {
		$path = $this->config->plugins_path;
		if (!$path) {
			$path = Paths::project() . 'mod/';
		}
		return $path;
	}

	/**
	 * Set the list of active plugins according to the boot data cache
	 *
	 * @param \ElggPlugin[]|null $plugins       Set of active plugins
	 * @param bool               $order_plugins Make sure plugins are saved in the correct order (set to false if provided plugins are already sorted)
	 *
	 * @return void
	 */
	public function setBootPlugins($plugins, $order_plugins = true) {
		if (!is_array($plugins)) {
			unset($this->boot_plugins);
			return;
		}
		
		// Always (re)set the boot_plugins. This makes sure that even if you have no plugins active this is known to the system.
		$this->boot_plugins = [];
		
		if ($order_plugins) {
			$plugins = $this->orderPluginsByPriority($plugins);
		}
		
		foreach ($plugins as $plugin) {
			if (!$plugin instanceof \ElggPlugin) {
				continue;
			}
			
			$plugin_id = $plugin->getID();
			if (!$plugin_id) {
				continue;
			}

			$plugin->registerLanguages();
			
			$this->boot_plugins[$plugin_id] = $plugin;
			$this->cache->save($plugin_id, $plugin);
		}
	}

	/**
	 * Clear plugin caches
	 * @return void
	 */
	public function clear() {
		$this->cache->clear();
	}
	
	/**
	 * Invalidate plugin cache
	 *
	 * @return void
	 */
	public function invalidate() {
		$this->cache->invalidate();
	}
	
	/**
	 * Returns a list of plugin directory names from a base directory.
	 *
	 * @param string $dir A dir to scan for plugins. Defaults to config's plugins_path.
	 *                    Must have a trailing slash.
	 *
	 * @return array Array of directory names (not full paths)
	 */
	public function getDirsInDir($dir = null) {
		if (!$dir) {
			$dir = $this->getPath();
		}

		if (!is_dir($dir)) {
			return [];
		}
		
		$handle = opendir($dir);
		if ($handle === false) {
			return [];
		}
		
		$plugin_dirs = [];
		while (($plugin_dir = readdir($handle)) !== false) {
			// must be directory and not begin with a .
			if (substr($plugin_dir, 0, 1) !== '.' && is_dir($dir . $plugin_dir)) {
				$plugin_dirs[] = $plugin_dir;
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
	 */
	public function generateEntities(): bool {

		$mod_dir = $this->getPath();

		// ignore access in case this is called with no admin logged in - needed for creating plugins perhaps?
		$old_ia = $this->session->setIgnoreAccess(true);

		// show hidden entities so that we can enable them if appropriate
		$old_access = $this->session->setDisabledEntityVisibility(true);

		$known_plugins = $this->find('all');
		if (empty($known_plugins)) {
			$known_plugins = [];
		}

		// keeps track if reindexing is needed
		$reindex = false;
		
		// map paths to indexes
		$id_map = [];
		$latest_priority = -1;
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
			
			// disabled plugins should have no priority, so no need to check if the priority is incorrect
			if (!$plugin->isEnabled()) {
				continue;
			}
			
			$current_priority = $plugin->getPriority();
			if (($current_priority - $latest_priority) > 1) {
				$reindex = true;
			}
			
			$latest_priority = $current_priority;
		}

		$physical_plugins = $this->getDirsInDir($mod_dir);
		if (empty($physical_plugins)) {
			$this->session->setIgnoreAccess($old_ia);
			$this->session->setDisabledEntityVisibility($old_access);

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
					try {
						$plugin->deactivate();
					} catch (PluginException $e) {
						// do nothing
					}
					$plugin->setPriority('new');
				}

				// remove from the list of plugins to disable
				unset($known_plugins[$index]);
			} else {
				// create new plugin
				// priority is forced to last in save() if not set.
				$plugin = \ElggPlugin::fromId($plugin_id);
				$plugin->cache();
			}
		}

		// everything remaining in $known_plugins needs to be disabled
		// because they are entities, but their dirs were removed.
		// don't delete the entities because they hold settings.
		foreach ($known_plugins as $plugin) {
			if (!$plugin->isEnabled()) {
				continue;
			}
			
			$reindex = true;
			
			if ($plugin->isActive()) {
				try {
					$plugin->deactivate();
				} catch (PluginException $e) {
					// do nothing
				}
			}
			
			// remove the priority.
			$plugin->removePrivateSetting(\ElggPlugin::PRIORITY_SETTING_NAME);
			
			$plugin->disable();
		}
		
		if ($reindex) {
			$this->reindexPriorities();
		}

		$this->session->setIgnoreAccess($old_ia);
		$this->session->setDisabledEntityVisibility($old_access);

		return true;
	}

	/**
	 * Cache a reference to this plugin by its ID
	 *
	 * @param \ElggPlugin $plugin the plugin to cache
	 *
	 * @return void
	 */
	public function cache(\ElggPlugin $plugin) {
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
		} catch (InvalidArgumentException $ex) {
			// A plugin must have been deactivated due to missing folder
			// without proper cleanup
			elgg_invalidate_caches();
		}
	}

	/**
	 * Returns an \ElggPlugin object with the path $path.
	 *
	 * @param string $plugin_id The id (dir name) of the plugin. NOT the guid.
	 *
	 * @return \ElggPlugin|null
	 */
	public function get(string $plugin_id): ?\ElggPlugin {
		if (!$plugin_id) {
			return null;
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
			if ($plugin instanceof \ElggPlugin) {
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
	public function exists(string $id): bool {
		return $this->get($id) instanceof \ElggPlugin;
	}

	/**
	 * Returns the highest priority of the plugins
	 *
	 * @return int
	 */
	public function getMaxPriority() {
		$qb = Select::fromTable('entities', 'e');
		$qb->select('MAX(CAST(ps.value AS unsigned)) as max')
			->join('e', 'private_settings', 'ps', 'e.guid = ps.entity_guid')
			->where($qb->compare('ps.name', '=', \ElggPlugin::PRIORITY_SETTING_NAME, ELGG_VALUE_STRING))
			->andWhere($qb->compare('e.type', '=', 'object', ELGG_VALUE_STRING))
			->andWhere($qb->compare('e.subtype', '=', 'plugin', ELGG_VALUE_STRING));

		$data = $this->db->getDataRow($qb);
		if (empty($data)) {
			return 1;
		}

		return max(1, (int) $data->max);
	}

	/**
	 * Returns if a plugin is active for a current site.
	 *
	 * @param string $plugin_id The plugin ID
	 *
	 * @return bool
	 */
	public function isActive(string $plugin_id): bool {
		if (isset($this->boot_plugins) && is_array($this->boot_plugins)) {
			return array_key_exists($plugin_id, $this->boot_plugins);
		}
		
		$plugin = $this->get($plugin_id);
		if (!$plugin) {
			return false;
		}
		
		return check_entity_relationship($plugin->guid, 'active_plugin', 1) instanceof \ElggRelationship;
	}

	/**
	 * Registers lifecycle hooks for all active plugins sorted by their priority
	 *
	 * @note   This is called on every page load. If a plugin is active and problematic, it
	 * will be disabled and a visible error emitted. This does not check the deps system because
	 * that was too slow.
	 *
	 * @return bool
	 */
	public function build() {

		$plugins_path = $this->getPath();

		// temporary disable all plugins if there is a file called 'disabled' in the plugin dir
		if (file_exists("$plugins_path/disabled")) {
			if ($this->session->isAdminLoggedIn() && $this->context->contains('admin')) {
				$this->system_messages->addSuccessMessage($this->translator->translate('plugins:disabled'));
			}

			return false;
		}

		$this->events->registerHandler('plugins_load', 'system', [$this, 'register']);
		$this->events->registerHandler('plugins_boot:before', 'system', [$this, 'boot']);
		$this->events->registerHandler('init', 'system', [$this, 'init']);
		$this->events->registerHandler('ready', 'system', [$this, 'ready']);
		$this->events->registerHandler('upgrade', 'system', [$this, 'upgrade']);
		$this->events->registerHandler('shutdown', 'system', [$this, 'shutdown']);

		return true;
	}

	/**
	 * Autoload plugin classes and files
	 * Register views, translations and custom entity types
	 *
	 * @elgg_event plugins_load system
	 * @return void
	 */
	public function register() {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		if ($this->timer) {
			$this->timer->begin([__METHOD__]);
		}

		/* @var $plugin \ElggPlugin */
		foreach ($plugins as $plugin) {
			try {
				$plugin->register();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		if ($this->timer) {
			$this->timer->end([__METHOD__]);
		}
	}

	/**
	 * Boot the plugins
	 *
	 * @elgg_event plugins_boot:before system
	 * @return void
	 */
	public function boot() {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		if ($this->timer) {
			$this->timer->begin([__METHOD__]);
		}

		foreach ($plugins as $plugin) {
			try {
				$plugin->boot();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		if ($this->timer) {
			$this->timer->end([__METHOD__]);
		}
	}

	/**
	 * Initialize plugins
	 *
	 * @elgg_event init system
	 * @return void
	 */
	public function init() {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		if ($this->timer) {
			$this->timer->begin([__METHOD__]);
		}

		foreach ($plugins as $plugin) {
			try {
				$plugin->init();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		if ($this->timer) {
			$this->timer->end([__METHOD__]);
		}
	}

	/**
	 * Run plugin ready handlers
	 *
	 * @elgg_event ready system
	 * @return void
	 */
	public function ready() {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		if ($this->timer) {
			$this->timer->begin([__METHOD__]);
		}

		foreach ($plugins as $plugin) {
			try {
				$plugin->getBootstrap()->ready();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		if ($this->timer) {
			$this->timer->end([__METHOD__]);
		}
	}

	/**
	 * Run plugin upgrade handlers
	 *
	 * @elgg_event upgrade system
	 * @return void
	 */
	public function upgrade() {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		if ($this->timer) {
			$this->timer->begin([__METHOD__]);
		}

		foreach ($plugins as $plugin) {
			try {
				$plugin->getBootstrap()->upgrade();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		if ($this->timer) {
			$this->timer->end([__METHOD__]);
		}
	}

	/**
	 * Run plugin shutdown handlers
	 *
	 * @elgg_event shutdown system
	 * @return void
	 */
	public function shutdown() {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		if ($this->timer) {
			$this->timer->begin([__METHOD__]);
		}

		foreach ($plugins as $plugin) {
			try {
				$plugin->getBootstrap()->shutdown();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		if ($this->timer) {
			$this->timer->end([__METHOD__]);
		}
	}

	/**
	 * Disable a plugin upon exception
	 *
	 * @param \ElggPlugin $plugin   Plugin entity to disable
	 * @param \Exception  $previous Exception thrown
	 *
	 * @return void
	 */
	protected function disable(\ElggPlugin $plugin, \Exception $previous) {
		$this->getLogger()->log(LogLevel::ERROR, $previous, [
			'context' => [
				'plugin' => $plugin,
			],
		]);

		$disable_plugins = $this->config->auto_disable_plugins;
		if ($disable_plugins === null) {
			$disable_plugins = true;
		}

		if (!$disable_plugins) {
			return;
		}

		try {
			$id = $plugin->getID();
			$plugin->deactivate();

			$msg = $this->translator->translate(
				'PluginException:CannotStart',
				[$id, $plugin->guid, $previous->getMessage()]
			);

			elgg_add_admin_notice("cannot_start $id", $msg);
		} catch (PluginException $ex) {
			$this->getLogger()->log(LogLevel::ERROR, $ex, [
				'context' => [
					'plugin' => $plugin,
				],
			]);
		}
	}

	/**
	 * Returns an ordered list of plugins
	 *
	 * @param string $status The status of the plugins. active, inactive, or all.
	 *
	 * @return \ElggPlugin[]
	 */
	public function find(string $status = 'active'): array {
		if (!$this->db || !$this->config->installed) {
			return [];
		}

		if ($status === 'active' && isset($this->boot_plugins)) {
			// boot_plugins is an already ordered list of plugins
			return array_values($this->boot_plugins);
		}
		
		$volatile_data_name = null;
		$site_guid = 1;

		// grab plugins
		$options = [
			'type' => 'object',
			'subtype' => 'plugin',
			'limit' => false,
			// ORDER BY CAST(ps.value) is super slow. We custom sorting below.
			'order_by' => false,
			// preload private settings because private settings will probably be used, at least priority
			'preload_private_settings' => true,
		];

		switch ($status) {
			case 'active':
				$options['relationship'] = 'active_plugin';
				$options['relationship_guid'] = $site_guid;
				$options['inverse_relationship'] = true;
				
				// shorten callstack
				$volatile_data_name = 'select:value';
				$options['select'] = ['ps.value'];
				$options['private_setting_names'] = [
					\ElggPlugin::PRIORITY_SETTING_NAME,
				];
				break;

			case 'inactive':
				$options['wheres'][] = function (QueryBuilder $qb, $main_alias) use ($site_guid) {
					$subquery = $qb->subquery('entity_relationships', 'active_er');
					$subquery->select('active_er.guid_one')
						->where($qb->compare('active_er.relationship', '=', 'active_plugin', ELGG_VALUE_STRING))
						->andWhere($qb->compare('active_er.guid_two', '=', $site_guid, ELGG_VALUE_GUID));

					return $qb->compare("{$main_alias}.guid", 'NOT IN', $subquery->getSQL());
				};
				break;

			case 'all':
			default:
				break;
		}

		$old_ia = $this->session->setIgnoreAccess(true);
		$plugins = elgg_get_entities($options) ? : [];
		$this->session->setIgnoreAccess($old_ia);

		$result = $this->orderPluginsByPriority($plugins, $volatile_data_name);
		
		if ($status === 'active' && !isset($this->boot_plugins)) {
			// populate local cache if for some reason this is not set yet
			$this->setBootPlugins($result, false);
		}
		
		return $result;
	}
	
	/**
	 * Sorts plugins by priority
	 *
	 * @param \ElggPlugin[] $plugins            Array of plugins
	 * @param string        $volatile_data_name Use an optional volatile data name to retrieve priority
	 *
	 * @return \ElggPlugin[]
	 */
	protected function orderPluginsByPriority($plugins = [], $volatile_data_name = null) {
		$priorities = [];
		$sorted_plugins = [];
				
		foreach ($plugins as $plugin) {
			$priority = null;
			if (!empty($volatile_data_name)) {
				$priority = $plugin->getVolatileData($volatile_data_name);
			}
			
			if (!isset($priority)) {
				$priority = $plugin->getPriority();
			}
			
			$priorities[$plugin->guid] = (int) $priority;
			$sorted_plugins[$plugin->guid] = $plugin;
		}
		
		asort($priorities);
		
		return array_values(array_replace($priorities, $sorted_plugins));
	}

	/**
	 * Reorder plugins to an order specified by the array.
	 * Plugins not included in this array will be appended to the end.
	 *
	 * @note This doesn't use the \ElggPlugin->setPriority() method because
	 *       all plugins are being changed and we don't want it to automatically
	 *       reorder plugins.
	 *
	 * @param array $order An array of plugin ids in the order to set them
	 *
	 * @return bool
	 */
	public function setPriorities(array $order) {
		$name = \ElggPlugin::PRIORITY_SETTING_NAME;

		$plugins = $this->find('any');
		if (empty($plugins)) {
			return false;
		}

		// reindex to get standard counting. no need to increment by 10.
		// though we do start with 1
		$order = array_values($order);

		/* @var \ElggPlugin[] $missing_plugins */
		$missing_plugins = [];

		$priority = 0;
		foreach ($plugins as $plugin) {
			if (!$plugin->isEnabled()) {
				// disabled plugins should not have a priority
				if ($plugin->getPriority() !== null) {
					// remove the priority
					$plugin->removePrivateSetting($name);
				}
				continue;
			}
			
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
	 */
	public function reindexPriorities() {
		return $this->setPriorities([]);
	}

	/**
	 * Set plugin priority and adjust the priorities of other plugins
	 *
	 * @param \ElggPlugin $plugin   Plugin
	 * @param int         $priority New priority
	 *
	 * @return int|false
	 */
	public function setPriority(\ElggPlugin $plugin, $priority) {

		$old_priority = $plugin->getPriority() ? : 1;

		$name = \ElggPlugin::PRIORITY_SETTING_NAME;

		if (!$plugin->setPrivateSetting($name, $priority)) {
			return false;
		}

		if (!$plugin->guid) {
			return false;
		}

		$qb = Update::table('private_settings');
		$qb->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '!=', $plugin->guid, ELGG_VALUE_INTEGER));

		if ($priority > $old_priority) {
			$qb->set('value', "CAST(value AS UNSIGNED) - 1");
			$qb->andWhere($qb->between('CAST(value AS UNSIGNED)', $old_priority, $priority, ELGG_VALUE_INTEGER));
		} else {
			$qb->set('value', "CAST(value AS UNSIGNED) + 1");
			$qb->andWhere($qb->between('CAST(value AS UNSIGNED)', $priority, $old_priority, ELGG_VALUE_INTEGER));
		}

		if (!$this->db->updateData($qb)) {
			return false;
		}

		return $priority;
	}
}
