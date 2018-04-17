<?php

namespace Elgg\Di;

use Elgg\UnitTestCase;

/**
 * @group DI
 */
class ServiceFacadeTest extends UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testCanAccessService() {

		elgg()->set('foo', new ServiceFacadeTestService());

		$this->assertTrue(elgg()->has('foo'));
		$this->assertInstanceOf(ServiceFacadeTestService::class, elgg()->foo);

		$this->assertEquals('foo', ServiceFacadeTestService::name());
		$this->assertSame(elgg()->foo, ServiceFacadeTestService::instance());
		$this->assertEquals('Hi, John Doe', ServiceFacadeTestService::call('greet', 'John Doe'));
		$this->assertEquals('Hi, John Doe', ServiceFacadeTestService::instance()->greet('John Doe'));
	}
}

class ServiceFacadeTestService {
	use ServiceFacade;

	public static function name() {
		return 'foo';
	}

	public function greet($name) {
		return "Hi, $name";
	}
}
