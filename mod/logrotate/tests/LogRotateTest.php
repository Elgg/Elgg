<?php

class LogRotateTest extends ElggCoreUnitTest {

	function testCanCatchTableCrashes() {
		$match1 = "'./server/elgg_foo'";
		$match0 = "Table $match1 is marked as crashed";
		$notice_id = "crash_table_" . md5($match1);

		$func = function () use ($match0) {
			throw new DatabaseException("SQLSTATE[HY000]: General error: 145 {$match0} and should be repaired");
		};

		$this->assertFalse(elgg_admin_notice_exists($notice_id));
		$this->assertSame(null, _logrotate_handle_crashed_table($func));
		$this->assertTrue(elgg_admin_notice_exists($notice_id));

		elgg_delete_admin_notice($notice_id);
	}

	function testPassesOnOtherExceptions() {
		$msg = "SQLSTATE[HY000]: Bad query";

		$func = function () use ($msg) {
			throw new DatabaseException($msg);
		};

		try {
			_logrotate_handle_crashed_table($func);
			$this->fail();
		} catch (DatabaseException $e) {
			$this->assertSame($msg, $e->getMessage());
		}
	}
}
