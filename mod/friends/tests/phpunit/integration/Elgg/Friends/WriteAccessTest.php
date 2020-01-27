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
		_elgg_services()->session->setLoggedInUser($this->user);
	}

	public function down() {
		if ($this->user) {
			$this->user->delete();
		}
		_elgg_services()->session->removeLoggedInUser();
	}

	public function testFriendsInWriteAccessArray() {
		$write_access = get_write_access_array($this->user->guid);
		
		$acl = $this->user->getOwnedAccessCollection('friends');
		$this->assertNotEmpty($acl);
		$this->assertArrayHasKey($acl->getId(), $write_access);
	}
}
