<?php

namespace Elgg\Database;

use Elgg\IntegrationTestCase;

class SessionHandlerIntegrationTest extends IntegrationTestCase {
	
	/**
	 * @var SessionHandler
	 */
	protected $handler;
	
	public function up() {
		$this->handler = new SessionHandler(_elgg_services()->db);
	}
	
	public function testReadWriteDestroy() {
		$id = md5(microtime(true));
		$data = $this->faker()->text();
		
		// no data should exist yet
		$this->assertEmpty($this->handler->read($id));
		
		// write some new data
		$this->assertTrue($this->handler->write($id, $data));
		
		// verify same data
		$this->assertEquals($data, $this->handler->read($id));
		
		// remove the data
		$this->assertTrue($this->handler->destroy($id));
		
		// no data should exist any more
		$this->assertEmpty($this->handler->read($id));
	}
	
	public function testGC() {
		$expired_ids = [];
		$active_ids = [];
		
		for ($i = 0; $i < 5; $i++) {
			$now = new \DateTime();
			$data = $this->faker()->text();
			$random_minutes = rand(250, 1000);
			
			// save data in the past
			$expired_id = md5(microtime(true));
			$past = $now->modify("-{$random_minutes} minutes");
			$this->handler->setCurrentTime($past);
			$this->assertTrue($this->handler->write($expired_id, $data));
			$expired_ids[] = $expired_id;
			
			// save data in the future
			$active_id = md5(microtime(true));
			$future = $now->modify("+{$random_minutes} minutes");
			$this->handler->setCurrentTime($future);
			$this->assertTrue($this->handler->write($active_id, $data));
			$active_ids[] = $active_id;
		}
		
		// reset time to now
		$this->handler->setCurrentTime();
		
		// remove all data older than 4 hours
		$this->handler->gc(14400);
		
		// check all expired data (should have been removed)
		foreach ($expired_ids as $id) {
			$this->assertEmpty($this->handler->read($id), "Session data for ID: {$id} wasn't cleaned");
		}
		
		// check all active data (should still exist)
		foreach ($active_ids as $id) {
			$this->assertNotEmpty($this->handler->read($id), "Session data for ID: {$id} was cleaned");
		}
	}
}
