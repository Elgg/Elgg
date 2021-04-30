<?php

namespace Elgg\Helpers\Di;

use Elgg\Traits\Di\ServiceFacade;

/**
 * @see Elgg\Di\ServiceFacadeTest
 */
class ServiceFacadeTestService {
	
	use ServiceFacade;
	
	public static function name() {
		return 'foo';
	}
	
	public function greet($name) {
		return "Hi, $name";
	}
}
