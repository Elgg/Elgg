<?php

namespace Elgg\Plugins;

use Elgg\PluginBootstrapInterface;
use Elgg\Router\Route;
use Elgg\UnitTestCase;

class StaticConfigTest extends UnitTestCase {

	use PluginTesting;

	/**
	 * @var \ElggPlugin
	 */
	protected $plugin;

	public function up() {
		$this->plugin = $this->startPlugin();
	}

	public function down() {

	}

	public function testEntityRegistration() {

		$entities = $this->plugin->getStaticConfig('entities');
		if (empty($entities)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($entities);

		foreach ($entities as $entity) {
			$this->assertNotEmpty($entity['type']);
			$this->assertNotEmpty($entity['subtype']);
			if (isset($entity['class'])) {
				$this->assertTrue(class_exists($entity['class']));
			}
		}
	}

	public function testActionsRegistration() {

		$actions = $this->plugin->getStaticConfig('actions');
		if (empty($actions)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($actions);
		
		$root_path = rtrim($this->getPath(), '/');

		foreach ($actions as $action => $action_spec) {
			$this->assertIsArray($action_spec);

			if (isset($action_spec['controller'])) {
				$controller = elgg_extract('controller', $action_spec);
				$this->assertTrue(_elgg_services()->handlers->isCallable($controller));
			} else if (isset($action_spec['handler'])) {
				$handler = elgg_extract('handler', $action_spec);
				$this->assertTrue(_elgg_services()->handlers->isCallable($handler));
			} else {
				$filename = elgg_extract('filename', $action_spec, "{$root_path}/actions/{$action}.php");
				$this->assertFileExists($filename);
			}
			
			if (isset($action_spec['access'])) {
				$this->assertTrue(in_array($action_spec['access'], ['public', 'loggedin', 'admin']));
			}
		}
	}

	/**
	 * @group Routing
	 */
	public function testRouteRegistrations() {

		$routes = $this->plugin->getStaticConfig('routes');
		if (empty($routes)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($routes);
		
		foreach ($routes as $name => $conf) {
			if (elgg_extract('controller', $conf)) {
				$this->assertTrue(_elgg_services()->handlers->isCallable($conf['controller']));
			} else if (elgg_extract('handler', $conf)) {
				$this->assertTrue(_elgg_services()->handlers->isCallable($conf['handler']));
			} else if (elgg_extract('resource', $conf)) {
				$view = "resources/{$conf['resource']}";
				$this->assertTrue(elgg_view_exists($view), "Resource $view for route $name does not exist");
			}

			elgg_register_route($name, $conf);
			$this->assertInstanceOf(Route::class, _elgg_services()->routeCollection->get($name));
			elgg_unregister_route($name);
		}
	}
	
	public function testBootstrapRegistration() {
		
		$bootstrap = $this->plugin->getStaticConfig('bootstrap');
		if (empty($bootstrap)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsString($bootstrap);
		$this->assertTrue(class_exists($bootstrap));
		$this->assertTrue(is_a($bootstrap, PluginBootstrapInterface::class, true));
	}
	
	public function testHooksRegistration() {
		
		$hooks = $this->plugin->getStaticConfig('hooks');
		if (empty($hooks)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($hooks);
		
		foreach ($hooks as $name => $types) {
			$this->assertIsString($name);
			$this->assertIsArray($types);
			
			foreach ($types as $type => $callbacks) {
				$this->assertIsString($type);
				$this->assertIsArray($callbacks);
				
				foreach ($callbacks as $callback => $hook_specs) {
					$this->assertIsString($callback);
					$this->assertIsArray($hook_specs);
					
					if (isset($hook_specs['priority'])) {
						$this->assertIsInt($hook_specs['priority']);
					}
					
					if (isset($hook_specs['unregister'])) {
						$this->assertIsBool($hook_specs['unregister']);
					}
				}
			}
		}
	}
	
	public function testEventsRegistration() {
		
		$events = $this->plugin->getStaticConfig('events');
		if (empty($events)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($events);
		
		foreach ($events as $name => $types) {
			$this->assertIsString($name);
			$this->assertIsArray($types);
			
			foreach ($types as $type => $callbacks) {
				$this->assertIsString($type);
				$this->assertIsArray($callbacks);
				
				foreach ($callbacks as $callback => $event_specs) {
					$this->assertIsString($callback);
					$this->assertIsArray($event_specs);
					
					if (isset($event_specs['priority'])) {
						$this->assertIsInt($event_specs['priority']);
					}
					
					if (isset($event_specs['unregister'])) {
						$this->assertIsBool($event_specs['unregister']);
					}
				}
			}
		}
	}
	
	public function testViewsRegistration() {
		
		$views = $this->plugin->getStaticConfig('views');
		if (empty($views)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($views);
		
		foreach ($views as $view_type => $view_specs) {
			$this->assertIsString($view_type);
			$this->assertIsArray($view_specs);
			
			foreach ($view_specs as $view => $location) {
				$this->assertIsString($view);
				$this->assertIsString($location);
			}
		}
	}
	
	public function testWidgetRegistration() {
		
		$widgets = $this->plugin->getStaticConfig('widgets');
		if (empty($widgets)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($widgets);
		
		foreach ($widgets as $widget_id => $widget_definition) {
			$this->assertIsArray($widget_definition);
			
			$actual_widget_id = $widget_definition['id'] ?? $widget_id;
			
			$this->assertIsString($actual_widget_id);
		}
	}
	
	public function testViewExtensionsRegistration() {
		
		$view_extensions = $this->plugin->getStaticConfig('view_extensions');
		if (empty($view_extensions)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($view_extensions);
		
		foreach ($view_extensions as $base_view => $extensions) {
			$this->assertIsString($base_view);
			$this->assertIsArray($extensions);
			
			foreach ($extensions as $extension_view => $extension_specs) {
				$this->assertIsString($extension_view);
				$this->assertIsArray($extension_specs);
				
				if (isset($extension_specs['unextend'])) {
					$this->assertIsBool($extension_specs['unextend']);
				}
				
				if (isset($extension_specs['priority'])) {
					$this->assertIsInt($extension_specs['priority']);
				}
			}
		}
	}
	
	public function testGroupToolsRegistration() {
		
		$tools = $this->plugin->getStaticConfig('group_tools');
		if (empty($tools)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($tools);
		
		foreach ($tools as $tool_id => $tool_specs)  {
			$this->assertIsString($tool_id);
			$this->assertIsArray($tool_specs);
			
			if (isset($tool_specs['unregister'])) {
				$this->assertIsBool($tool_specs['unregister']);
			}
		}
	}
	
	public function testViewOptionsRegistration() {
		
		$view_options = $this->plugin->getStaticConfig('view_options');
		if (empty($view_options)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($view_options);
		
		foreach ($view_options as $view_name => $options) {
			$this->assertIsString($view_name);
			$this->assertIsArray($options);
			
			if (isset($options['ajax'])) {
				$this->assertIsBool($options['ajax']);
			}
			
			if (isset($options['simplecache'])) {
				$this->assertIsBool($options['simplecache']);
			}
		}
	}
	
	public function testNotificationRegistration() {
		
		$notifications = $this->plugin->getStaticConfig('notifications');
		if (empty($notifications)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($notifications);
		
		foreach ($notifications as $type => $subtypes) {
			$this->assertIsString($type);
			$this->assertIsArray($subtypes);
			
			foreach ($subtypes as $subtype => $actions) {
				$this->assertIsString($subtype);
				$this->assertIsArray($actions);
				
				foreach ($actions as $action => $callback) {
					$this->assertIsString($action);
					
					if (is_string($callback)) {
						$this->assertTrue(is_a($callback, \Elgg\Notifications\NotificationEventHandler::class, true));
					} else {
						$this->assertIsBool($callback);
					}
				}
			}
		}
	}
	
	public function testDefaultPluginSettingRegistration() {
		
		$settings = $this->plugin->getStaticConfig('settings');
		if (empty($settings)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($settings);
		
		foreach ($settings as $settings_name => $default_value) {
			$this->assertIsString($settings_name);
			
			if (isset($default_value)) {
				// prevent failure if the default is 'null'
				$this->assertIsScalar($default_value);
			}
		}
	}
	
	public function testDefaultPluginUserSettingRegistration() {
		
		$user_settings = $this->plugin->getStaticConfig('user_settings');
		if (empty($user_settings)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($user_settings);
		
		foreach ($user_settings as $settings_name => $default_value) {
			$this->assertIsString($settings_name);
			
			if (isset($default_value)) {
				// prevent failure if the default is 'null'
				$this->assertIsScalar($default_value);
			}
		}
	}
	
	public function testCliCommandsRegistration() {
		
		$commands = $this->plugin->getStaticConfig('cli_commands');
		if (empty($commands)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($commands);
		
		foreach ($commands as $command) {
			$this->assertIsString($command);
			$this->assertTrue(is_a($command, \Elgg\Cli\BaseCommand::class, true));
		}
	}
}
