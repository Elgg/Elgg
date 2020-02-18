<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;
use Elgg\Mocks\Database\UsersApiSessionsTable;

class UsersApiSessionsTableIntegrationTest extends IntegrationTestCase {

	/**
	 * @var UsersApiSessionsTable
	 */
	protected $service;
	
	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->usersApiSessionsTable;
		$this->user = $this->createUser();
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		if ($this->user instanceof \ElggUser) {
			$this->user->delete();
		}
	}
	
	public function testCreateToken() {
		$this->assertNotFalse($this->service->createToken($this->user->guid));
	}
	
	public function testGetUserTokens() {
		$this->assertNotFalse($this->service->createToken($this->user->guid));
		$this->assertNotFalse($this->service->createToken($this->user->guid));
		
		$tokens = $this->service->getUserTokens($this->user->guid);
		$this->assertNotEmpty($tokens);
		$this->assertCount(2, $tokens);
	}
	
	public function testValidateToken() {
		$token = $this->service->createToken($this->user->guid);
		$this->assertNotFalse($token);
		
		$this->assertNotFalse($this->service->validateToken($token));
	}
	
	public function testRemoveToken() {
		$token = $this->service->createToken($this->user->guid);
		$this->assertNotFalse($token);
		
		$this->assertTrue($this->service->removeToken($token));
		$this->assertFalse($this->service->validateToken($token));
		$this->assertEmpty($this->service->getUserTokens($this->user->guid));
	}
	
	public function testRemoveExpiredTokens() {
		// create short token
		$this->assertNotFalse($this->service->createToken($this->user->guid, 1));
		$this->assertNotEmpty($this->service->getUserTokens($this->user->guid));
		
		// advance time
		$time = $this->service->getCurrentTime('+10 minutes');
		$this->service->setCurrentTime($time);
		
		$this->assertGreaterThan(0, $this->service->removeExpiresTokens());
		$this->assertEmpty($this->service->getUserTokens($this->user->guid));
	}
}
