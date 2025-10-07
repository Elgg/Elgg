<?php

namespace Elgg\Plugins;

use Elgg\PluginBootstrapInterface;
use Elgg\PluginsIntegrationTestCase;
use Elgg\Router\Route;

class StaticConfigIntegrationTest extends PluginsIntegrationTestCase {
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testEntityRegistration(\ElggPlugin $plugin) {
		
		$entities = $plugin->getStaticConfig('entities');
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
			
			if (isset($entity['capabilities'])) {
				$this->assertIsArray($entity['capabilities']);
				foreach ($entity['capabilities'] as $capability => $value) {
					$this->assertIsString($capability);
					$this->assertIsBool($value);
				}
			}
		}
	}
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testActionsRegistration(\ElggPlugin $plugin) {
		$actions = $plugin->getStaticConfig('actions');
		if (empty($actions)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($actions);
		
		$root_path = rtrim($plugin->getPath(), '/');
		
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
				$this->assertTrue(in_array($action_spec['access'], ['public', 'logged_in', 'logged_out', 'admin']));
			}
			
			if (isset($action_spec['middleware'])) {
				$this->assertIsArray($action_spec['middleware']);
			}
		}
	}
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testRouteRegistrations(\ElggPlugin $plugin) {
		$routes = $plugin->getStaticConfig('routes');
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

			$required_plugins = (array) elgg_extract('required_plugins', $conf);
			$validate_route = true;
			foreach ($required_plugins as $plugin) {
				if (!elgg_is_active_plugin($plugin)) {
					$validate_route = false;
					break;
				}
			}

			elgg_register_route($name, $conf);

			if ($validate_route) {
				$this->assertInstanceOf(Route::class, _elgg_services()->routeCollection->get($name));
			} else {
				$this->assertNull(_elgg_services()->routeCollection->get($name));
			}

			elgg_unregister_route($name);
		}
	}
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testBootstrapRegistration(\ElggPlugin $plugin) {
		$bootstrap = $plugin->getStaticConfig('bootstrap');
		if (empty($bootstrap)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsString($bootstrap);
		$this->assertTrue(class_exists($bootstrap));
		$this->assertTrue(is_a($bootstrap, PluginBootstrapInterface::class, true));
	}
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testHooksRegistration(\ElggPlugin $plugin) {
		$hooks = $plugin->getStaticConfig('hooks');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testEventsRegistration(\ElggPlugin $plugin) {
		$events = $plugin->getStaticConfig('events');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testViewsRegistration(\ElggPlugin $plugin) {
		$views = $plugin->getStaticConfig('views');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testWidgetRegistration(\ElggPlugin $plugin) {
		$widgets = $plugin->getStaticConfig('widgets');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testViewExtensionsRegistration(\ElggPlugin $plugin) {
		$view_extensions = $plugin->getStaticConfig('view_extensions');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testGroupToolsRegistration(\ElggPlugin $plugin) {
		$tools = $plugin->getStaticConfig('group_tools');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testViewOptionsRegistration(\ElggPlugin $plugin) {
		$view_options = $plugin->getStaticConfig('view_options');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testNotificationRegistration(\ElggPlugin $plugin) {
		$notifications = $plugin->getStaticConfig('notifications');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testDefaultPluginSettingRegistration(\ElggPlugin $plugin) {
		$settings = $plugin->getStaticConfig('settings');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testDefaultPluginUserSettingRegistration(\ElggPlugin $plugin) {
		$user_settings = $plugin->getStaticConfig('user_settings');
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
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testCliCommandsRegistration(\ElggPlugin $plugin) {
		$commands = $plugin->getStaticConfig('cli_commands');
		if (empty($commands)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($commands);
		
		foreach ($commands as $command) {
			$this->assertIsString($command);
			$this->assertTrue(is_a($command, \Elgg\Cli\BaseCommand::class, true));
		}
	}
	
	/**
	 * @dataProvider activePluginsProvider
	 */
	public function testUpgradesRegistration(\ElggPlugin $plugin) {
		$upgrades = $plugin->getStaticConfig('upgrades');
		if (empty($upgrades)) {
			$this->markTestSkipped();
		}
		
		$this->assertIsArray($upgrades);
		
		foreach ($upgrades as $upgrade) {
			$this->assertIsString($upgrade);
			$this->assertTrue(class_exists($upgrade), "Upgrade class {$upgrade} does not exist");
			$this->assertTrue(is_subclass_of($upgrade, \Elgg\Upgrade\Batch::class), "Upgrade class {$upgrade} is not a correct extension of a \Elgg\Upgrade\Batch base class");
		}
	}
}

