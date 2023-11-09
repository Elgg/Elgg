<?php

namespace Elgg;

class SessionManagerServiceIntegrationTest extends \Elgg\IntegrationTestCase {
	
	public function testCanSetLoggedInUser() {
		
		$user = $this->createUser();
		
		$session = _elgg_services()->session_manager;
		
		$session->setLoggedInUser($user);
		
		$this->assertEquals($user, $session->getLoggedInUser());
		$this->assertEquals($user->guid, $session->getLoggedInUserGuid());
		
		$session->removeLoggedInUser();
		
		$this->assertNull($session->getLoggedInUser());
	}
	
	public function testSetLoggedInUserChangesSessionID() {
		$user = $this->createUser();
		
		$session = _elgg_services()->session;
		$session_manager = _elgg_services()->session_manager;
		
		$session->start();
		$session_id = $session->getID();
		
		$session_manager->setLoggedInUser($user, true);
		
		$this->assertNotEquals($session_id, $session->getID());
	}
	
	public function testSetLoggedInUserDoesntChangesSessionID() {
		$user = $this->createUser();
		
		$session = _elgg_services()->session;
		$session_manager = _elgg_services()->session_manager;
		
		$session->start();
		$session_id = $session->getID();
		
		$session_manager->setLoggedInUser($user, false);
		
		$this->assertEquals($session_id, $session->getID());
	}
	
	public function testUserTokenValidation() {
		$user = $this->createUser();
		$session = _elgg_services()->session_manager;
		
		$session->setUserToken($user);
		$session->validateUserToken($user);
		
		// change the user password
		$user->setPassword('some new password');
		
		// token isn't valid anymore
		$this->expectException(\Elgg\Exceptions\SecurityException::class);
		$session->validateUserToken($user);
	}
	
	public function testUserTokenValidationLoggedIn() {
		$user = $this->createUser();
		$session = _elgg_services()->session_manager;
		
		$session->setLoggedInUser($user);
		$session->setUserToken();
		
		$session->validateUserToken($user);
		
		// change the user password
		$user->setPassword('some new password');
		
		// session should remain valid
		$session->validateUserToken($user);
	}
}
