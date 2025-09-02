<?php

namespace Elgg\Database;

use Elgg\Cache\PluginsCache;
use Elgg\Config;
use Elgg\Context;
use Elgg\Database;
use Elgg\EventsService;
use Elgg\Exceptions\PluginException;
use Elgg\Http\Request;
use Elgg\I18n\Translator;
use Elgg\Invoker;
use Elgg\SessionManagerService;
use Elgg\SystemMessagesService;
use Elgg\Traits\Debug\Profilable;
use Elgg\Traits\Loggable;
use Elgg\ViewsService;
use Psr\Log\LogLevel;

/**
 * Persistent, installation-wide key-value storage.
 *
 * @internal
 * @since 1.10.0
 */
class Plugins {

	use Profilable;
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
		'externalpages',
		'file',
		'friends',
		'friends_collections',
		'garbagecollector',
		'groups',
		'invitefriends',
		'likes',
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
		'theme_sandbox',
		'thewire',
		'uservalidationbyemail',
		'web_services',
	];

	/**
	 * @var \ElggPlugin[]
	 */
	protected ?array $boot_plugins;
	
	protected Context $context;

	/**
	 * Constructor
	 *
	 * @param PluginsCache          $cache           Plugins cache
	 * @param Database              $db              Database
	 * @param SessionManagerService $session_manager Session
	 * @param EventsService         $events          Events
	 * @param Translator            $translator      Translator
	 * @param ViewsService          $views           Views service
	 * @param Config                $config          Config
	 * @param SystemMessagesService $system_messages System messages
	 * @param Invoker               $invoker         Invoker
	 * @param Request               $request         Context
	 */
	public function __construct(
		protected PluginsCache $cache,
		protected Database $db,
		protected SessionManagerService $session_manager,
		protected EventsService $events,
		protected Translator $translator,
		protected ViewsService $views,
		protected Config $config,
		protected SystemMessagesService $system_messages,
		protected Invoker $invoker,
		Request $request
	) {
		$this->context = $request->getContextStack();
	}

	/**
	 * Get the plugin path for this installation, ending with slash.
	 *
	 * @return string
	 */
	public function getPath(): string {
		return $this->config->plugins_path;
	}

	/**
	 * Set the list of active plugins according to the boot data cache
	 *
	 * @param \ElggPlugin[]|null $plugins       Set of active plugins
	 * @param bool               $order_plugins Make sure plugins are saved in the correct order (set to false if provided plugins are already sorted)
	 *
	 * @return void
	 */
	public function setBootPlugins(?array $plugins = null, bool $order_plugins = true): void {
		$this->cache->clear();
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
			
			// make sure the plugin is in the entity and plugin cache
			$plugin->cache();
			
			// can't use ElggEntity::cache() as it conflict with metadata preloading
			$this->cache->save($plugin_id, $plugin);
		}
	}
	
	/**
	 * Returns a list of plugin directory names from a base directory.
	 *
	 * @param null|string $dir A dir to scan for plugins. Defaults to config's plugins_path.
	 *                         Must have a trailing slash.
	 *
	 * @return array Array of directory names (not full paths)
	 */
	public function getDirsInDir(?string $dir = null): array {
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
			if (!str_starts_with($plugin_dir, '.') && is_dir($dir . $plugin_dir)) {
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
		// ignore access in case this is called with no admin logged in - needed for creating plugins perhaps?
		// show hidden entities so that we can enable them if appropriate
		return $this->invoker->call(ELGG_IGNORE_ACCESS | ELGG_SHOW_DISABLED_ENTITIES, function() {
			$mod_dir = $this->getPath();
			
			$known_plugins = $this->find('all');
			
			// keeps track if reindexing is needed
			$reindex = false;
			
			// map paths to indexes
			$id_map = [];
			$latest_priority = 0;
			foreach ($known_plugins as $i => $plugin) {
				// if the ID is wrong, delete the plugin because we can never load it.
				$id = $plugin->getID() . $plugin->guid;
				if (!$id) {
					$plugin->delete();
					unset($known_plugins[$i]);
					continue;
				}
				
				$id_map[$plugin->getID()] = $i;
				
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
					\ElggPlugin::fromId($plugin_id);
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
				$plugin->deleteMetadata(\ElggPlugin::PRIORITY_SETTING_NAME);
				
				$plugin->disable();
			}
			
			if ($reindex) {
				$this->reindexPriorities();
			}
			
			return true;
		});
	}

	/**
	 * Returns an \ElggPlugin object with the path $path.
	 *
	 * @param string $plugin_id The id (dir name) of the plugin. NOT the guid.
	 *
	 * @return \ElggPlugin|null
	 */
	public function get(string $plugin_id): ?\ElggPlugin {
		if (empty($plugin_id)) {
			return null;
		}
		
		$plugin = $this->cache->load($plugin_id);
		if ($plugin instanceof \ElggPlugin) {
			return $plugin;
		}
		
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
		
		if (empty($plugins)) {
			return null;
		}
		
		$plugin = $plugins[0];
		
		$this->cache->save($plugin_id, $plugin);
		
		return $plugin;
	}

	/**
	 * Returns the highest priority of the plugins
	 *
	 * @return int
	 */
	public function getMaxPriority(): int {
		$qb = Select::fromTable(EntityTable::TABLE_NAME, EntityTable::DEFAULT_JOIN_ALIAS);
		$qb->select('MAX(CAST(md.value AS unsigned)) as max')
			->join($qb->getTableAlias(), MetadataTable::TABLE_NAME, 'md', "{$qb->getTableAlias()}.guid = md.entity_guid")
			->where($qb->compare('md.name', '=', \ElggPlugin::PRIORITY_SETTING_NAME, ELGG_VALUE_STRING))
			->andWhere($qb->compare("{$qb->getTableAlias()}.type", '=', 'object', ELGG_VALUE_STRING))
			->andWhere($qb->compare("{$qb->getTableAlias()}.subtype", '=', 'plugin', ELGG_VALUE_STRING));

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
		if (!$plugin instanceof \ElggPlugin) {
			return false;
		}
		
		return $plugin->hasRelationship(1, 'active_plugin');
	}

	/**
	 * Registers lifecycle events for all active plugins sorted by their priority
	 *
	 * @note   This is called on every page load. If a plugin is active and problematic, it
	 * will be disabled and a visible error emitted. This does not check the deps system because
	 * that was too slow.
	 *
	 * @return bool
	 */
	public function build(): bool {
		$plugins_path = $this->getPath();

		// temporary disable all plugins if there is a file called 'disabled' in the plugin dir
		if (file_exists("{$plugins_path}/disabled")) {
			if ($this->session_manager->isAdminLoggedIn() && $this->context->contains('admin')) {
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
	 * @return void
	 */
	public function register(): void {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		$this->beginTimer([__METHOD__]);

		foreach ($plugins as $plugin) {
			try {
				$plugin->register();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		$this->endTimer([__METHOD__]);
	}

	/**
	 * Boot the plugins
	 *
	 * @return void
	 */
	public function boot(): void {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		$this->beginTimer([__METHOD__]);

		foreach ($plugins as $plugin) {
			try {
				$plugin->boot();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		$this->endTimer([__METHOD__]);
	}

	/**
	 * Initialize plugins
	 *
	 * @return void
	 */
	public function init(): void {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		$this->beginTimer([__METHOD__]);

		foreach ($plugins as $plugin) {
			try {
				$plugin->init();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		$this->endTimer([__METHOD__]);
	}

	/**
	 * Run plugin ready handlers
	 *
	 * @return void
	 */
	public function ready(): void {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		$this->beginTimer([__METHOD__]);

		foreach ($plugins as $plugin) {
			try {
				$plugin->getBootstrap()->ready();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		$this->endTimer([__METHOD__]);
	}

	/**
	 * Run plugin upgrade handlers
	 *
	 * @return void
	 */
	public function upgrade(): void {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		$this->beginTimer([__METHOD__]);

		foreach ($plugins as $plugin) {
			try {
				$plugin->getBootstrap()->upgrade();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		$this->endTimer([__METHOD__]);
	}

	/**
	 * Run plugin shutdown handlers
	 *
	 * @return void
	 */
	public function shutdown(): void {
		$plugins = $this->find('active');
		if (empty($plugins)) {
			return;
		}

		$this->beginTimer([__METHOD__]);

		foreach ($plugins as $plugin) {
			try {
				$plugin->getBootstrap()->shutdown();
			} catch (\Exception $ex) {
				$this->disable($plugin, $ex);
			}
		}

		$this->endTimer([__METHOD__]);
	}

	/**
	 * Disable a plugin upon exception
	 *
	 * @param \ElggPlugin $plugin   Plugin entity to disable
	 * @param \Exception  $previous Exception thrown
	 *
	 * @return void
	 */
	protected function disable(\ElggPlugin $plugin, \Exception $previous): void {
		$this->getLogger()->log(LogLevel::ERROR, $previous, [
			'context' => [
				'plugin' => $plugin,
			],
		]);

		if (!$this->config->auto_disable_plugins) {
			return;
		}

		try {
			$id = $plugin->getID();
			$plugin->deactivate();

			$msg = $this->translator->translate(
				'PluginException:CannotStart',
				[$id, $plugin->guid, $previous->getMessage()]
			);

			elgg_add_admin_notice("cannot_start {$id}", $msg);
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
			'order_by' => false,
		];

		switch ($status) {
			case 'active':
				$options['relationship'] = 'active_plugin';
				$options['relationship_guid'] = $site_guid;
				$options['inverse_relationship'] = true;
				
				// shorten callstack
				$volatile_data_name = 'select:value';
				$options['select'] = [MetadataTable::DEFAULT_JOIN_ALIAS . '.value'];
				$options['metadata_names'] = [
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

		$plugins = $this->invoker->call(ELGG_IGNORE_ACCESS, function () use ($options) {
			return elgg_get_entities($options) ?: [];
		});
		
		$result = $this->orderPluginsByPriority($plugins, $volatile_data_name);
		
		if ($status === 'active' && !isset($this->boot_plugins)) {
			// populate local cache if for some reason this is not set yet
			$this->setBootPlugins($result, false);
		}
		
		foreach ($plugins as $plugin) {
			// can't use ElggEntity::cache() as it conflict with metadata preloading
			$this->cache->save($plugin->getID(), $plugin);
		}
		
		return $result;
	}
	
	/**
	 * Sorts plugins by priority
	 *
	 * @param \ElggPlugin[] $plugins            Array of plugins
	 * @param null|string   $volatile_data_name Use an optional volatile data name to retrieve priority
	 *
	 * @return \ElggPlugin[]
	 */
	protected function orderPluginsByPriority(array $plugins = [], ?string $volatile_data_name = null): array {
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
	public function setPriorities(array $order): bool {
		$name = \ElggPlugin::PRIORITY_SETTING_NAME;

		$plugins = $this->find('all');
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
					unset($plugin->$name);
				}
				
				continue;
			}
			
			$plugin_id = $plugin->getID();

			if (!in_array($plugin_id, $order)) {
				$missing_plugins[] = $plugin;
				continue;
			}

			$priority = array_search($plugin_id, $order) + 1;

			if (!$plugin->setMetadata($name, $priority)) {
				return false;
			}
		}

		// set the missing plugins' priorities
		if (empty($missing_plugins)) {
			return true;
		}

		foreach ($missing_plugins as $plugin) {
			$priority++;
			if (!$plugin->setMetadata($name, $priority)) {
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
	public function reindexPriorities(): bool {
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
	public function setPriority(\ElggPlugin $plugin, int $priority): int|false {
		$old_priority = $plugin->getPriority() ?: 1;

		$name = \ElggPlugin::PRIORITY_SETTING_NAME;

		if (!$plugin->setMetadata($name, $priority)) {
			return false;
		}

		if (!$plugin->guid) {
			return false;
		}

		$qb = Update::table(MetadataTable::TABLE_NAME);
		$qb->where($qb->compare('name', '=', $name, ELGG_VALUE_STRING))
			->andWhere($qb->compare('entity_guid', '!=', $plugin->guid, ELGG_VALUE_INTEGER));

		if ($priority > $old_priority) {
			$qb->set('value', 'CAST(value AS UNSIGNED) - 1');
			$qb->andWhere($qb->between('CAST(value AS UNSIGNED)', $old_priority, $priority, ELGG_VALUE_INTEGER));
		} else {
			$qb->set('value', 'CAST(value AS UNSIGNED) + 1');
			$qb->andWhere($qb->between('CAST(value AS UNSIGNED)', $priority, $old_priority, ELGG_VALUE_INTEGER));
		}

		if (!$this->db->updateData($qb)) {
			return false;
		}

		return $priority;
	}
}
