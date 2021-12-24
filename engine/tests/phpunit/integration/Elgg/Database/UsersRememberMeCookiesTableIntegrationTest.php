<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class UsersRememberMeCookiesTableIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @var UsersRememberMeCookiesTable
	 */
	protected $service;
	
	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	public function up() {
		$this->service = _elgg_services()->users_remember_me_cookies_table;
		$this->user = $this->createUser();
	}
	
	public function testInsertHash() {
		$this->assertEquals(0, $this->service->insertHash($this->user, 'foo'));
		
		$row = $this->service->getRowFromHash('foo');
		$this->assertNotEmpty($row);
		$this->assertEquals($this->user->guid, $row->guid);
		$this->assertEquals('foo', $row->code);
	}
	
	public function testInsertDuplicateHash() {
		$other_user = $this->createUser();
		$this->assertEquals(0, $this->service->insertHash($this->user, 'foo'));
		$this->assertEquals(0, $this->service->insertHash($other_user, 'foo'));
		
		$row = $this->service->getRowFromHash('foo');
		$this->assertNotEmpty($row);
		$this->assertEquals($other_user->guid, $row->guid);
		$this->assertEquals('foo', $row->code);
	}
	
	public function testGetRowFromInvalidHash() {
		$this->assertEmpty($this->service->getRowFromHash('bar'));
	}
	
	public function testUpdateHash() {
		$this->service->setCurrentTime($this->service->getCurrentTime('-10 minutes'));
		
		$this->assertEquals(0, $this->service->insertHash($this->user, 'foo'));
		
		$row = $this->service->getRowFromHash('foo');
		$this->assertNotEmpty($row);
		$this->assertEquals($this->user->guid, $row->guid);
		$this->assertEquals('foo', $row->code);
		
		$this->service->setCurrentTime();
		
		$this->assertTrue($this->service->updateHash($this->user, 'foo'));
		
		$updated_row = $this->service->getRowFromHash('foo');
		$this->assertNotEmpty($updated_row);
		$this->assertEquals($this->user->guid, $updated_row->guid);
		$this->assertEquals('foo', $updated_row->code);
		
		$this->assertNotEquals($updated_row->timestamp, $row->timestamp);
	}
	
	public function testDeleteHash() {
		$this->assertEquals(0, $this->service->insertHash($this->user, 'foo'));
		
		$this->assertNotEmpty($this->service->getRowFromHash('foo'));
		
		$this->assertGreaterThan(0, $this->service->deleteHash('foo'));
		
		$this->assertEmpty($this->service->getRowFromHash('foo'));
	}
	
	public function testDeleteInvalidHash() {
		$this->assertEmpty($this->service->getRowFromHash('foo'));
		
		$this->assertEmpty($this->service->deleteHash('foo'));
		
		$this->assertEmpty($this->service->getRowFromHash('foo'));
	}
	
	public function testDeleteAllHashes() {
		$other_user = $this->createUser();
		$this->assertEquals(0, $this->service->insertHash($this->user, 'foo'));
		$this->assertEquals(0, $this->service->insertHash($other_user, 'bar'));
		
		$this->assertNotEmpty($this->service->getRowFromHash('foo'));
		$this->assertNotEmpty($this->service->getRowFromHash('bar'));
		
		$this->assertGreaterThan(0, $this->service->deleteAllHashes($this->user));
		
		$this->assertEmpty($this->service->getRowFromHash('foo'));
		$this->assertNotEmpty($this->service->getRowFromHash('bar'));
	}
	
	public function testDeleteAllHashesWhenNonStored() {
		$this->assertEquals(0, $this->service->insertHash($this->user, 'foo'));
		
		$this->assertNotEmpty($this->service->getRowFromHash('foo'));
		
		$this->assertEmpty($this->service->deleteAllHashes($this->createUser()));
		
		$this->assertNotEmpty($this->service->getRowFromHash('foo'));
	}
	
	public function testDeleteExpiredHashes() {
		// expired token
		$this->service->setCurrentTime($this->service->getCurrentTime('-10 years'));
		$this->assertEquals(0, $this->service->insertHash($this->user, 'foo'));
		
		// valid token
		$this->service->setCurrentTime();
		$this->assertEquals(0, $this->service->insertHash($this->user, 'bar'));
		
		$this->assertNotEmpty($this->service->getRowFromHash('foo'));
		$this->assertNotEmpty($this->service->getRowFromHash('bar'));
		
		$this->assertGreaterThan(0, $this->service->deleteExpiredHashes(time() - 3600));
		
		$this->assertEmpty($this->service->getRowFromHash('foo'));
		$this->assertNotEmpty($this->service->getRowFromHash('bar'));
	}
}
