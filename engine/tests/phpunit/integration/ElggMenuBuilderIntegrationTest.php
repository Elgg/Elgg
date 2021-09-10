<?php

use Elgg\IntegrationTestCase;
use Elgg\Menu\PreparedMenu;
use Elgg\Menu\MenuSection;

class ElggMenuBuilderIntegrationTest extends IntegrationTestCase {

	/**
	 * @var array
	 */
	protected $contextStack;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->contextStack = elgg_get_context_stack();
		
		elgg_push_context('foo');
		elgg_push_context('bar');
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		elgg_set_context_stack($this->contextStack);
	}
	
	protected function getMenuItems(): array {
		$items = [];
		
		$items[] = ElggMenuItem::factory([
			'name' => 'a_menu_item',
			'text' => 'Z menu text',
			'href' => '/something',
			'priority' => 200,
			'contexts' => ['foo', 'bar'],
		]);
		
		$items[] = ElggMenuItem::factory([
			'name' => 'b_menu_item',
			'text' => 'Y menu text',
			'href' => '/foo',
			'priority' => 100,
			'selected' => true,
			'contexts' => ['foo'],
		]);
		
		$items[] = ElggMenuItem::factory([
			'name' => 'c_menu_item',
			'text' => 'X menu text',
			'href' => '/bar',
			'priority' => 150,
			'contexts' => ['bar'],
		]);
		
		return $items;
	}
	
	protected function getMenuBuilder(): ElggMenuBuilder {
		return new ElggMenuBuilder($this->getMenuItems());
	}

	public function testGetMenuSetsSelectedMenuItem() {
		$builder = $this->getMenuBuilder();
		
		$this->assertEmpty($builder->getSelected());
		
		$sorted_menu = $builder->getMenu();
		$this->assertInstanceOf(PreparedMenu::class, $sorted_menu);
		
		$selected = $builder->getSelected();
		$this->assertInstanceOf(ElggMenuItem::class, $selected);
		$this->assertEquals('b_menu_item', $selected->getID());
	}
	
	/**
	 * @dataProvider getMenuSortingProvider
	 */
	public function testGetMenuSorting($sort_by, $expected) {
		$builder = $this->getMenuBuilder();
		
		$sorted = $builder->getMenu($sort_by);
		$this->assertInstanceOf(PreparedMenu::class, $sorted);
		
		$actual_order = [];
		foreach ($sorted as $section) {
			foreach ($section as $menu_item) {
				$actual_order[] = $menu_item->getID();
			}
		}
		
		$this->assertEquals($expected, $actual_order);
	}
	
	public function getMenuSortingProvider() {
		return [
			['priority', ['b_menu_item', 'c_menu_item', 'a_menu_item']],
			['text', ['c_menu_item', 'b_menu_item', 'a_menu_item']],
			['name', ['a_menu_item', 'b_menu_item', 'c_menu_item']],
		];
	}
	
	/**
	 * @dataProvider filterByContextProvider
	 */
	public function testFilterByContext($context, $expected) {
		$builder = $this->getMenuBuilder();
		
		if (isset($context)) {
			elgg_set_context_stack([]);
			elgg_set_context($context);
		}
		
		$sorted = $builder->getMenu();
		$this->assertInstanceOf(PreparedMenu::class, $sorted);
		
		$actual_order = [];
		foreach ($sorted as $section) {
			foreach ($section as $menu_item) {
				$actual_order[] = $menu_item->getID();
			}
		}
		
		$this->assertEquals($expected, $actual_order);
	}
	
	public function filterByContextProvider() {
		return [
			[null, ['b_menu_item', 'c_menu_item', 'a_menu_item']],
			['foo', ['b_menu_item', 'a_menu_item']],
			['bar', ['c_menu_item', 'a_menu_item']],
		];
	}
	
	public function testSetupSections() {
		$items = $this->getMenuItems();
		
		$builder = new ElggMenuBuilder($items);
		$sorted = $builder->getMenu();
		
		$this->assertInstanceOf(PreparedMenu::class, $sorted);
		// count number of sections
		$this->assertEquals(1, $sorted->count());
		
		// change one menu item to a different section
		$items[1]->setSection('action');
		
		$builder = new ElggMenuBuilder($items);
		$sorted = $builder->getMenu();
		
		$this->assertInstanceOf(PreparedMenu::class, $sorted);
		// count number of sections
		$this->assertEquals(2, $sorted->count());
	}
	
	public function testSetupTrees() {
		$items = $this->getMenuItems();
		
		$builder = new ElggMenuBuilder($items);
		$sorted = $builder->getMenu();
		
		$this->assertInstanceOf(PreparedMenu::class, $sorted);
		$default_section = $sorted->get('default');
		$this->assertInstanceOf(MenuSection::class, $default_section);
		
		/* @var $menu_item ElggMenuItem */
		foreach ($default_section as $menu_item) {
			$this->assertEmpty($menu_item->getParentName());
		}
		
		// change one menu item to a different parent
		$items[1]->setParentName('a_menu_item');
		
		$builder = new ElggMenuBuilder($items);
		$sorted = $builder->getMenu();
		
		$this->assertInstanceOf(PreparedMenu::class, $sorted);
		$default_section = $sorted->get('default');
		$this->assertInstanceOf(MenuSection::class, $default_section);
		$this->assertEquals(2, $default_section->count());
		
		// check a_menu_item
		$this->assertEmpty($default_section->get('a_menu_item')->getParentName());
		$children = $default_section->get('a_menu_item')->getChildren();
		$this->assertCount(1, $children);
		$this->assertEquals('b_menu_item', $children[0]->getID());
		
		$this->assertEmpty($default_section->get('b_menu_item'));
		
		$this->assertEmpty($default_section->get('c_menu_item')->getParentName());
	}
}
