<?php

namespace Elgg\Menus;

use Elgg\IntegrationTestCase;

class BreadcrumbsIntegrationTest extends IntegrationTestCase {
	
	public function up() {
		_elgg_services()->hooks->backup();
	}
	
	public function down() {
		$menus = elgg_get_config('menus');
		
		unset($menus['breadcrumbs']);
		elgg_set_config('menus', $menus);
		
		_elgg_services()->hooks->restore();
	}
	
	public function testCrumbsAreExcerpted() {
		elgg_register_plugin_hook_handler('prepare', 'menu:breadcrumbs', '\Elgg\Menus\Breadcrumbs::cleanupBreadcrumbs');
		
		elgg_push_breadcrumb(str_repeat('abcd ', 100), '#');
		
		$menu = _elgg_services()->menus->getMenu('breadcrumbs');
		$items = $menu->getSection('default')->getItems();
		$item = end($items);
		
		$this->assertEquals(elgg_get_excerpt(str_repeat('abcd ', 100), 100), $item->getText());
		$this->assertEquals('#', $item->getHref());
	}

	public function testTrailingNonLinkIsRemoved() {
		elgg_push_breadcrumb('Foo', 'foo');
		elgg_push_breadcrumb('Bar');
		
		$menu = _elgg_services()->menus->getMenu('breadcrumbs');
		$this->assertCount(2, $menu->getSection('default')->getItems());

		elgg_register_plugin_hook_handler('prepare', 'menu:breadcrumbs', '\Elgg\Menus\Breadcrumbs::cleanupBreadcrumbs');
		
		$menu = _elgg_services()->menus->getMenu('breadcrumbs');
		$this->assertCount(1, $menu->getSection('default')->getItems());
	}
}
