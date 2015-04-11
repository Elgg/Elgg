<?php

namespace Elgg\Upgrades;

class PrivateAndFriendAcls {
	private $db;

	private $accessCollections;

	private $entityTable;

	/**
	 *
	 */
	public function __construct() {
		$this->db = _elgg_services()->db;
		$this->entityTable = _elgg_services()->entityTable;
		$this->accessCollections = _elgg_services()->accessCollections;
		$this->metastringsTable = _elgg_services()->metastringsTable;
	}

	/**
	 *
	 */
	public function run(int $offset) {

		$dbprefix = $this->db->getTablePrefix();

		$access_private = ACCESS_PRIVATE;
		$access_friends = ACCESS_FRIENDS;

		$users = $this->entityTable->getEntities(array(
			'type' => 'user',
			'offset' => $offset,
			'limit' => 50,
		));

		foreach ($users as $user) {
			$friends_acl_id = $this->getFriendsAclId();
			$private_acl_id = $this->getPrivateAclId();

			if (!$friends_acl_id || !$private_acl_id) {
				$error_count++;
				// TODO Register user facing error
				continue;
			}

			$user->friends_acl = $friends_acl_id;
			$user->private_acl = $private_acl_id;

			// Add user to their own private acl
			$this->accessCollections->addUser($user->guid, $private_acl_id);

			// Add user to their own friends collection so they still have access
			// if ownership changes on things assigned to their friends
			$this->accessCollections->addUser($user->guid, $friends_acl_id);

			// Add missing friends to the ACL
			$params = array(
				'type' => 'user',
				'relationship' => 'friend',
				'relationship_guid' => $user->guid,
				'limit' => false,
				'callback' => false
			);

			$friends = new \ElggBatch('elgg_get_entities_from_relationship', $params);
			foreach ($friends as $friend) {
				$this->accessCollections->addUser($friend->guid, $friends_acl_id);
			}

			// update their content
			$params = array(
				'owner_guid' => $user->guid,
				'wheres' => array(
					"access_id IN ({$access_private}, {$access_friends})"
				),
				'limit' => false
			);

			$content = new \ElggBatch('elgg_get_entities', $params, null, 25, false);
			foreach ($content as $entity) {
				// Skip comments and discussion replies as their ACLs are
				// updated to match their containers' ACLs automatically
				if ($entity instanceof ElggComment) {
					continue;
				}

				if ($entity->access_id == 0) {
					$entity->access_id = $private_acl_id;
				} else {
					$entity->access_id = $friends_acl_id;
				}

				$entity->save();
			}

			// Catch any metadata/annotations that aren't handled automagically
			$pmsql = "UPDATE {$dbprefix}metadata SET access_id = {$private_acl_id} WHERE access_id = 0 AND owner_guid = {$user->guid}";
			$this->db->updateData($pmsql);

			$fmsql = "UPDATE {$dbprefix}metadata SET access_id = {$friends_acl_id} WHERE access_id = -2 AND owner_guid = {$user->guid}";
			$this->db->updateData($fmsql);

			$pasql = "UPDATE {$dbprefix}annotations SET access_id = {$private_acl_id} WHERE access_id = 0 AND owner_guid = {$user->guid}";
			$this->db->updateData($pasql);

			$fasql = "UPDATE {$dbprefix}annotations SET access_id = {$friends_acl_id} WHERE access_id = 0 AND owner_guid = {$user->guid}";
			$this->db->updateData($fasql);

			$success_count++;
		}
	}

	/**
	 * Get id of current friends ACL id or create a new one if necessary
	 *
	 * @return int|bool $friends_acl_id The ACL id or false on error
	 */
	private function getFriendsAclId($user) {
		$friends_acl = false;

		if ($user->friends_acl) {
			// Use existing ACL
			$friends_acl = $this->accessCollections->get($user->friends_acl);
		}

		if (!$friends_acl) {
			// Create a new ACL to hold the friends
			$friends_acl_id = $this->accessCollections->create("acl:friends:{$user->guid}", $user->guid);
		}

		return $friends_acl_id;
	}

	/**
	 * Get id of current friends ACL id or create a new one if necessary
	 *
	 * @return int|bool $friends_acl_id The ACL id or false on error
	 */
	private function getPrivateAclId(ElggUser $user) {
		$private_acl = false;

		if ($user->private_acl) {
			$private_acl = $this->accessCollections->get($user->private_acl);
		}

		if (!$private_acl) {
			$private_acl_id = $this->accessCollections->create("acl:private:{$user->guid}", $user->guid);
		}

		return $private_acl_id;
	}

	/**
	 * Check if there are users without personal ACL or friends ACL
	 *
	 * @return boolean
	 */
	public function isRequired() {
		$dbprefix = $this->db->getTablePrefix();

		$friends_acl_id = $this->metastringsTable->getId('friends_acl');

		// TODO Add a \Elgg\Database\AccessCollections::getFriendsAclId($user)
		// method which returns false for non-existing ACL?
		// @see comment: https://github.com/Elgg/Elgg/pull/8166#issuecomment-91807940
		$friends_acl = $this->entityTable->getEntities(array(
			'type' => 'user',
			'wheres' => array(
				"NOT EXISTS (
			SELECT 1 FROM {$dbprefix}metadata md
			WHERE md.entity_guid = e.guid
				AND md.name_id = $friends_acl_id"
			),
			'count' => true
		));

		$private_acl_id = $this->metastringsTable->getId('private_acl');

		// TODO Add a \Elgg\Database\AccessCollections::getPrivateAclId($user)
		// method which returns false for non-existing ACL?
		// @see comment: https://github.com/Elgg/Elgg/pull/8166#issuecomment-91807940
		$private_acl = $this->entityTable->getEntities(array(
			'type' => 'user',
			'wheres' => array(
				"NOT EXISTS (
			SELECT 1 FROM {$dbprefix}metadata md
			WHERE md.entity_guid = e.guid
				AND md.name_id = $private_acl_id"
			),
			'count' => true
		));

		$return = $friends_acl && $private_acl;

		return $return;
	}
}
