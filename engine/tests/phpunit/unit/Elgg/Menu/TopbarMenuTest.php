<?php

namespace Elgg\Menu;

use Elgg\UnitTestCase;

/**
 * @group Menus
 */
class TopbarMenuTest extends UnitTestCase {

	public function up() {
		_elgg_services()->hooks->backup();

		_elgg_services()->hooks->registerHandler('register', 'menu:topbar', '_elgg_user_topbar_menu');
	}
	
	public function down() {
		_elgg_services()->hooks->restore();
	}

	public function testTopbarMenuWithoutUser() {

		$menu = _elgg_services()->menus->getUnpreparedMenu('topbar', [
			'entity' => 'foo',
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());
	}

	public function testTopbarMenuViewedByGuest() {

		$menu = _elgg_services()->menus->getUnpreparedMenu('topbar');

		$items = $menu->getItems();

		$this->assertEmpty($items->all());
	}

	public function testTopbarMenuViewedByUser() {

		$viewer = $this->createUser();
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('topbar');

		$items = $menu->getItems();

		$this->assertTrue($items->has('account'));
		$this->assertTrue($items->has('usersettings'));
		$this->assertTrue($items->has('logout'));
		$this->assertFalse($items->has('administration'));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testTopbarMenuViewedByAdmin() {

		$viewer = $this->createUser([
			'admin' => 'yes',
		]);
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('topbar');

		$items = $menu->getItems();

		$this->assertTrue($items->has('account'));
		$this->assertTrue($items->has('usersettings'));
		$this->assertTrue($items->has('logout'));
		$this->assertTrue($items->has('administration'));

		_elgg_services()->session->removeLoggedInUser();
	}

}