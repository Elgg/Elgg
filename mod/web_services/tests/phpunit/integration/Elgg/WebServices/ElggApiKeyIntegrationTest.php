<?php

namespace Elgg\WebServices;

use Elgg\Plugins\IntegrationTestCase;

class ElggApiKeyIntegrationTest extends IntegrationTestCase {

	public function testGetKeyFunctions() {
		
		/* @var $entity \ElggApiKey */
		$entity = $this->createObject([
			'subtype' => \ElggApiKey::SUBTYPE,
		]);
		
		$this->assertInstanceOf(\ElggApiKey::class, $entity);
		
		$keys = $entity->getKeys();
		$this->assertNotEmpty($keys);
		
		$this->assertEquals($keys->api_key, $entity->getPublicKey());
		$this->assertEquals($keys->secret, $entity->getSecretKey());
	}
	
	public function testRegenerateKeys() {
		
		/* @var $entity \ElggApiKey */
		$entity = $this->createObject([
			'subtype' => \ElggApiKey::SUBTYPE,
		]);
		
		$this->assertInstanceOf(\ElggApiKey::class, $entity);
		
		$keys = $entity->getKeys();
		$this->assertNotEmpty($keys);
		
		$this->assertEquals($keys->api_key, $entity->getPublicKey());
		$this->assertEquals($keys->secret, $entity->getSecretKey());
		
		$this->assertTrue($entity->regenerateKeys());
		
		$this->assertNotEmpty($entity->getPublicKey());
		$this->assertNotEmpty($entity->getSecretKey());
		
		$this->assertNotEquals($keys->api_key, $entity->getPublicKey());
		$this->assertNotEquals($keys->secret, $entity->getSecretKey());
		
		$this->assertFalse(_elgg_services()->apiUsersTable->getApiUser($keys->api_key));
	}
	
	public function testDelete() {
		
		$admin = $this->getAdmin();
		_elgg_services()->session_manager->setLoggedInUser($admin);
		
		/* @var $entity \ElggApiKey */
		$entity = $this->createObject([
			'subtype' => \ElggApiKey::SUBTYPE,
		]);
		
		$this->assertInstanceOf(\ElggApiKey::class, $entity);
		
		$keys = $entity->getKeys();
		$this->assertNotEmpty($keys);
		
		$this->assertTrue($entity->delete());
		
		$this->assertFalse(_elgg_services()->apiUsersTable->getApiUser($keys->api_key));
	}
	
	public function testEnableDisableKeys() {
		
		/* @var $entity \ElggApiKey */
		$entity = $this->createObject([
			'subtype' => \ElggApiKey::SUBTYPE,
		]);
		
		$this->assertInstanceOf(\ElggApiKey::class, $entity);
		
		$this->assertTrue($entity->hasActiveKeys());
		
		$this->assertTrue($entity->disableKeys());
		$this->assertFalse($entity->hasActiveKeys());
		
		$this->assertTrue($entity->enableKeys());
		$this->assertTrue($entity->hasActiveKeys());
	}
}
