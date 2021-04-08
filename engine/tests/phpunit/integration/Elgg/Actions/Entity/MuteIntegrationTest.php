<?php

namespace Elgg\Actions\Entity;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

class MuteIntegrationTest extends ActionResponseTestCase {
	
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
		$response = $this->executeAction('entity/mute');
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('error:missing_data'), $response->getContent());
	}
	
	public function testMute() {
		$response = $this->executeAction('entity/mute', [
			'guid' => $this->entity->guid,
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		$this->assertTrue($this->entity->hasMutedNotifications($this->user->guid));
	}
}
