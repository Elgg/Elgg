<?php

namespace Elgg\Groups\Actions\Membership;

use Elgg\ActionResponseTestCase;

class JoinTest extends ActionResponseTestCase {
	
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
		$this->executeAction('groups/join');
	}
	
	public function testNoGroupProvided() {
		$response = $this->executeAction('groups/join');
		
		$this->assertInstanceOf(\Elgg\Http\ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('groups:cantjoin'), $response->getContent());
	}
	
	public function testInvalidUserProvided() {
		$other_user = $this->createUser();
		
		$response = $this->executeAction('groups/join', [
			'user_guid' => $other_user->guid,
			'group_guid' => $this->group->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('actionunauthorized'), $response->getContent());
	}
	
	public function testCanJoinPublicGroup() {
		$this->group->membership = ACCESS_PUBLIC;
		$this->assertTrue($this->group->isPublicMembership());
		
		$response = $this->executeAction('groups/join', [
			'group_guid' => $this->group->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);
		$this->assertEquals($this->group->getURL(), $response->getForwardURL());
				
		$this->assertSystemMessageEmitted(elgg_echo('groups:joined'));
		
		$this->assertTrue($this->group->isMember($this->user));
	}
	
	public function testGroupOwnerCanJoinUserOnPublicGroup() {
		$this->group->membership = ACCESS_PUBLIC;
		$this->assertTrue($this->group->isPublicMembership());
		
		_elgg_services()->session_manager->setLoggedInUser($this->group->getOwnerEntity());
		$response = $this->executeAction('groups/join', [
			'group_guid' => $this->group->guid,
			'user_guid' => $this->user->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);
		$this->assertEquals($this->group->getURL(), $response->getForwardURL());
		
		$this->assertSystemMessageEmitted(elgg_echo('groups:joined'));
		
		$this->assertTrue($this->group->isMember($this->user));
	}
	
	public function testCanJoinClosedGroupIfInvited() {
		$this->group->membership = ACCESS_PRIVATE;
		$this->assertFalse($this->group->isPublicMembership());
		
		$this->assertTrue($this->group->addRelationship($this->user->guid, 'invited'));
		
		$response = $this->executeAction('groups/join', [
			'group_guid' => $this->group->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);
		$this->assertEquals($this->group->getURL(), $response->getForwardURL());
		
		$this->assertSystemMessageEmitted(elgg_echo('groups:joined'));
		
		$this->assertTrue($this->group->isMember($this->user));
	}
	
	public function testCantHaveMultipleMembershipRequests() {
		$this->group->membership = ACCESS_PRIVATE;
		$this->assertFalse($this->group->isPublicMembership());
		
		$this->assertTrue($this->user->addRelationship($this->group->guid, 'membership_request'));
		
		$response = $this->executeAction('groups/join', [
			'group_guid' => $this->group->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\ErrorResponse::class, $response);
		$this->assertEquals(elgg_echo('groups:joinrequest:exists'), $response->getContent());
	}
	
	public function testCanRequestMembershipForClosedGroup() {
		$this->group->membership = ACCESS_PRIVATE;
		$this->assertFalse($this->group->isPublicMembership());
		
		$response = $this->executeAction('groups/join', [
			'group_guid' => $this->group->guid,
		]);
		
		$this->assertInstanceOf(\Elgg\Http\OkResponse::class, $response);
		$this->assertEquals(REFERRER, $response->getForwardURL());
		
		$this->assertSystemMessageEmitted(elgg_echo('groups:joinrequestmade'));
		
		$this->assertTrue($this->user->hasRelationship($this->group->guid, 'membership_request'));
	}
}
