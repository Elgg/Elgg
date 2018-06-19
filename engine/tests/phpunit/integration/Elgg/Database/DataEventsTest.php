<?php

namespace Elgg\Database;

use Elgg\Event;
use Elgg\IntegrationTestCase;

/**
 * @group Database
 * @group Events
 */
class DataEventsTest extends IntegrationTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testEntityLifecycleEventsAreCalled() {
		$calls = [
			'create:before' => 0,
			'create' => 0,
			'create:after' => 0,
			'update:before' => 0,
			'update' => 0,
			'update:after' => 0,
			'delete:before' => 0,
			'delete' => 0,
			'delete:after' => 0,
		];

		elgg_register_event_handler('all', 'object', function(Event $event) use (&$calls) {
			$name = $event->getName();
			if (isset($calls[$name])) {
				$calls[$name]++;
			}
		});

		elgg_call(ELGG_IGNORE_ACCESS, function() {
			$object = $this->createObject();

			$object->access_id = 5;
			$object->save();

			$object->delete();
		});

		foreach ($calls as $event => $count) {
			$fail = "$event was triggered $count times instead of expected 1";
			$this->assertEquals(1, $count, $fail);
		}
	}

	public function testMetadataLifecycleEventsAreCalled() {
		$calls = [
			'create:before' => 0,
			'create' => 0,
			'create:after' => 0,
			'update:before' => 0,
			'update' => 0,
			'update:after' => 0,
			'delete:before' => 0,
			'delete' => 0,
			'delete:after' => 0,
		];

		$object = $this->createObject();

		elgg_register_event_handler('all', 'metadata', function(Event $event) use (&$calls) {
			$name = $event->getName();
			if (isset($calls[$name])) {
				$calls[$name]++;
			}
		});

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
			$metadata = new \ElggMetadata();
			$metadata->name = 'foo';
			$metadata->value = 'bar';
			$metadata->entity_guid = $object->guid;

			$metadata->save();

			$metadata->value = 'bar1';
			$metadata->save();

			$metadata->delete();
		});

		foreach ($calls as $event => $count) {
			$fail = "$event was triggered $count times instead of expected 1";
			$this->assertEquals(1, $count, $fail);
		}
	}

	public function testAnnotationLifecycleEventsAreCalled() {
		$calls = [
			'create:before' => 0,
			'create' => 0,
			'create:after' => 0,
			'update:before' => 0,
			'update' => 0,
			'update:after' => 0,
			'delete:before' => 0,
			'delete' => 0,
			'delete:after' => 0,
		];

		$object = $this->createObject();

		elgg_register_event_handler('all', 'annotation', function(Event $event) use (&$calls) {
			$name = $event->getName();
			if (isset($calls[$name])) {
				$calls[$name]++;
			}
		});

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
			$ann = new \ElggAnnotation();
			$ann->name = 'foo';
			$ann->value = 'bar';
			$ann->entity_guid = $object->guid;
			$ann->access_id = ACCESS_PRIVATE;

			$ann->save();

			$ann->value = 'bar1';
			$ann->access_id = ACCESS_PUBLIC;
			$ann->save();

			$ann->delete();
		});

		foreach ($calls as $event => $count) {
			$fail = "$event was triggered $count times instead of expected 1";
			$this->assertEquals(1, $count, $fail);
		}
	}

	public function testRelationshipLifecycleEventsAreCalled() {
		$calls = [
			'create:before' => 0,
			'create' => 0,
			'create:after' => 0,
			'delete:before' => 0,
			'delete' => 0,
			'delete:after' => 0,
		];

		$object = $this->createObject();
		$subject = $object->getOwnerEntity();

		elgg_register_event_handler('all', 'relationship', function(Event $event) use (&$calls) {
			$name = $event->getName();
			if (isset($calls[$name])) {
				$calls[$name]++;
			}
		});

		elgg_call(ELGG_IGNORE_ACCESS, function() use ($object, $subject) {
			$rel = new \ElggRelationship();
			$rel->guid_one = $object->guid;
			$rel->relationship = 'belongs';
			$rel->guid_two = $subject->guid;

			$rel->save();

			$rel->delete();
		});

		foreach ($calls as $event => $count) {
			$fail = "$event was triggered $count times instead of expected 1";
			$this->assertEquals(1, $count, $fail);
		}
	}

}