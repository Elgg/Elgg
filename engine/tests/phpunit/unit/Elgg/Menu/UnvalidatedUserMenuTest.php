<?php

namespace Elgg\Menu;

use Elgg\UnitTestCase;

/**
 * @group Menus
 */
class UnvalidatedUserMenuTest extends UnitTestCase {

	public function up() {
		_elgg_services()->hooks->backup();

		_elgg_services()->hooks->registerHandler('register', 'menu:user:unvalidated', '_elgg_user_unvalidated_menu');
	}
	
	public function down() {
		_elgg_services()->hooks->restore();
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
		_elgg_services()->session->setLoggedInUser($viewer);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user:unvalidated', [
			'entity' => $this->createUser(),
		]);

		$items = $menu->getItems();

		$this->assertEmpty($items->all());

		_elgg_services()->session->removeLoggedInUser();
	}

	public function testUnvalidatedUserMenuViewedByAdmin() {

		$viewer = $this->createUser([
			'admin' => 'yes',
		]);

		_elgg_services()->session->setLoggedInUser($viewer);

		$user = $this->createUser();
		$user->setValidationStatus(false);

		$menu = _elgg_services()->menus->getUnpreparedMenu('user:unvalidated', [
			'entity' => $user,
		]);

		$items = $menu->getItems();

		$this->assertTrue($items->has('validate'));
		$this->assertTrue($items->has('delete'));

		_elgg_services()->session->removeLoggedInUser();
	}

}