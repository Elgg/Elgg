<?php

namespace Elgg\Menu;

use Elgg\UnitTestCase;

/**
 * @group Menus
 */
class PageMenuTest extends UnitTestCase {

	public function up() {
		_elgg_services()->hooks->backup();

		_elgg_services()->hooks->registerHandler('register', 'menu:page', '_elgg_user_page_menu');

		// @todo: test other menu hooks
		//_elgg_services()->hooks->registerHandler('register', 'menu:page', '_elgg_admin_page_menu');
		//_elgg_services()->hooks->registerHandler('register', 'menu:page', '_elgg_admin_page_menu_plugin_settings');
		//_elgg_services()->hooks->registerHandler('prepare', 'menu:page', '_elgg_setup_vertical_menu', 999);
		//_elgg_services()->hooks->registerHandler('register', 'menu:page', '_elgg_user_settings_menu_register');
		//_elgg_services()->hooks->registerHandler('prepare', 'menu:page', '_elgg_user_settings_menu_prepare');
	}
	
	public function down() {
		_elgg_services()->hooks->restore();
	}

	public function contextProvider() {
		return [
			['settings'],
			['admin'],
		];
	}

	public function testSettingsPageMenuWithoutUser() {

		elgg_set_context('settings');

		$menu = _elgg_services()->menus->getUnpreparedMenu('page', [
			'entity' => 'foo',
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());
	}

	public function testSettingsPageMenuViewedByGuest() {

		elgg_set_context('settings');

		$menu = _elgg_services()->menus->getUnpreparedMenu('page', [
			'entity' => $this->createUser(),
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());
	}

	public function testSettingsPageMenuViewedByUser() {

		elgg_set_context('settings');

		$viewer = $this->createUser();
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('page', [
			'entity' => $this->createUser(),
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testSettingsPageMenuViewedBySelf() {

		elgg_set_context('settings');

		$viewer = $this->createUser();
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('page', [
			'entity' => $viewer,
		]);

		$items = $menu->getItems();

		$this->assertTrue($items->has('edit_avatar'));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testSettingsPageMenuViewedByAdmin() {

		elgg_set_context('settings');

		$viewer = $this->createUser([
			'admin' => 'yes',
		]);

		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('page', [
			'entity' => $this->createUser(),
		]);

		$items = $menu->getItems();

		$this->assertTrue($items->has('edit_avatar'));

		_elgg_services()->session->removeLoggedInUser();
	}

}