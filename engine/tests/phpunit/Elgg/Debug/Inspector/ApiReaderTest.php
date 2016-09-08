<?php
namespace Elgg\Debug\Inspector;

class ApiReaderTest extends \Elgg\TestCase {

	function testRead() {
		$data = (new ApiReader())->getData();
		$section = $data->sections[0];
		$item = $section->items[0];

		$this->assertSame('api-list', $section->type);
		$this->assertSame('events', $section->id);

		$this->assertSame('elgg_register_event_handler', $item->name);
		$this->assertSame('elgg-register-event-handler', $item->id);
		$this->assertSame('($event, $object_type, $callback, $priority = 500)', $item->args);
		$this->assertSame('Register a callback as an Elgg event handler.', $item->summary);
		$this->assertStringStartsWith('Events are emitted', $item->doc_html);
	}
}