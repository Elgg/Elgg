<?php

namespace Elgg\WebServices;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\IntegrationTestCase;
use Elgg\WebServices\PAM\API\APIKey;
use Elgg\Exceptions\DomainException;

/**
 * @group WebServices
 */
class ElggCoreWebServicesApiTest extends IntegrationTestCase {

	protected $call_method;

	public function up() {
		$this->call_method = _elgg_services()->request->getMethod();
		// Emulate GET request, which is not set in cli mode
		_elgg_services()->request->server->set('REQUEST_METHOD', 'GET');
	}

	/**
	 * Called after each test method.
	 */
	public function down() {
		// Restore original request method
		_elgg_services()->request->server->set('REQUEST_METHOD', $this->call_method);
	}

	// api key methods
	public function testApiAuthenticate() {
		$this->markTestSkipped();
	}

	public function testApiAuthKeyNoKey() {
		$apikey = new APIKey();
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:MissingAPIKey'));
		$apikey();
	}

	public function testApiAuthKeyBadKey() {
		$apikey = new APIKey();
		set_input('api_key', 'BAD');
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:BadAPIKey'));
		$apikey();
	}
	
	public function testApiAuthKeyDisabled() {
		$apikey = new APIKey();
		/* @var $entity \ElggApiKey */
		$entity = $this->createObject([
			'subtype' => \ElggApiKey::SUBTYPE,
		]);
		
		$this->assertInstanceOf(\ElggApiKey::class, $entity);
		
		set_input('api_key', $entity->getPublicKey());
		
		$this->assertTrue($apikey());
		
		$this->assertTrue($entity->disableKeys());
		$this->assertFalse($entity->hasActiveKeys());
		
		$this->expectException(\APIException::class);
		$this->expectExceptionMessage(elgg_echo('APIException:BadAPIKey'));
		$apikey();
	}
}
