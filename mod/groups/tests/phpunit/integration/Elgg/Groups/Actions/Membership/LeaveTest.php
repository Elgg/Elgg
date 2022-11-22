<?php

namespace Elgg\Groups\Actions\Membership;

use Elgg\ActionResponseTestCase;

class LeaveTest extends ActionResponseTestCase {
	
	use \Elgg\MessageTesting;
	
	/**
	 * @var \ElggGroup
	 */
	protected $group;
	
	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	public function up() {
		parent::up();
		
		$this->group = $this->createGroup();
		$this->user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($this->user);
	}
	
	public function down() {
		// make sure no system/error messages are left behind
		_elgg_services()->system_messages->dumpRegister();
	}
	
	public function testLoggedInUserRequired() {
		_elgg_services()->session_manager->removeLoggedInUser();
		
		$this->expectException(\Elgg\Exceptions\Http\Gatekeeper\LoggedInGatekeeperException::class);
		$this->executeAction('groups/leave');
	}
	
	public function testNoGroupProvided() {
		$response = $this->executeAction('groups/leave');
		
		$this->assertInstanceOf(\Elgg\Http\ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('groups:cantleave'), $response->getContent());
	}
	
	public function testInvalidUserProvided() {
		$other_user = $this->createUser();
		
		$response = $this->executeAction('groups/leave', [
			'user_guid' => $other_user->guid,
			'group_guid' => $this->group->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('actionunauthorized'), $response->getContent());
	}
	
	public function testGroupOwnerCantLeave() {
		_elgg_services()->session_manager->setLoggedInUser($this->group->getOwnerEntity());
		
		$response = $this->executeAction('groups/leave', [
			'user_guid' => $this->group->owner_guid,
			'group_guid' => $this->group->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('groups:cantleave'), $response->getContent());
	}
	
	public function testNonMemberCantLeave() {
		$other_user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($other_user);
		
		$response = $this->executeAction('groups/leave', [
			'user_guid' => $other_user->guid,
			'group_guid' => $this->group->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('groups:cantleave'), $response->getContent());
	}
	
	public function testMemberCanLeave() {
		$this->group->join($this->user);
		$this->assertTrue($this->group->isMember($this->user));
		
		$response = $this->executeAction('groups/leave', [
			'group_guid' => $this->group->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);
		$this->assertEquals(REFERRER, $response->getForwardURL());

		$this->assertSystemMessageEmitted(elgg_echo('groups:left'));
		
		$this->assertFalse($this->group->isMember($this->user));
	}
}
