<?php

namespace Elgg\Navigation;

use Elgg\IntegrationTestCase;

/**
 * The purpose of these tests is to catch validation problems in menu events
 */
class MenuRenderingIntegrationTest extends IntegrationTestCase {
	
	protected \ElggUser $user;
	protected \ElggObject $object;
	protected \ElggGroup $group;
	
	public function up() {
		$this->user = $this->createUser();
		elgg_set_page_owner_guid($this->user->guid);
		$this->object = $this->createObject();
		$this->group = $this->createGroup();
	}

	public static function entityMenuNamesProvider() {
		return [
			['entity'],
			['user_hover'],
			['page_owner'],
			['social'],
			['entity_navigation'],
		];
	}

	/**
	 * @dataProvider entityMenuNamesProvider
	 */
	public function testEntityMenus($menu) {
		$menu_item = \ElggMenuItem::factory([
			'name' => 'foo',
			'text' => 'bar',
		]);

		if ($menu === 'user_hover') {
			$types = ['user'];
		} else {
			$types = ['user', 'object', 'group'];
		}

		foreach ($types as $type) {
			$calls = 0;
			$handler = function (\Elgg\Event $event) use ($menu_item, &$calls) {
				$value = $event->getValue();
				$value[] = $menu_item;

				$calls++;

				return $value;
			};

			elgg_register_event_handler('register', "menu:$menu", $handler);

			$view = elgg_view_menu($menu, [
				'entity' => $this->$type,
			]);

			$this->assertNotEmpty($view);
			$this->assertEquals(1, $calls);

			elgg_unregister_event_handler('register', "menu:$menu", $handler);
		}
	}

	public static function layoutMenuNamesProvider() {
		return [
			['title'],
			['page'],
			['walled_garden'],
			['login'],
			['site'],
			['topbar'],
			['footer'],
		];
	}

	/**
	 * @dataProvider layoutMenuNamesProvider
	 */
	public function testLayoutMenu($menu) {

		$menu_item = \ElggMenuItem::factory([
			'name' => 'foo',
			'text' => 'bar',
		]);

		$calls = 0;
		$handler = function(\Elgg\Event $event) use ($menu_item, &$calls) {
			$value = $event->getValue();
			$value[] = $menu_item;

			$calls++;

			return $value;
		};

		elgg_register_event_handler('register', "menu:$menu", $handler);

		$menu = elgg_view_menu($menu);

		$this->assertNotEmpty($menu);
		$this->assertEquals(1, $calls);

		elgg_unregister_event_handler('register', "menu:$menu", $handler);
	}

	public function testRiverMenu() {

		$menu_item = \ElggMenuItem::factory([
			'name' => 'foo',
			'text' => 'bar',
		]);

		$calls = 0;
		$handler = function(\Elgg\Event $event) use ($menu_item, &$calls) {
			$value = $event->getValue();
			$value[] = $menu_item;

			$calls++;

			return $value;
		};

		elgg_register_event_handler('register', 'menu:river', $handler);

		$item = new \ElggRiverItem((object) [
			'view' => 'river/elements/layout',
			'action_type' => 'test',
			'object_guid' => $this->object->guid,
			'subject_guid' => $this->user->guid,
			'target_guid' => $this->group->guid,
		]);

		$menu = elgg_view_menu('river', [
			'item' => $item,
			'prepare_dropdown' => true,
		]);

		$this->assertNotEmpty($menu);
		$this->assertEquals(1, $calls);

		elgg_unregister_event_handler('register', 'menu:river', $handler);
	}
}
