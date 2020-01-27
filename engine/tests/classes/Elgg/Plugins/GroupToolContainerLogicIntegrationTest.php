<?php

namespace Elgg\Plugins;

use Elgg\IntegrationTestCase;

/**
 * Test the functionality of the group tool based container logic
 *
 * @since 3.3
 */
abstract class GroupToolContainerLogicIntegrationTest extends IntegrationTestCase {

	/**
	 * @var \ElggGroup
	 */
	protected $group;
	
	/**
	 * @var \ElggUser
	 */
	protected $owner;
	
	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::up()
	 */
	public function up() {
		$this->owner = $this->createUser();
		elgg()->session->setLoggedInUser($this->owner);
		
		$this->group = $this->createGroup();
	}

	/**
	 * {@inheritDoc}
	 * @see \Elgg\BaseTestCase::down()
	 */
	public function down() {
		if ($this->group) {
			$this->group->delete();
		}
		
		if ($this->owner) {
			$this->owner->delete();
		}
		
		elgg()->session->removeLoggedInUser();
	}
	
	/**
	 * Get the type of the content being gated behind the group tool option
	 *
	 * @return string
	 */
	abstract public function getContentType(): string;
	
	/**
	 * Get the subtype of the content being gated behind the group tool option
	 *
	 * @return string
	 */
	abstract public function getContentSubtype(): string;
	
	/**
	 * Get the name of the group tool option
	 *
	 * @return string
	 */
	abstract public function getGroupToolOption(): string;
	
	public function testGroupOwnerCanWriteContentWithToolEnabled() {
		
		$this->assertTrue($this->group->enableTool($this->getGroupToolOption()));
		
		$this->assertTrue($this->group->canWriteToContainer($this->owner->guid, $this->getContentType(), $this->getContentSubtype()));
	}
	
	public function testGroupOwnerCantWriteContentWithToolDisabled() {
		$this->assertTrue($this->group->disableTool($this->getGroupToolOption()));
		
		$this->assertFalse($this->group->canWriteToContainer($this->owner->guid, $this->getContentType(), $this->getContentSubtype()));
	}
	
	public function testUserCantWriteContentWithToolEnabled() {
		$user = $this->createUser();
		
		$this->assertTrue($this->group->enableTool($this->getGroupToolOption()));
		
		$this->assertFalse($this->group->canWriteToContainer($user->guid, $this->getContentType(), $this->getContentSubtype()));
		
		$user->delete();
	}
	
	public function testUserCantWriteContentWithToolDisabled() {
		$user = $this->createUser();
		
		$this->assertTrue($this->group->disableTool($this->getGroupToolOption()));
		
		$this->assertFalse($this->group->canWriteToContainer($user->guid, $this->getContentType(), $this->getContentSubtype()));
		
		$user->delete();
	}
	
	public function testGroupMemberCanWriteContentWithToolEnabled() {
		$user = $this->createUser();
		$this->group->join($user);
		
		$this->assertTrue($this->group->enableTool($this->getGroupToolOption()));
		
		$this->assertTrue($this->group->canWriteToContainer($user->guid, $this->getContentType(), $this->getContentSubtype()));
		
		$user->delete();
	}
	
	public function testGroupMemberCantWriteContentWithToolDisabled() {
		$user = $this->createUser();
		$this->group->join($user);
		
		$this->assertTrue($this->group->disableTool($this->getGroupToolOption()));
		
		$this->assertFalse($this->group->canWriteToContainer($user->guid, $this->getContentType(), $this->getContentSubtype()));
		
		$user->delete();
	}
	
	public function testAdminCanWriteContentWithToolEnabled() {
		$user = $this->getAdmin();
		
		$this->assertTrue($this->group->enableTool($this->getGroupToolOption()));
		
		$this->assertTrue($this->group->canWriteToContainer($user->guid, $this->getContentType(), $this->getContentSubtype()));
		
		$user->delete();
	}
	
	public function testAdminCantWriteContentWithToolDisabled() {
		$user = $this->getAdmin();
		
		$this->assertTrue($this->group->disableTool($this->getGroupToolOption()));
		
		$this->assertFalse($this->group->canWriteToContainer($user->guid, $this->getContentType(), $this->getContentSubtype()));
		
		$user->delete();
	}
	
	public function testAdminMemberCantWriteContentWithToolDisabled() {
		$user = $this->getAdmin();
		$this->group->join($user);
		
		$this->assertTrue($this->group->disableTool($this->getGroupToolOption()));
		
		$this->assertFalse($this->group->canWriteToContainer($user->guid, $this->getContentType(), $this->getContentSubtype()));
		
		$user->delete();
	}
}
