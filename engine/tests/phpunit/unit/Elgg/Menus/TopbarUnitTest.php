<?php

namespace Elgg\Menus;

use Elgg\UnitTestCase;

/**
 * @group Menus
 */
class TopbarUnitTest extends UnitTestCase {

	public function up() {
		_elgg_services()->events->backup();

		_elgg_services()->events->registerHandler('register', 'menu:topbar', 'Elgg\Menus\Topbar::registerUserLinks');
	}
	
	public function down() {
		_elgg_services()->events->restore();
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
		_elgg_services()->session_manager->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('topbar');

		$items = $menu->getItems();

		$this->assertTrue($items->has('account'));
		$this->assertTrue($items->has('usersettings'));
		$this->assertTrue($items->has('logout'));
		$this->assertFalse($items->has('administration'));
	}

	public function testTopbarMenuViewedByAdmin() {

		$viewer = $this->createUser([
			'admin' => 'yes',
		]);
		_elgg_services()->session_manager->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('topbar');

		$items = $menu->getItems();

		$this->assertTrue($items->has('account'));
		$this->assertTrue($items->has('usersettings'));
		$this->assertTrue($items->has('logout'));
		$this->assertTrue($items->has('administration'));
	}
}
