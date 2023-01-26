<?php

namespace Elgg\Menus;

use Elgg\UnitTestCase;

/**
 * @group Menus
 */
class UserUnvalidatedUnitTest extends UnitTestCase {

	public function up() {
		_elgg_services()->events->backup();

		_elgg_services()->events->registerHandler('register', 'menu:user:unvalidated', 'Elgg\Menus\UserUnvalidated::register');
	}
	
	public function down() {
		_elgg_services()->events->restore();
	}

	public function testUnvalidatedUserMenuWithoutUser() {

		$menu = _elgg_services()->menus->getUnpreparedMenu('user:unvalidated', [
			'entity' => 'foo',
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());
	}

	public function testUnvalidatedUserMenuViewedByGuest() {

		$menu = _elgg_services()->menus->getUnpreparedMenu('user:unvalidated', [
			'entity' => $this->createUser(),
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());
	}

	public function testUnvalidatedUserMenuViewedByUser() {

		$viewer = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user:unvalidated', [
			'entity' => $this->createUser(),
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());
	}

	public function testUnvalidatedUserMenuViewedByAdmin() {

		$viewer = $this->createUser([
			'admin' => 'yes',
		]);

		_elgg_services()->session_manager->setLoggedInUser($viewer);

		$user = $this->createUser();
		$user->setValidationStatus(false);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user:unvalidated', [
			'entity' => $user,
		]);

		$items = $menu->getItems();

		$this->assertTrue($items->has('validate'));
		$this->assertTrue($items->has('delete'));
	}
}
