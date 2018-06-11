<?php

namespace Elgg\Menu;

use Elgg\UnitTestCase;

/**
 * @group Menus
 */
class UserHoverMenuTest extends UnitTestCase {

	public function up() {
		_elgg_services()->hooks->backup();

		_elgg_services()->hooks->registerHandler('register', 'menu:user_hover', 'elgg_user_hover_menu');
	}
	
	public function down() {
		_elgg_services()->hooks->restore();
	}

	public function testUserHoverMenuWithoutUser() {

		$menu = _elgg_services()->menus->getUnpreparedMenu('user_hover', [
			'entity' => 'foo',
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());
	}

	public function testUserHoverMenuViewedByGuest() {

		$menu = _elgg_services()->menus->getUnpreparedMenu('user_hover', [
			'entity' => $this->createUser(),
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());
	}

	public function testUserHoverMenuViewedByUser() {

		$viewer = $this->createUser();
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user_hover', [
			'entity' => $this->createUser(),
		]);

		$items = $menu->getItems();

		$this->assertFalse($items->has('avatar:edit'));
		$this->assertFalse($items->has('ban'));
		$this->assertFalse($items->has('unban'));
		$this->assertFalse($items->has('delete'));
		$this->assertFalse($items->has('resetpassword'));
		$this->assertFalse($items->has('makeadmin'));
		$this->assertFalse($items->has('removeadmin'));
		$this->assertFalse($items->has('settings:edit'));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testUserHoverMenuViewedBySelf() {

		$viewer = $this->createUser();
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user_hover', [
			'entity' => $viewer,
		]);

		$items = $menu->getItems();

		$this->assertTrue($items->has('avatar:edit'));
		$this->assertFalse($items->has('ban'));
		$this->assertFalse($items->has('unban'));
		$this->assertFalse($items->has('delete'));
		$this->assertFalse($items->has('resetpassword'));
		$this->assertFalse($items->has('makeadmin'));
		$this->assertFalse($items->has('removeadmin'));
		$this->assertFalse($items->has('settings:edit'));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testUserHoverMenuViewedByAdmin() {

		$viewer = $this->createUser([
			'admin' => 'yes',
		]);
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user_hover', [
			'entity' => $this->createUser(),
		]);

		$items = $menu->getItems();

		$this->assertTrue($items->has('avatar:edit'));
		$this->assertTrue($items->has('ban'));
		$this->assertFalse($items->has('unban'));
		$this->assertTrue($items->has('delete'));
		$this->assertTrue($items->has('resetpassword'));
		$this->assertTrue($items->has('makeadmin'));
		$this->assertFalse($items->has('removeadmin'));
		$this->assertTrue($items->has('settings:edit'));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testBannedUserHoverMenuViewedByAdmin() {

		$viewer = $this->createUser([
			'admin' => 'yes',
		]);
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user_hover', [
			'entity' => $this->createUser([
				'banned' => 'yes',
			]),
		]);

		$items = $menu->getItems();

		$this->assertTrue($items->has('avatar:edit'));
		$this->assertFalse($items->has('ban'));
		$this->assertTrue($items->has('unban'));
		$this->assertTrue($items->has('delete'));
		$this->assertTrue($items->has('resetpassword'));
		$this->assertTrue($items->has('makeadmin'));
		$this->assertFalse($items->has('removeadmin'));
		$this->assertTrue($items->has('settings:edit'));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testAdminUserHoverMenuViewedByAdmin() {

		$viewer = $this->createUser([
			'admin' => 'yes',
		]);
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user_hover', [
			'entity' => $this->createUser([
				'admin' => 'yes',
			]),
		]);

		$items = $menu->getItems();

		$this->assertTrue($items->has('avatar:edit'));
		$this->assertTrue($items->has('ban'));
		$this->assertFalse($items->has('unban'));
		$this->assertTrue($items->has('delete'));
		$this->assertTrue($items->has('resetpassword'));
		$this->assertFalse($items->has('makeadmin'));
		$this->assertTrue($items->has('removeadmin'));
		$this->assertTrue($items->has('settings:edit'));

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testAdminUserViewedBySelf() {

		$viewer = $this->createUser([
			'admin' => 'yes',
		]);
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user_hover', [
			'entity' => $viewer,
		]);

		$items = $menu->getItems();

		$this->assertTrue($items->has('avatar:edit'));
		$this->assertFalse($items->has('ban'));
		$this->assertFalse($items->has('unban'));
		$this->assertFalse($items->has('delete'));
		$this->assertFalse($items->has('resetpassword'));
		$this->assertFalse($items->has('makeadmin'));
		$this->assertFalse($items->has('removeadmin'));
		$this->assertFalse($items->has('settings:edit'));

		_elgg_services()->session->removeLoggedInUser();
	}

}