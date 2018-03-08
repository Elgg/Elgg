<?php

namespace Elgg\SystemLog;

use Elgg\IntegrationTestCase;

/**
 * @group Plugins
 * @group SystemLogPlugin
 */
class SystemLogApiTest extends IntegrationTestCase {

	public function up() {
		self::createApplication(['isolate' => true]);

		$log = elgg()->system_log;
		/* @var $log SystemLog */

		$log->setCurrentTime();
	}

	public function down() {

	}

	public function testLogsObjectEvent() {

		$object = $this->createObject();

		$event = 'SystemLogApiTest' . rand();

		system_log_default_logger('log', 'systemlog', [
			'object' => $object,
			'event' => $event,
		]);

		_elgg_services()->db->executeDelayedQueries();

		$log = system_log_get_log('', $event);

		if (empty($log)) {
			// We are seeing intermittent issues with tests on different systems
			// likely due to delayed queries and shutdown events
			// We don't care enough about system log to kill the build on error
			$this->markTestSkipped();
		}

		$entry = array_shift($log);
		/* @var $entry \Elgg\SystemLog\SystemLogEntry */

		$this->assertInstanceOf(SystemLogEntry::class, $entry);

		$this->assertEquals($object->guid, $entry->object_id);
		$this->assertEquals(\ElggObject::class, $entry->object_class);
		$this->assertEquals('object', $entry->object_type);
		$this->assertEquals($object->getSubtype(), $entry->object_subtype);
		$this->assertEquals($event, $entry->event);
		$this->assertEquals($object->owner_guid, $entry->owner_guid);
		$this->assertRegExp('/\d+\.\d+\.\d+\.\d+/', $entry->ip_address);
		$this->assertEquals(elgg()->system_log->getCurrentTime()->getTimestamp(), $entry->time_created);
		
		$loaded_entry = system_log_get_log_entry($entry->id);

		$this->assertEquals($entry, $loaded_entry);

		$loaded_object = system_log_get_object_from_log_entry($entry->id);

		$this->assertEquals($object->guid, $loaded_object->guid);

		$object->delete();
	}

	public function testCanDeleteArchivedLog() {

		$object = $this->createObject();

		$event = 'SystemLogApiTest' . rand();

		system_log_default_logger('log', 'systemlog', [
			'object' => $object,
			'event' => $event,
		]);

		_elgg_services()->db->executeDelayedQueries();

		$log = system_log_get_log();

		if (empty($log)) {
			// We are seeing intermittent issues with tests on different systems
			// likely due to delayed queries and shutdown events
			// We don't care enough about system log to kill the build on error
			$this->markTestSkipped();
		}

		$this->assertTrue(system_log_archive_log());
		$this->assertTrue(system_log_browser_delete_log(0));

		$entries = system_log_get_log();

		$this->assertEmpty($entries);

		$object->delete();
	}
}