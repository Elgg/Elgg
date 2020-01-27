<?php

namespace Elgg\WebServices;

use Elgg\IntegrationTestCase;

/**
 * @group WebServices
 */
class ElggApiKeyIntegrationTest extends IntegrationTestCase {
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		
	}
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
	public function down() {
		
	}
	
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
		
		$this->assertFalse(get_api_user($keys->api_key));
	}
	
	public function testDelete() {
		
		$admin = $this->getAdmin();
		$session = elgg_get_session();
		$session->setLoggedInUser($admin);
		
		/* @var $entity \ElggApiKey */
		$entity = $this->createObject([
			'subtype' => \ElggApiKey::SUBTYPE,
		]);
		
		$this->assertInstanceOf(\ElggApiKey::class, $entity);
		
		$keys = $entity->getKeys();
		$this->assertNotEmpty($keys);
		
		$this->assertTrue($entity->delete());
		
		$this->assertFalse(get_api_user($keys->api_key));
		
		$session->removeLoggedInUser();
	}
}
