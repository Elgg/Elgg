<?php

namespace Elgg\Helpers\Di;

use Elgg\Traits\Di\ServiceFacade;

/**
 * @see Elgg\Di\ServiceFacadeTest
 */
class ServiceFacadeTestService {
	
	use ServiceFacade;
	
	public static function name(): string {
		return 'foo';
	}
	
	public function greet($name) {
		return "Hi, $name";
	}
}
