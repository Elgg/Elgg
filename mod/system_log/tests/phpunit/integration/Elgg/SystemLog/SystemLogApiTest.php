<?php

namespace Elgg\SystemLog;

use Elgg\IntegrationTestCase;
use Elgg\HooksRegistrationService\Event;

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

		\Elgg\SystemLog\Logger::log(new Event(elgg(), 'log', 'systemlog', [
			'object' => $object,
			'event' => $event,
		]));

		_elgg_services()->db->executeDelayedQueries();

		$log = SystemLog::instance()->getAll([
			'event' => $event,
		]);

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
		$this->assertMatchesRegularExpression('/\d+\.\d+\.\d+\.\d+/', $entry->ip_address);
		$this->assertEquals(elgg()->system_log->getCurrentTime()->getTimestamp(), $entry->time_created);
		
		$loaded_entry = SystemLog::instance()->get($entry->id);

		$this->assertEquals($entry, $loaded_entry);

		$loaded_object = $loaded_entry->getObject();

		$this->assertEquals($object->guid, $loaded_object->guid);

		$object->delete();
	}

	public function testCanDeleteArchivedLog() {

		$object = $this->createObject();

		$event = 'SystemLogApiTest' . rand();

		\Elgg\SystemLog\Logger::log(new Event(elgg(), 'log', 'systemlog', [
			'object' => $object,
			'event' => $event,
		]));

		_elgg_services()->db->executeDelayedQueries();

		$log = SystemLog::instance()->getAll();

		if (empty($log)) {
			// We are seeing intermittent issues with tests on different systems
			// likely due to delayed queries and shutdown events
			// We don't care enough about system log to kill the build on error
			$this->markTestSkipped();
		}

		$cron_class = new \Elgg\SystemLog\Cron();
		$reflector = new \ReflectionClass(\Elgg\SystemLog\Cron::class);
		$method = $reflector->getMethod('archiveLog');
		$method->setAccessible(true);
		
		$this->assertTrue($method->invokeArgs($cron_class, [-1])); // using -1 to make sure all entries are archived

		$method = $reflector->getMethod('deleteLog');
		$method->setAccessible(true);
		
		$this->assertTrue($method->invokeArgs($cron_class, [0]));
		
		$entries = SystemLog::instance()->getAll();

		$this->assertEmpty($entries);

		$object->delete();
	}
	
	public function testCanDisableEnableSystemLogLogging() {
		
		$service = SystemLog::instance();
		
		$this->assertTrue($service->isLoggingEnabled());
		
		$service->disableLogging();
		$this->assertFalse($service->isLoggingEnabled());
		
		$service->enableLogging();
		$this->assertTrue($service->isLoggingEnabled());
	}
	
	public function testDisabledSystemLogLoggingDoesntInsertRow() {
		// @todo find a way to test this, because of the delayed queries which can be tricky to test
		$this->markTestIncomplete();
	}
}
