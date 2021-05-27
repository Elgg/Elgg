<?php

namespace Elgg\Plugin;

use Elgg\IntegrationTestCase;

class ElggPluginStaticConfigIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggPlugin
	 */
	protected $plugin;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->createApplication([
			'isolate' => true,
		]);
		
		$this->plugin = \ElggPlugin::fromId('static_config', $this->normalizeTestFilePath('mod/'));
		$this->plugin->autoload();
		
		_elgg_services()->events->backup();
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		_elgg_services()->events->restore();
	}
	
	/**
	 * Get a protected/private function to call
	 *
	 * @param string $method
	 *
	 * @return \ReflectionMethod
	 */
	protected function getReflectorMethod(string $method): \ReflectionMethod {
		$reflector = new \ReflectionClass($this->plugin);
		$method = $reflector->getMethod($method);
		$method->setAccessible(true);
		
		return $method;
	}
	
	protected function callReflectorMethod(string $method) {
		$method = $this->getReflectorMethod($method);
		
		return $method->invoke($this->plugin);
	}
	
	public function testBootstrapRegistration() {
		$this->assertInstanceOf(\Elgg\StaticConfig\Bootstrap::class, $this->plugin->getBootstrap());
	}
	
	/**
	 * @dataProvider viewsRegistrationProvider
	 */
	public function testViewsRegistration(string $view_name, string $expected_view_output) {
		$this->assertFalse(elgg_view_exists($view_name));
		
		elgg_set_config('system_cache_loaded', false);
		
		$this->callReflectorMethod('registerViews');
		
		$this->assertTrue(elgg_view_exists($view_name));
		$this->assertEquals($expected_view_output, elgg_view($view_name));
	}
		
	public function viewsRegistrationProvider() {
		return [
			['custom_view', 'custom view'],
			['custom_directory/view1', 'view1'],
			['custom_directory/view2', 'view2 actual'],
		];
	}
	
	public function testEntitiesRegistration() {
		$this->assertFalse(is_registered_entity_type('object', \StaticConfigObject::SUBTYPE));
		
		$this->callReflectorMethod('registerEntities');
		
		$this->assertTrue(is_registered_entity_type('object', \StaticConfigObject::SUBTYPE));
	}
	
	public function testDeActivateEntities() {
		$class = elgg_get_entity_class('object', \StaticConfigObject::SUBTYPE);
		
		$this->assertNotEquals(\StaticConfigObject::class, $class);
		
		$this->callReflectorMethod('activateEntities');
		
		$class = elgg_get_entity_class('object', \StaticConfigObject::SUBTYPE);
		
		$this->assertEquals(\StaticConfigObject::class, $class);
		
		$this->callReflectorMethod('deactivateEntities');
		
		$class = elgg_get_entity_class('object', \StaticConfigObject::SUBTYPE);
		
		$this->assertNotEquals(\StaticConfigObject::class, $class);
	}
	
	public function testActionsRegistration() {
		$this->assertFalse(elgg_action_exists('static_config/autodetect'));
		$this->assertFalse(elgg_action_exists('static_config/custom_file'));
		$this->assertFalse(elgg_action_exists('static_config/controller'));
		
		$this->callReflectorMethod('registerActions');
		
		$this->assertTrue(elgg_action_exists('static_config/autodetect'));
		$this->assertTrue(elgg_action_exists('static_config/custom_file'));
		$this->assertTrue(elgg_action_exists('static_config/controller'));
		
		$route1 = _elgg_services()->routes->get('action:static_config/autodetect');
		$this->assertEquals($this->normalizeTestFilePath('mod/static_config/actions/static_config/autodetect.php'), $route1->getDefault('_file'));
		$this->assertIsArray($route1->getDefault('_middleware'));
		$this->assertContains(\Elgg\Router\Middleware\Gatekeeper::class, $route1->getDefault('_middleware'));
		
		$route2 = _elgg_services()->routes->get('action:static_config/custom_file');
		$this->assertEquals($this->normalizeTestFilePath('mod/static_config/actions/custom_file.php'), \Elgg\Project\Paths::sanitize($route2->getDefault('_file'), false));
		$this->assertIsArray($route2->getDefault('_middleware'));
		$this->assertNotContains(\Elgg\Router\Middleware\Gatekeeper::class, $route2->getDefault('_middleware'));
		
		$route3 = _elgg_services()->routes->get('action:static_config/controller');
		$this->assertEquals(\Elgg\StaticConfig\ActionController::class, $route3->getDefault('_controller'));
		$this->assertIsArray($route1->getDefault('_middleware'));
		$this->assertContains(\Elgg\Router\Middleware\AdminGatekeeper::class, $route3->getDefault('_middleware'));
	}
	
	public function testRoutesRegistration() {
		$this->assertFalse(elgg_route_exists('default:object:static_config_subtype'));
		
		$this->callReflectorMethod('registerRoutes');
		
		$this->assertTrue(elgg_route_exists('default:object:static_config_subtype'));
	}
	
	public function testWidgetsRegistration() {
		$widgets = _elgg_services()->widgets;
		
		$this->assertFalse($widgets->validateType('static_config'));
		
		$this->callReflectorMethod('registerWidgets');
		
		$this->assertTrue($widgets->validateType('static_config'));
		$this->assertTrue($widgets->validateType('static_config', 'profile'));
		$this->assertFalse($widgets->validateType('static_config', 'dashboard'));
	}
	
	public function testHooksRegistration() {
		$hooks = _elgg_services()->hooks;
		
		$hooks->registerHandler('prevent', 'something', '\Elgg\Plugin\ElggPluginStaticConfigIntegrationTest::hookCallback');
		
		$ordered = $hooks->getOrderedHandlers('prevent', 'something');
		$this->assertIsArray($ordered);
		$this->assertCount(1, $ordered);
		
		$this->callReflectorMethod('registerHooks');
		
		$ordered = $hooks->getOrderedHandlers('prevent', 'something');
		$this->assertIsArray($ordered);
		$this->assertCount(2, $ordered);
		
		$this->assertNotContains('\Elgg\Plugin\ElggPluginStaticConfigIntegrationTest::hookCallback', $ordered);
		$this->assertEquals([
			'\Elgg\StaticConfig\HookCallback',
			'\Elgg\StaticConfig\HookCallback::highPriority',
		], $ordered);
	}
	
	public function testEventsRegistration() {
		$events = _elgg_services()->events;
		
		$events->registerHandler('do', 'something', '\Elgg\Plugin\ElggPluginStaticConfigIntegrationTest::eventCallback');
		
		$ordered = $events->getOrderedHandlers('do', 'something');
		$this->assertIsArray($ordered);
		$this->assertCount(1, $ordered);
		
		$this->callReflectorMethod('registerEvents');
		
		$ordered = $events->getOrderedHandlers('do', 'something');
		$this->assertIsArray($ordered);
		$this->assertCount(2, $ordered);
		
		$this->assertNotContains('\Elgg\Plugin\ElggPluginStaticConfigIntegrationTest::eventCallback', $ordered);
		$this->assertEquals([
			'\Elgg\StaticConfig\EventCallback',
			'\Elgg\StaticConfig\EventCallback::highPriority',
		], $ordered);
	}
	
	public function testViewExtensionsRegistration() {
		$views = _elgg_services()->views;
		
		$this->callReflectorMethod('registerViews');
		
		$views->extendView('static_config/view', 'static_config/unextend');
		
		$view_list = $views->getViewList('static_config/view');
		$this->assertIsArray($view_list);
		$this->assertCount(2, $view_list);
		
		$this->callReflectorMethod('registerViewExtensions');
		
		$view_list = $views->getViewList('static_config/view');
		$this->assertIsArray($view_list);
		$this->assertCount(3, $view_list);
		
		$this->assertEquals([
			100 => 'static_config/extension100',
			\Elgg\ViewsService::BASE_VIEW_PRIORITY => 'static_config/view',
			900 => 'static_config/extension900',
		], $view_list);
	}
	
	public function testGroupToolsRegistration() {
		$group_tools = _elgg_services()->group_tools;
		
		$group_tools->register('static_config_unregister', []);
		
		$this->callReflectorMethod('registerGroupTools');
		
		$this->assertEmpty($group_tools->get('static_config_unregister'));
		$this->assertInstanceOf(\Elgg\Groups\Tool::class, $group_tools->get('static_config'));
	}
	
	public function testViewOptionsRegistration() {
		$ajax = _elgg_services()->ajax;
		$views = _elgg_services()->views;
		
		$this->callReflectorMethod('registerViews');
		
		$ajax->registerView('static_config/view');
		
		$this->assertNotContains('static_config/viewoptions', $ajax->getViews());
		$this->assertFalse($views->isCacheableView('static_config/viewoptions'));
		
		$this->callReflectorMethod('registerViewOptions');
		
		$this->assertNotContains('static_config/view', $ajax->getViews());
		$this->assertContains('static_config/viewoptions', $ajax->getViews());
		$this->assertTrue($views->isCacheableView('static_config/viewoptions'));
	}
	
	public function testNotificationsRegistration() {
		$notifications = _elgg_services()->notifications;
		
		$notifications->registerEvent('object', 'static_config_subtype', ['update']);
		
		$events = $notifications->getEvents();
		$this->assertArrayHasKey('object', $events);
		$this->assertArrayHasKey('static_config_subtype', $events['object']);
		$this->assertArrayNotHasKey('create', $events['object']['static_config_subtype']);
		$this->assertArrayHasKey('update', $events['object']['static_config_subtype']);
		
		$this->callReflectorMethod('registerNotifications');
		
		$events = $notifications->getEvents();
		$this->assertArrayHasKey('object', $events);
		$this->assertArrayHasKey('static_config_subtype', $events['object']);
		$this->assertArrayHasKey('create', $events['object']['static_config_subtype']);
		$this->assertArrayNotHasKey('update', $events['object']['static_config_subtype']);
	}
	
	public static function hookCallback(\Elgg\Hook $hook) {
		$result = $hook->getValue();
		
		$result[] = __METHOD__;
		
		return $result;
	}
	
	public static function eventCallback(\Elgg\Event $event) {
		
	}
}
