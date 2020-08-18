<?php

namespace Elgg\Helpers\Di;

/**
 * @see \Elgg\Di\DiContainerUnitTest
 */
class DiContainerTestObject {
	
	public $di;
	
	public function __construct($di = null) {
		$this->di = $di;
	}
	
}
