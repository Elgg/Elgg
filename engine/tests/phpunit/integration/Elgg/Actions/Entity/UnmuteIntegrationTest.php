<?php

namespace Elgg\Actions\Entity;

use Elgg\ActionResponseTestCase;
use Elgg\Http\ErrorResponse;
use Elgg\Http\OkResponse;

class UnmuteIntegrationTest extends ActionResponseTestCase {
	
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
		parent::up();
		
		_elgg_services()->notifications->registerMethod('apples');
		_elgg_services()->notifications->registerMethod('bananas');
		
		$this->entity = $this->createObject();
		$this->user = $this->createUser();
		
		_elgg_services()->session_manager->setLoggedInUser($this->user);
	}

	public function testFailsWithMissingEntityGUID() {
		$response = $this->executeAction('entity/unmute');
		
		$this->assertInstanceOf(ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('error:missing_data'), $response->getContent());
	}
	
	public function testMute() {
		$this->entity->muteNotifications($this->user->guid);
		
		$response = $this->executeAction('entity/unmute', [
			'guid' => $this->entity->guid,
		]);
		
		$this->assertInstanceOf(OkResponse::class, $response);
		
		$this->assertFalse($this->entity->hasMutedNotifications($this->user->guid));
	}
}
