<?php

namespace Elgg\Friends;

use Elgg\Plugins\PluginTesting;

/**
 * @group IntegrationTests
 */
class WriteAccessTest extends \Elgg\IntegrationTestCase {
	use PluginTesting;
	
	/**
	 * @var \ElggUser
	 */
	protected $user;
	
	public function up() {
		$this->user = $this->createUser();
		_elgg_services()->session_manager->setLoggedInUser($this->user);
	}

	public function testFriendsInWriteAccessArray() {
		$write_access = elgg_get_write_access_array($this->user->guid);
		
		$acl = $this->user->getOwnedAccessCollection('friends');
		$this->assertNotEmpty($acl);
		$this->assertArrayHasKey($acl->getId(), $write_access);
	}
}
