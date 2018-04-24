<?php

namespace Elgg\Upgrades;

use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Creates user friends access collection and migrates entity access_id
 */
class MigrateFriendsACL implements AsynchronousUpgrade {

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2017121200;
	}

	/**
	 * {@inheritdoc}
	 */
	public function needsIncrementOffset() {
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function shouldBeSkipped() {
		return empty($this->countItems());
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		// users without a friends acl
		$db = elgg()->db;
		
		return elgg_get_entities([
			'type' => 'user',
			'count' => true,
			'wheres' => [
				"e.guid NOT IN (
					SELECT acl.owner_guid FROM {$db->prefix}access_collections acl WHERE acl.subtype = 'friends'
				)",
			],
		]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {
		
		$db = elgg()->db;
		
		$users = elgg_get_entities([
			'type' => 'user',
			'limit' => 1,
			'offset' => $offset,
			'wheres' => [
				"e.guid NOT IN (
					SELECT acl.owner_guid FROM {$db->prefix}access_collections acl WHERE acl.subtype = 'friends'
				)",
			],
		]);
		
		if (empty($users)) {
			// mark as complete
			$result->addSuccesses(1);
			return $result;
		}
		
		$user = $users[0];
		
		// create acl
		$acl_id = create_access_collection('friends', $user->guid, 'friends');
		if (!$acl_id) {
			$result->addError('Failed to create an ACL for user');
			return $result;
		}
		
		$acl = get_access_collection($acl_id);
		
		$this->addFriendsToACL($user, $acl);
		$this->updateEntities($user, $acl);
		$this->updateAnnotations($user, $acl);
		
		$result->addSuccesses(1);
	}
	
	protected function addFriendsToACL(\ElggUser $user, \ElggAccessCollection $acl) {
		$friends = $user->getFriends(['batch' => true]);
		foreach ($friends as $friend) {
			$acl->addMember($friend->guid);
		}
	}
	
	protected function updateEntities(\ElggUser $user, \ElggAccessCollection $acl) {
		$entities = elgg_get_entities([
			'type' => 'object',
			'owner_guid' => $user->guid,
			'access_id' => ACCESS_FRIENDS,
			'limit' => false,
			'batch' => true,
		]);
		
		foreach ($entities as $entity) {
			$entity->access_id = $acl->id;
			$entity->save();
		}
	}
	
	protected function updateAnnotations(\ElggUser $user, \ElggAccessCollection $acl) {
		$annotations = elgg_get_annotations([
			'type' => 'object',
			'owner_guid' => $user->guid,
			'access_id' => ACCESS_FRIENDS,
			'limit' => false,
			'batch' => true,
		]);
		
		foreach ($annotations as $annotation) {
			$annotation->access_id = $acl->id;
			$annotation->save();
		}
	}

}
