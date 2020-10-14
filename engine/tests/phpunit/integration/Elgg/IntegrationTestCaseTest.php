<?php

namespace Elgg;

use Elgg\Debug\Inspector;

/**
 * @group IntegrationTests
 * @group TestingSuite
 */
class IntegrationTestCaseTest extends IntegrationTestCase {

	/**
	 * @var \ElggObject
	 */
	private $entity;

	public function up() {
		_elgg_services()->boot->clearCache();
		
		$this->entity = $this->createOne('object', [
			'access_id' => ACCESS_PUBLIC,
		]);
	}

	public function down() {
		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$this->entity->delete();
		});
	}

	public function testCanLoadEntityFromTestingApplicationDatabase() {
		$count = elgg_get_entities([
			'guids' => $this->entity->guid,
			'count' => true,
		]);

		$this->assertTrue($count === 1);
	}

	/**
	 * Parent class will also make assertions that hooks and events
	 * are identical after multiple bootstraps, which will indicate
	 * that plugins can start multiple times
	 */
	public function testCanResetTestingApplicationAfterMultipleInstantiations() {
		$app1 = self::createApplication(['isolate' => true]);
		$dbConfig = $app1->getDbConfig();

		$app2 = self::createApplication(['isolate' => true]);

		$this->assertNotSame($app1, $app2);
		$this->assertSame(Application::$_instance, $app2);

		$this->assertEquals($dbConfig, $app2->getDbConfig());
	}

	public function testCanResetTestingApplicationAfterPluginStackChanges() {

		$map = function($plugins) {
			$mapped = [];
			foreach ($plugins as $plugin) {
				$mapped[] = $plugin->getPriority() . ':' . $plugin->getID();
			}
			return $mapped;
		};

		self::createApplication(['isolate' => true]);

		$plugins = elgg_get_plugins('active');
		if (!$plugins) {
			$this->markTestSkipped("Test requires at least one active plugin");
		}

		$plugin = false;
		/* @var $potential_plugin \ElggPlugin */
		foreach ($plugins as $potential_plugin) {
			// Find a plugin which can be deactivated (has no dependant plugins)
			if (!$potential_plugin->canDeactivate()) {
				continue;
			}
			$plugin = $potential_plugin;
			break;
		}
		
		$this->assertInstanceOf(\ElggPlugin::class, $plugin); // we need a plugin to test with

		$inspector = new Inspector();
		$hooks = $inspector->getPluginHooks();
		$events = $inspector->getEvents();
		$views = $inspector->getViews();
		$actions = $inspector->getActions();
		$entity_types = get_registered_entity_types();
		$widget_types = elgg_get_widget_types();
		
		$this->assertTrue($plugin->deactivate());
		$this->assertFalse($plugin->isActive());
		$this->assertFalse(elgg_is_active_plugin($plugin->getID()));

		$this->assertNotEquals($map($plugins), $map(elgg_get_plugins('active')));

		self::createApplication(['isolate' => true]);

		$this->assertTrue($plugin->activate());
		$this->assertTrue($plugin->isActive());
		$this->assertTrue(elgg_is_active_plugin($plugin->getID()));

		self::createApplication(['isolate' => true]);

		$this->assertEquals($map($plugins), $map(elgg_get_plugins('active')));

		$this->assertEquals($hooks, $inspector->getPluginHooks());
		$this->assertEquals($events, $inspector->getEvents());
		$this->assertEquals($views, $inspector->getViews());
		$this->assertEquals($actions, $inspector->getActions());
		$this->assertEquals($entity_types, get_registered_entity_types());
		$this->assertEquals($widget_types, elgg_get_widget_types());
	}

	public function testCanReplaceSessionUser() {

		$user = $this->createOne('user');

		_elgg_services()->session->setLoggedInUser($user);

		$this->assertEquals(elgg_get_logged_in_user_entity(), $user);

		_elgg_services()->session->removeLoggedInUser();

	}
}