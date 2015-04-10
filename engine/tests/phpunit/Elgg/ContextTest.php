<?php
namespace Elgg;

class ContextTest extends \PHPUnit_Framework_TestCase {
	public function testPeekAndPopReturnNullByDefault() {
		$context = new Context();
		
		$this->assertNull($context->peek());
		$this->assertNull($context->pop());
		
		// TODO: remove once global state is fully deprecated (2.0)
		_elgg_services()->setValue('context', new Context());
		
		$this->assertNull(elgg_get_context());
		$this->assertNull(elgg_pop_context());
	}
	
	public function testPopReturnsAndRemovesTheMostRecentlyPushedContext() {
		$context = new Context();

		$context->push('foo');
		$context->push('bar');
		
		$this->assertEquals('bar', $context->pop());
		$this->assertEquals('foo', $context->pop());
		
		// TODO: remove once global state is fully deprecated (2.0)
		_elgg_services()->setValue('context', new Context());

		elgg_push_context('foo');
		elgg_push_context('bar');
		
		$this->assertEquals('bar', elgg_pop_context());
		$this->assertEquals('foo', elgg_pop_context());
	}
	
	public function testSetReplacesTheMostRecentlyPushedContext() {
		$context = new Context();
		
		$context->push('foo');
		$context->set('bar');
		
		$this->assertNotTrue($context->contains('foo'));
		$this->assertEquals('bar', $context->pop());
		$this->assertNotEquals('foo', $context->pop());
		
		// TODO: remove once global state is fully deprecated (2.0)
		_elgg_services()->setValue('context', new Context());

		elgg_push_context('foo');
		elgg_set_context('bar');
		
		$this->assertNotTrue(elgg_in_context('foo'));
		$this->assertEquals('bar', elgg_pop_context());
		$this->assertNotEquals('foo', elgg_pop_context());
	}
	
	public function testPeekReturnsTheMostRecentlyPushedContext() {
		$context = new Context();
		
		$context->push('foo');
		$this->assertEquals('foo', $context->peek());
		$context->push('bar');
		$this->assertEquals('bar', $context->peek());
		$context->pop();
		$this->assertEquals('foo', $context->peek());

		// TODO: remove once global state is fully deprecated (2.0)
		_elgg_services()->setValue('context', new Context());
		
		elgg_push_context('foo');
		$this->assertEquals('foo', elgg_get_context());
		elgg_push_context('bar');
		$this->assertEquals('bar', elgg_get_context());
		elgg_pop_context();
		$this->assertEquals('foo', elgg_get_context());
	}
	
	public function testContainsTellsYouIfAGivenContextIsInTheCurrentStack() {
		$context = new Context();
		
		$context->push('foo');
		$context->push('bar');
		$context->push('baz');
		
		$this->assertTrue($context->contains('foo'));
		$this->assertTrue($context->contains('bar'));
		$this->assertTrue($context->contains('baz'));
		
		$popped = $context->pop();
		
		$this->assertFalse($context->contains($popped));

		// TODO: remove once global state is fully deprecated (2.0)
		_elgg_services()->setValue('context', new Context());
		
		elgg_push_context('foo');
		elgg_push_context('bar');
		elgg_push_context('baz');
		
		$this->assertTrue(elgg_in_context('foo'));
		$this->assertTrue(elgg_in_context('bar'));
		$this->assertTrue(elgg_in_context('baz'));
		
		$popped = elgg_pop_context();
		
		$this->assertFalse(elgg_in_context($popped));
	}

	public function testFailToSetEmptyContext() {
		$context = new Context();

		$context->set("  ");

		$this->assertNull($context->peek());
		$this->assertNull($context->pop());

		$context->push("  ");
		$this->assertEquals("  ", $context->peek());
		$this->assertEquals("  ", $context->pop());
	}

	public function testCanGetStack() {
		$context = new Context();

		$context->push("123");
		$context->push("hello");

		$this->assertEquals(["123", "hello"], $context->toArray());
	}

	public function testCanSetStack() {
		$context = new Context();

		$context->fromArray([123, "hello", true]);
		$this->assertEquals(["123", "hello", "1"], $context->toArray());
	}

	public function testStackAlwaysContainsStrings() {
		$context = new Context();

		$context->set(123);
		$context->push(true);
		$this->assertEquals(["123", "1"], $context->toArray());

		$context->fromArray([123, true]);
		$this->assertEquals(["123", "1"], $context->toArray());
	}

	public function testSetLowersCase() {
		$context = new Context();

		$context->set("HELLO");
		$this->assertEquals("hello", $context->peek());
	}

	public function testPushDoesNotAlterCase() {
		$context = new Context();

		$context->push("HELLO");
		$this->assertEquals("HELLO", $context->peek());
	}
}