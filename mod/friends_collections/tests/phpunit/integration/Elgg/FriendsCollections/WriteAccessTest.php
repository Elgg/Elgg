<?php

namespace Elgg\FriendsCollections;

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

	public function testFriendsCollectionInWriteAccessArray() {
		
		$special_friends = create_access_collection('My Special Friends', $this->user->guid, 'friends_collection');
		$this->assertNotEmpty($special_friends);
		
		$write_access = get_write_access_array($this->user->guid);
		
		$this->assertArrayHasKey($special_friends, $write_access);
	}
}
