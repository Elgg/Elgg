<?php

namespace Elgg\Helpers;

/**
 * @see \Elgg\DeprecationWrapperUnitTest
 */
class DeprecationWrapperTestObj1 {
	
	public $foo = 'foo';
	
	public function foo() {
		return 'foo';
	}
	
	public function __toString() {
		return 'foo';
	}
}
