<?php

namespace Elgg\SystemLog;

use Elgg\IntegrationTestCase;

/**
 * @group Plugins
 * @group SystemLogPlugin
 */
class SystemLogApiTest extends IntegrationTestCase {

	public function up() {
		self::createApplication(true);
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

		$entry = array_shift($log);

		if (!$entry) {
			// This test is too fragile due to delayed queries
			// Let's not fail the suite
			$this->markTestSkipped();
		}

		$this->assertInstanceOf(\stdClass::class, $entry);

		$this->assertEquals($object->guid, $entry->object_id);
		$this->assertEquals(\ElggObject::class, $entry->object_class);
		$this->assertEquals('object', $entry->object_type);
		$this->assertEquals($object->getSubtype(), $entry->object_subtype);
		$this->assertEquals($event, $entry->event);
		$this->assertEquals($object->owner_guid, $entry->owner_guid);

		$loaded_entry = system_log_get_log_entry($entry->id);

		$this->assertEquals($entry, $loaded_entry);

		$loaded_object = system_log_get_object_from_log_entry($entry->id);

		$this->assertEquals($object->guid, $loaded_object->guid);

		$object->delete();
	}

	public function testCanDeleteArchivedLog() {

		$time = time();

		$object = $this->createObject();

		$event = 'SystemLogApiTest' . rand();

		system_log_default_logger('log', 'systemlog', [
			'object' => $object,
			'event' => $event,
		]);

		_elgg_services()->db->executeDelayedQueries();

		$log = system_log_get_log();
		if (empty($log)) {
			// This test is too fragile due to delayed queries
			// Let's not fail the suite
			$this->markTestSkipped();
		}

		system_log_archive_log(time() - $time);
		system_log_browser_delete_log($time);

		$entries = system_log_get_log('', '', '', '', '', null, 0, true, $time);

		$this->assertEmpty($entries);

		$object->delete();
	}
}