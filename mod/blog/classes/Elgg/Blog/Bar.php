<?php

namespace Elgg\Blog;

class Bar {

	private $foo;

	public function __construct(Foo $foo) {
		$this->foo = $foo;
	}

	public function test ($test) {
		$this->foo->test("Calling Bar::test() with value: $test");
	}

}
