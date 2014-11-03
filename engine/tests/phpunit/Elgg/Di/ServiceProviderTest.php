<?php
namespace Elgg\Di;

use Elgg\Http\MockSessionStorage;

class ServiceProviderTest extends \PHPUnit_Framework_TestCase {

	public function testCanInstantiateAllServices() {

		$sp = _elgg_create_service_provider();
		$di = require dirname(dirname(dirname(dirname(__DIR__)))) . "/di.php";

		// Some services not compatible with unit test environment...
		$sp->setValue('Elgg\Http\SessionStorage', new MockSessionStorage());
		unset($di['cookies']);
		unset($di['db']);
		unset($di['jsroot']);
		unset($di['notifications']);
		unset($di['persistentLogin']);
		unset($di['Elgg\AmdConfig']);
		unset($di['Elgg\Database']);
		unset($di['Elgg\Queue\Queue']);
		unset($di['Elgg\PersistentLoginService']);
		
		// Create all services without throwing exceptions
		foreach ($di as $key => $definition) {
			$sp->$key; 
		}
	}
}

