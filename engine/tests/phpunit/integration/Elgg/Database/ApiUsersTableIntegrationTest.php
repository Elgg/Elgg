<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class ApiUsersTableIntegrationTest extends IntegrationTestCase {

	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->apiUsersTable;
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
		$api_user = $this->service->createApiUser();
		$this->assertNotFalse($api_user);
		
		$this->assertEquals($api_user, $this->service->getApiUser($api_user->api_key));
	}
	
	public function testRemoveApiUser() {
		$api_user = $this->service->createApiUser();
		$this->assertNotFalse($api_user);
		
		$this->assertNotEmpty($this->service->removeApiUser($api_user->api_key));
		$this->assertEmpty($this->service->getApiUser($api_user->api_key));
	}
}
