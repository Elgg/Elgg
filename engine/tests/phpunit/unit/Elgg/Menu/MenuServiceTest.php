<?php

namespace Elgg\Menu;

use Elgg\Hook;
use Elgg\UnitTestCase;

/**
 * @group MenuService
 */
class MenuServiceTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanUnpreparedMenu() {

		$params_hook = $this->registerTestingHook('parameters', 'menu:test', function (Hook $hook) {
			$this->assertEquals('bar', $hook->getParam('foo'));
			$this->assertEquals('test', $hook->getParam('name'));
		});

		$register_hook = $this->registerTestingHook('register', 'menu:test', function (Hook $hook) {
			$this->assertInstanceOf(MenuItems::class, $hook->getValue());
		});

		$items = $this->buildMenu();

		foreach ($items as $item) {
			elgg_register_menu_item('test', $item);
		}

		$menu = elgg()->menus->getUnpreparedMenu('test', ['foo' => 'bar']);

		$this->assertEquals(count($items), count($menu->getItems()));
		$this->assertEquals('priority', $menu->getSortBy());

		$params_hook->assertNumberOfCalls(1);
		$register_hook->assertNumberOfCalls(1);

	}

	public function testCanGetPreparedMenu() {

		$prepare_hook = $this->registerTestingHook('prepare', 'menu:test', function (Hook $hook) {
			$this->assertEquals('test', $hook->getParam('name'));
			$this->assertEquals('bar', $hook->getParam('foo'));
			$this->assertInstanceOf(PreparedMenu::class, $hook->getValue());
		});

		$items = $this->buildMenu();

		foreach ($items as $item) {
			elgg_register_menu_item('test', $item);
		}

		$menu = elgg()->menus->getMenu('test', ['foo' => 'bar']);

		$this->assertInstanceOf(PreparedMenu::class, $menu->getSections());
		$this->assertEquals(1, count($menu->getSections()));

		$root = $menu->getSection('default')->getItem('root');
		$this->assertEquals(4, count($root->getChildren()));

		$sorts = array_map(function (\ElggMenuItem $child) {
			return $child->getPriority();
		}, $root->getChildren());

		$this->assertEquals([100, 200, 300, 400], $sorts);

		$this->assertEquals(1, count($menu->getSection('default')->getItems()));

		$prepare_hook->assertNumberOfCalls(1);

	}


	public function testCanSortMenuByName() {

		$prepare_hook = $this->registerTestingHook('prepare', 'menu:test', function (Hook $hook) {
			$this->assertEquals('test', $hook->getParam('name'));
			$this->assertEquals('bar', $hook->getParam('foo'));
			$this->assertInstanceOf(PreparedMenu::class, $hook->getValue());
		});

		$items = $this->buildMenu();

		foreach ($items as $item) {
			elgg_register_menu_item('test', $item);
		}

		$menu = elgg()->menus->getMenu('test', ['sort_by' => 'text', 'foo' => 'bar']);

		$this->assertEquals(1, count($menu->getSection('default')->getItems()));

		$root = $menu->getSection('default')->getItem('root');
		$this->assertEquals(4, count($root->getChildren()));

		$sorts = array_map(function (\ElggMenuItem $child) {
			return $child->getName();
		}, $root->getChildren());

		$this->assertEquals(["n:100", "n:200", "n:300", "n:400"], $sorts);

		$prepare_hook->assertNumberOfCalls(1);
	}

	public function testCanSortMenuByText() {

		$prepare_hook = $this->registerTestingHook('prepare', 'menu:test', function (Hook $hook) {
			$this->assertEquals('test', $hook->getParam('name'));
			$this->assertEquals('bar', $hook->getParam('foo'));
			$this->assertInstanceOf(PreparedMenu::class, $hook->getValue());
		});

		$items = $this->buildMenu();

		foreach ($items as $item) {
			elgg_register_menu_item('test', $item);
		}

		$menu = elgg()->menus->getMenu('test', ['sort_by' => 'text', 'foo' => 'bar']);

		$this->assertEquals(1, count($menu->getSection('default')->getItems()));

		$root = $menu->getSection('default')->getItem('root');
		$this->assertEquals(4, count($root->getChildren()));

		$sorts = array_map(function (\ElggMenuItem $child) {
			return $child->getText();
		}, $root->getChildren());

		$this->assertEquals(["t:100", "t:200", "t:300", "t:400"], $sorts);

		$prepare_hook->assertNumberOfCalls(1);
	}

	public function testCanCombineMenus() {

		$items = $this->buildMenu();
		$count = count($items);

		foreach ($items as $item) {
			$name = $item['name'];
			$parent_name =  null;
			if (!empty($item['parent_name'])) {
				$parent_name = $item['parent_name'];
			}

			$item['name'] = "menu1:{$name}";
			$item['parent_name'] = $parent_name ? "menu1:{$parent_name}" : null;
			elgg_register_menu_item('menu1', $item);

			$item['name'] = "menu2:{$name}";
			$item['parent_name'] = $parent_name ? "menu2:{$parent_name}" : null;
			elgg_register_menu_item('menu2', $item);
		}

		$unprepared_menu = elgg()->menus->combineMenus(['menu1', 'menu2'], [], 'new_menu');
		$unprepared_menu->setSortBy('text');
		$items = $unprepared_menu->getItems();
		$this->assertEquals($count * 2, count($items));

		$menu = elgg()->menus->prepareMenu($unprepared_menu);

		$this->assertInstanceOf(PreparedMenu::class, $menu->getSections());
		$this->assertEquals(2, count($menu->getSections()));

		$this->assertNull($menu->getSections()->get('default'));
		$this->assertInstanceOf(MenuSection::class, $menu->getSections()->get('menu1'));
		$this->assertInstanceOf(MenuSection::class, $menu->getSections()->get('menu2'));

		$root = $menu->getSection('menu1')->getItem('menu1:root');
		$this->assertEquals(4, count($root->getChildren()));

		$root = $menu->getSection('menu2')->getItem('menu2:root');
		$this->assertEquals(4, count($root->getChildren()));

		$sorts = array_map(function (\ElggMenuItem $child) {
			return $child->getText();
		}, $root->getChildren());

		$this->assertEquals(["t:100", "t:200", "t:300", "t:400"], $sorts);

	}
	
	public function testSetSelectedMenuItem() {
		$items = $this->buildMenu();
		
		foreach ($items as $item) {
			elgg_register_menu_item('test', $item);
		}
		
		$menu = elgg()->menus->getMenu('test', [
			'selected_item_name' => 'n:200',
		]);
		
		$params = $menu->getParams();
		$selected_item = elgg_extract('selected_item', $params);
		
		$this->assertInstanceOf(\ElggMenuItem::class, $selected_item);
		$this->assertEquals('n:200', $selected_item->getName());
	}

	public function buildMenu() {
		$items = [
			[
				'name' => 'root',
				'text' => 'root',
				'href' => '#root',
			],
		];

		foreach ([400, 300, 200, 100] as $priority) {
			$items[] = [
				'name' => "n:$priority",
				'text' => "t:$priority",
				'href' => "#t:$priority",
				'parent_name' => 'root',
				'priority' => $priority,
			];


			foreach ([800, 700] as $priority2) {
				$items[] = [
					'name' => "n:$priority:$priority2",
					'text' => "t:$priority:$priority2",
					'href' => "#t:$priority:$priority2",
					'parent_name' => "n:$priority",
					'priority' => $priority2,
				];

				foreach ([10, 20] as $priority3) {
					$items[] = [
						'name' => "n:$priority:$priority2:$priority3",
						'text' => "t:$priority:$priority2:$priority3",
						'href' => "#t:$priority:$priority2:$priority3",
						'parent_name' => "n:$priority:$priority2",
						'priority' => $priority3,
					];
				}
			}
		}

		return $items;
	}
}
