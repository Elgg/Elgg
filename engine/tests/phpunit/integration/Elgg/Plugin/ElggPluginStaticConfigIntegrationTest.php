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
		
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$plugin = elgg_get_plugin_from_id('static_config');
			if ($plugin) {
				$plugin->delete();
			}
		});
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
		
		$this->invokeInaccessableMethod($this->plugin, 'registerViews');
		
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
		$this->assertFalse(elgg_entity_has_capability('object', \StaticConfigObject::SUBTYPE, 'searchable'));
		
		$this->invokeInaccessableMethod($this->plugin, 'registerEntities');
		
		$this->assertTrue(elgg_entity_has_capability('object', \StaticConfigObject::SUBTYPE, 'searchable'));
	}
	
	public function testDeActivateEntities() {
		$class = elgg_get_entity_class('object', \StaticConfigObject::SUBTYPE);
		
		$this->assertNotEquals(\StaticConfigObject::class, $class);
		
		$this->invokeInaccessableMethod($this->plugin, 'activateEntities');
		
		$class = elgg_get_entity_class('object', \StaticConfigObject::SUBTYPE);
		
		$this->assertEquals(\StaticConfigObject::class, $class);
		
		$this->invokeInaccessableMethod($this->plugin, 'deactivateEntities');
		
		$class = elgg_get_entity_class('object', \StaticConfigObject::SUBTYPE);
		
		$this->assertNotEquals(\StaticConfigObject::class, $class);
	}
	
	public function testActionsRegistration() {
		$this->assertFalse(elgg_action_exists('static_config/autodetect'));
		$this->assertFalse(elgg_action_exists('static_config/custom_file'));
		$this->assertFalse(elgg_action_exists('static_config/controller'));
		$this->assertFalse(elgg_action_exists('static_config/logged_out'));
		
		$this->invokeInaccessableMethod($this->plugin, 'registerActions');
		
		$this->assertTrue(elgg_action_exists('static_config/autodetect'));
		$this->assertTrue(elgg_action_exists('static_config/custom_file'));
		$this->assertTrue(elgg_action_exists('static_config/controller'));
		$this->assertTrue(elgg_action_exists('static_config/logged_out'));
		
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
		
		$route4 = _elgg_services()->routes->get('action:static_config/logged_out');
		$this->assertEquals($this->normalizeTestFilePath('mod/static_config/actions/static_config/logged_out.php'), $route4->getDefault('_file'));
		$this->assertIsArray($route4->getDefault('_middleware'));
		$this->assertContains(\Elgg\Router\Middleware\LoggedOutGatekeeper::class, $route4->getDefault('_middleware'));
		$this->assertContains(\Elgg\Router\Middleware\AjaxGatekeeper::class, $route4->getDefault('_middleware'));
	}
	
	public function testRoutesRegistration() {
		$this->assertFalse(elgg_route_exists('default:object:static_config_subtype'));
		
		$this->invokeInaccessableMethod($this->plugin, 'registerRoutes');
		
		$this->assertTrue(elgg_route_exists('default:object:static_config_subtype'));
	}
	
	public function testWidgetsRegistration() {
		$widgets = _elgg_services()->widgets;
		
		$this->assertFalse($widgets->validateType('static_config'));
		
		$this->invokeInaccessableMethod($this->plugin, 'registerWidgets');
		
		$this->assertTrue($widgets->validateType('static_config'));
		$this->assertTrue($widgets->validateType('static_config', 'profile'));
		$this->assertFalse($widgets->validateType('static_config', 'dashboard'));
	}
	
	public function testEventsRegistration() {
		$events = _elgg_services()->events;
		
		$events->registerHandler('prevent', 'something', '\Elgg\Plugin\ElggPluginStaticConfigIntegrationTest::hookCallback');
		
		$ordered = $events->getOrderedHandlers('prevent', 'something');
		$this->assertIsArray($ordered);
		$this->assertCount(1, $ordered);
		
		$this->invokeInaccessableMethod($this->plugin, 'registerEvents');
		
		$ordered = $events->getOrderedHandlers('prevent', 'something');
		$this->assertIsArray($ordered);
		$this->assertCount(2, $ordered);
		
		$this->assertNotContains('\Elgg\Plugin\ElggPluginStaticConfigIntegrationTest::hookCallback', $ordered);
		$this->assertEquals([
			'\Elgg\StaticConfig\HookCallback',
			'\Elgg\StaticConfig\HookCallback::highPriority',
		], $ordered);
	}
	
	public function testViewExtensionsRegistration() {
		$views = _elgg_services()->views;
		
		$this->invokeInaccessableMethod($this->plugin, 'registerViews');
		
		$views->extendView('static_config/view', 'static_config/unextend');
		
		$view_list = $views->getViewList('static_config/view');
		$this->assertIsArray($view_list);
		$this->assertCount(2, $view_list);
		
		$this->invokeInaccessableMethod($this->plugin, 'registerViewExtensions');
		
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
		
		$this->invokeInaccessableMethod($this->plugin, 'registerGroupTools');
		
		$this->assertEmpty($group_tools->get('static_config_unregister'));
		$this->assertInstanceOf(\Elgg\Groups\Tool::class, $group_tools->get('static_config'));
	}
	
	public function testViewOptionsRegistration() {
		$ajax = _elgg_services()->ajax;
		$views = _elgg_services()->views;
		
		$this->invokeInaccessableMethod($this->plugin, 'registerViews');
		
		$ajax->registerView('static_config/view');
		
		$this->assertNotContains('static_config/viewoptions', $ajax->getViews());
		$this->assertFalse($views->isCacheableView('static_config/viewoptions'));
		
		$this->invokeInaccessableMethod($this->plugin, 'registerViewOptions');
		
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
		
		$this->invokeInaccessableMethod($this->plugin, 'registerNotifications');
		
		$events = $notifications->getEvents();
		$this->assertArrayHasKey('object', $events);
		$this->assertArrayHasKey('static_config_subtype', $events['object']);
		$this->assertArrayHasKey('create', $events['object']['static_config_subtype']);
		$this->assertArrayNotHasKey('update', $events['object']['static_config_subtype']);
	}
	
	public static function hookCallback(\Elgg\Event $event) {
		$result = $event->getValue();
		
		$result[] = __METHOD__;
		
		return $result;
	}
	
	public static function eventCallback(\Elgg\Event $event) {
		
	}
}
