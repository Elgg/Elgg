<?php

namespace Elgg\Database;

use Elgg\UnitTestCase;

class ApiUsersTableUnitTest extends UnitTestCase {

	/**
	 * @var ApiUsersTable
	 */
	protected $service;
	
	/**
	 * @var \stdClass
	 */
	protected $api_user;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->apiUsersTable;
		
		$this->api_user = $this->service->createApiUser();
		$this->assertNotFalse($this->api_user);
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}
	
	public function testCreateApiUser() {
		$api_user = $this->service->createApiUser();
		
		$this->assertNotFalse($api_user);
		$this->assertNotEmpty($api_user->api_key);
		$this->assertNotEmpty($api_user->secret);
	}
	
	public function testGetApiUser() {
		$this->assertEquals($this->api_user, $this->service->getApiUser($this->api_user->api_key));
	}
	
	public function testRemoveApiUser() {
		$this->assertNotEmpty($this->service->removeApiUser($this->api_user->api_key));
		$this->assertEmpty($this->service->getApiUser($this->api_user->api_key));
	}
	
	public function testDisableEnableApiUser() {
		// disable
		$this->assertTrue($this->service->disableAPIUser($this->api_user->api_key));
		$this->assertFalse($this->service->getApiUser($this->api_user->api_key));
		$this->assertNotEmpty($this->service->getApiUser($this->api_user->api_key, false));
		
		// (re)enable
		$this->assertTrue($this->service->enableAPIUser($this->api_user->api_key));
		$this->assertEquals($this->service->getApiUser($this->api_user->api_key), $this->api_user);
	}
}
