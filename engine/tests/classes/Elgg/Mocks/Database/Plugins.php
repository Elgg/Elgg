<?php

namespace Elgg\Mocks\Database;

use Elgg\BaseTestCase;
use Elgg\Database\Plugins as DbPlugins;
use ElggPlugin;

/**
 * @group Plugins
 */
class Plugins extends DbPlugins {

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
		'diagnostics',
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

	public function get($plugin_id) {
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

	public function find($status = 'active') {
		return $this->_plugins;
	}

	public function addTestingPlugin(ElggPlugin $plugin) {
		$this->_plugins[] = $plugin;
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
