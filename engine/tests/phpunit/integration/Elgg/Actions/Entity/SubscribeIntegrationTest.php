<?php

namespace Elgg\Actions\Entity;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

class SubscribeIntegrationTest extends ActionResponseTestCase {
	
	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	/**
	 * @var \ElggEntity
	 */
	protected $entity;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		_elgg_services()->notifications->registerMethod('apples');
		_elgg_services()->notifications->registerMethod('bananas');
		
		$this->entity = $this->createObject();
		$this->user = $this->createUser();
		$this->user->setNotificationSetting('apples', true);
		$this->user->setNotificationSetting('bananas', false);
		
		_elgg_services()->session->setLoggedInUser($this->user);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function down() {
		if ($this->user instanceof \ElggUser) {
			$this->user->delete();
		}
		
		if ($this->entity instanceof \ElggEntity) {
			$this->entity->delete();
		}
		
		_elgg_services()->session->removeLoggedInUser();
	}
	
	public function testFailsWithMissingEntityGUID() {
		$response = $this->executeAction('entity/subscribe');
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('error:missing_data'), $response->getContent());
	}
	
	public function testGenericSubscribe() {
		$response = $this->executeAction('entity/subscribe', [
			'guid' => $this->entity->guid,
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		$this->assertTrue($this->entity->hasSubscription($this->user->guid, 'apples'));
		$this->assertFalse($this->entity->hasSubscription($this->user->guid, 'bananas'));
	}
	
	public function testSubscribeWithMethods() {
		$response = $this->executeAction('entity/subscribe', [
			'guid' => $this->entity->guid,
			'methods' => ['bananas'],
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		$this->assertFalse($this->entity->hasSubscription($this->user->guid, 'apples'));
		$this->assertTrue($this->entity->hasSubscription($this->user->guid, 'bananas'));
	}
	
	public function testFailsWithInvalidMethod() {
		$response = $this->executeAction('entity/subscribe', [
			'guid' => $this->entity->guid,
			'methods' => ['invalid'],
		]);
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
	}
	
	public function testSubscribeWithTypeSubtypeAction() {
		$response = $this->executeAction('entity/subscribe', [
			'guid' => $this->entity->guid,
			'type' => 'object',
			'subtype' => 'foo',
			'action' => 'bar'
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		$this->assertFalse($this->entity->hasSubscription($this->user->guid, 'apples'));
		$this->assertFalse($this->entity->hasSubscription($this->user->guid, 'bananas'));
		$this->assertTrue($this->entity->hasSubscription($this->user->guid, 'apples', 'object', 'foo', 'bar'));
		$this->assertFalse($this->entity->hasSubscription($this->user->guid, 'bananas', 'object', 'foo', 'bar'));
	}
}
