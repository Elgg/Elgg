<?php

namespace Elgg;

class SessionManagerServiceUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var SessionManagerService
	 */
	protected $service;

	public function up() {
		_elgg_services()->reset('session_manager');
		$this->service = _elgg_services()->session_manager;
	}

	function testIgnoreAccess() {
		$this->assertFalse($this->service->getIgnoreAccess()); // service inits false
		$this->assertFalse($this->service->setIgnoreAccess(true)); // returns old state
		$this->assertTrue($this->service->getIgnoreAccess());
		$this->assertTrue($this->service->setIgnoreAccess(false)); // returns old state
		$this->assertFalse($this->service->getIgnoreAccess());
	}

	function testDisabledEntityVisibility() {
		$this->assertFalse($this->service->getDisabledEntityVisibility()); // service inits false
		$this->assertFalse($this->service->setDisabledEntityVisibility(true)); // returns old state
		$this->assertTrue($this->service->getDisabledEntityVisibility());
		$this->assertTrue($this->service->setDisabledEntityVisibility(false)); // returns old state
		$this->assertFalse($this->service->getDisabledEntityVisibility());
	}
	
	function testDeletedEntityVisibility() {
		$this->assertFalse($this->service->getDeletedEntityVisibility()); // service inits false
		$this->assertFalse($this->service->setDeletedEntityVisibility(true)); // returns old state
		$this->assertTrue($this->service->getDeletedEntityVisibility());
		$this->assertTrue($this->service->setDeletedEntityVisibility(false)); // returns old state
		$this->assertFalse($this->service->getDeletedEntityVisibility());
	}

	function testSettingLoggedInUser() {
		$this->assertNull($this->service->getLoggedInUser()); // service inits null
		$this->assertEquals(0, $this->service->getLoggedInUserGuid());
		$this->assertFalse($this->service->isLoggedIn());
		$this->assertFalse($this->service->isAdminLoggedIn());
		
		$user = $this->createUser();
		$this->service->setLoggedInUser($user);
		
		$this->assertInstanceOf(\ElggUser::class, $this->service->getLoggedInUser());
		$this->assertEquals($user->guid, $this->service->getLoggedInUserGuid());
		
		$this->assertNotNull(_elgg_services()->entityCache->load($user->guid));
		_elgg_services()->entityCache->delete($user->guid);
		$this->assertNull(_elgg_services()->entityCache->load($user->guid));
		
		$this->service->setLoggedInUser($user); // settings the same user should not update caches
		$this->assertNull(_elgg_services()->entityCache->load($user->guid));
		
		$user2 = $this->createUser();
		$this->service->setLoggedInUser($user2);
		$this->assertInstanceOf(\ElggUser::class, $this->service->getLoggedInUser());
		$this->assertEquals($user2->guid, $this->service->getLoggedInUserGuid());
		
		$this->assertTrue($this->service->isLoggedIn());
		$this->assertFalse($this->service->isAdminLoggedIn());
		
		$admin = $this->createUser(['admin' => 'yes']);
		$this->service->setLoggedInUser($admin);
		$this->assertTrue($this->service->isAdminLoggedIn());
		
		// check all after remove
		$this->service->removeLoggedInUser();
		$this->assertNull($this->service->getLoggedInUser());
		$this->assertEquals(0, $this->service->getLoggedInUserGuid());
		$this->assertFalse($this->service->isLoggedIn());
		$this->assertFalse($this->service->isAdminLoggedIn());
	}
}
