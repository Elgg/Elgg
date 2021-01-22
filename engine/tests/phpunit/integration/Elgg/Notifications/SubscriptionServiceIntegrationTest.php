<?php

namespace Elgg\Notifications;

use Elgg\IntegrationTestCase;

class SubscriptionServiceIntegrationTest extends IntegrationTestCase {

	/**
	 * @var SubscriptionsService
	 */
	protected $service;
	
	/**
	 * {@inheritDoc}
	 */
	public function up() {
		$this->service = _elgg_services()->subscriptions;
	}

	/**
	 * {@inheritDoc}
	 */
	public function down() {
		
	}
	
	public function testAddRemoveSubscription() {
		$user = $this->createUser();
		$target = $this->createGroup();
		
		// add subscription
		$this->assertTrue($this->service->addSubscription($user->guid, 'mail', $target->guid));
		
		$expected = [
			$user->guid => ['mail'],
		];
		$this->assertEquals($expected, $this->service->getSubscriptionsForContainer($target->guid, ['mail', 'site']));
		
		// remove subscription
		$this->assertTrue($this->service->removeSubscription($user->guid, 'mail', $target->guid));
		// remove non existing subscription
		$this->assertFalse($this->service->removeSubscription($user->guid, 'site', $target->guid));
		
		// check if all was removed
		$this->assertEmpty($this->service->getSubscriptionsForContainer($target->guid, ['mail', 'site']));
		
		$user->delete();
		$target->delete();
	}
}
