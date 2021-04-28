<?php

namespace Elgg\Mocks\Database;

use Elgg\BaseTestCase;
use Elgg\Database\Plugins as DbPlugins;
use ElggPlugin;
use Elgg\Testing;

/**
 * @group Plugins
 */
class Plugins extends DbPlugins {

	use Testing;
	
	/**
	 * @var ElggPlugin[]
	 */
	protected $_plugins = [];

	public static $managed_plugins = [
		'activity',
		'blog',
		'bookmarks',
		'ckeditor',
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
		'thewire',
		'uservalidationbyemail',
		'web_services',

		// these plugins need to be activated after a previous activated plugin
		'custom_index',
		'embed',
	];

	public function get(string $plugin_id): ?\ElggPlugin {
		$plugin = parent::get($plugin_id);
		if ($plugin) {
			return $plugin;
		}

		$plugin = BaseTestCase::$_instance->createObject([
			'owner_guid' => 1,
			'container_guid' => 1,
			'subtype' => 'plugin',
			'title' => $plugin_id,
		]);

		return $plugin;
	}

	public function find(string $status = 'active'): array {
		return $this->_plugins;
	}

	public function generateEntities(): bool {
		parent::generateEntities();
		$this->addTestingPlugin(ElggPlugin::fromId('test_plugin', $this->normalizeTestFilePath('mod/')));
		return true;
	}

	public function addTestingPlugin(\ElggPlugin $plugin): void {
		$this->_plugins[$plugin->getID()] = $plugin;
	}

	public function isActive(string $plugin_id): bool {
		return array_key_exists($plugin_id, $this->_plugins);
	}

	public function setPriority(ElggPlugin $plugin, $priority) {

		$old_priority = $plugin->getPriority();

		foreach ($this->find() as $sibling) {
			if ($sibling->guid == $plugin->guid) {
				continue;
			}

			$sibling_priority = $sibling->getPriority();
			if ($sibling_priority <= $old_priority) {
				$sibling_priority--;
			} else {
				$sibling_priority++;
			}

			$sibling->setPrivateSetting('elgg:internal:priority', $sibling_priority);
		}

		$plugin->setPrivateSetting('elgg:internal:priority', $priority);

		return $priority;
	}
}
