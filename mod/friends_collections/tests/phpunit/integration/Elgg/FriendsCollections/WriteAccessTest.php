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

	public function testFriendsCollectionInWriteAccessArray() {
		
		$special_friends = elgg_create_access_collection('My Special Friends', $this->user->guid, 'friends_collection');
		$this->assertInstanceOf(\ElggAccessCollection::class, $special_friends);
		
		$write_access = elgg_get_write_access_array($this->user->guid);
		
		$this->assertArrayHasKey($special_friends->id, $write_access);
	}
}
