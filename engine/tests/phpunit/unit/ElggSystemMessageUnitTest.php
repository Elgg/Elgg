<?php
/**
 * Tests the ElggSystemMessages class
 */
class ElggSystemMessageUnitTest extends \Elgg\UnitTestCase {
	
	public function testConstructor() {
		$message = new \ElggSystemMessage('foo');
		$this->assertEquals('foo', $message->getMessage());
		$this->assertEquals('success', $message->getType());

		$message = new \ElggSystemMessage('foo2', 'info');
		$this->assertEquals('foo2', $message->getMessage());
		$this->assertEquals('info', $message->getType());
		$this->assertEquals([], $message->getVars());
	}

	public function testFactory() {
		$message = \ElggSystemMessage::factory([
			'message' => 'foo_message',
			'type' => 'foo_type',
			'ttl' => 15,
			'random_data' => 'random_value',
		]);
		
		$this->assertInstanceOf(\ElggSystemMessage::class, $message);
		
		$this->assertEquals('foo_message', $message->getMessage());
		$this->assertEquals('foo_type', $message->getType());
		$this->assertEquals(15, $message->getTtl());
		
		$this->assertEquals('overwritten', $message->getVars(['random_data' => 'overwritten'])['random_data']);
		$this->assertEquals('random_value', $message->getVars()['random_data']);
		$this->assertArrayNotHasKey('ttl', $message->getVars());
		
		$message->setMessage('bar_message');
		$this->assertEquals('bar_message', $message->getMessage());
		
		$message->setType('bar_type');
		$this->assertEquals('bar_type', $message->getType());
		
		$message->setTtl(25);
		$this->assertEquals(25, $message->getTtl());
	}

	public function testToString() {
		$message = new \ElggSystemMessage('foo');
		$this->assertEquals('foo', (string) $message);
	}
}
