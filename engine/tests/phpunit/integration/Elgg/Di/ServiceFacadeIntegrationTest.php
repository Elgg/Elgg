<?php

namespace Elgg\Di;

use Elgg\Helpers\Di\ServiceFacadeTestService;
use Elgg\IntegrationTestCase;

class ServiceFacadeIntegrationTest extends IntegrationTestCase {

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
