<?php

namespace Elgg;

/**
 * @group UnitTests
 */
class ContextUnitTest extends \Elgg\UnitTestCase {

	protected $context;
	
	public function up() {
		$request = $this->prepareHttpRequest('');

		$this->context = new Context($request);
		$this->context->pop();

		// resetting global state
		_elgg_services()->setValue('context', $this->context);
	}

	public function down() {

	}

	public function testPeekAndPopReturnNullByDefault() {
		$this->assertNull($this->context->peek());
		$this->assertNull($this->context->pop());

		// testing global state
		$this->assertNull(elgg_get_context());
		$this->assertNull(elgg_pop_context());
	}

	public function testPopReturnsAndRemovesTheMostRecentlyPushedContext() {
		$this->context->push('foo');
		$this->context->push('bar');

		$this->assertEquals('bar', $this->context->pop());
		$this->assertEquals('foo', $this->context->pop());

		// testing global state
		elgg_push_context('foo');
		elgg_push_context('bar');

		$this->assertEquals('bar', elgg_pop_context());
		$this->assertEquals('foo', elgg_pop_context());
	}

	public function testSetReplacesTheMostRecentlyPushedContext() {
		$this->context->push('foo');
		$this->context->set('bar');

		$this->assertNotTrue($this->context->contains('foo'));
		$this->assertEquals('bar', $this->context->pop());
		$this->assertNotEquals('foo', $this->context->pop());

		// testing global state
		elgg_push_context('foo');
		elgg_set_context('bar');

		$this->assertNotTrue(elgg_in_context('foo'));
		$this->assertEquals('bar', elgg_pop_context());
		$this->assertNotEquals('foo', elgg_pop_context());
	}

	public function testPeekReturnsTheMostRecentlyPushedContext() {
		$this->context->push('foo');
		$this->assertEquals('foo', $this->context->peek());
		$this->context->push('bar');
		$this->assertEquals('bar', $this->context->peek());
		$this->context->pop();
		$this->assertEquals('foo', $this->context->peek());

		// testing global state
		elgg_push_context('foo');
		$this->assertEquals('foo', elgg_get_context());
		elgg_push_context('bar');
		$this->assertEquals('bar', elgg_get_context());
		elgg_pop_context();
		$this->assertEquals('foo', elgg_get_context());
	}

	public function testContainsTellsYouIfAGivenContextIsInTheCurrentStack() {
		$this->context->push('foo');
		$this->context->push('bar');
		$this->context->push('baz');

		$this->assertTrue($this->context->contains('foo'));
		$this->assertTrue($this->context->contains('bar'));
		$this->assertTrue($this->context->contains('baz'));

		$popped = $this->context->pop();

		$this->assertFalse($this->context->contains($popped));

		// testing global state
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
		$this->context->set("  ");

		$this->assertNull($this->context->peek());
		$this->assertNull($this->context->pop());

		$this->context->push("  ");
		$this->assertEquals("  ", $this->context->peek());
		$this->assertEquals("  ", $this->context->pop());
	}

	public function testCanGetStack() {
		$this->context->push("123");
		$this->context->push("hello");

		$this->assertEquals(["123", "hello"], $this->context->toArray());
	}

	public function testCanSetStack() {
		$this->context->fromArray([123, "hello", true]);
		$this->assertEquals(["123", "hello", "1"], $this->context->toArray());
	}

	public function testStackAlwaysContainsStrings() {
		$this->context->set(123);
		$this->context->push(true);
		$this->assertEquals(["123", "1"], $this->context->toArray());

		$this->context->fromArray([123, true]);
		$this->assertEquals(["123", "1"], $this->context->toArray());
	}

	public function testSetLowersCase() {
		$this->context->set("HELLO");
		$this->assertEquals("hello", $this->context->peek());
	}

	public function testPushDoesNotAlterCase() {
		$this->context->push("HELLO");
		$this->assertEquals("HELLO", $this->context->peek());
	}

}
