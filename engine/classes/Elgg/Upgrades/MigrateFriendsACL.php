<?php

namespace Elgg\Upgrades;

use Elgg\Database\QueryBuilder;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;
use ElggUser;
use Exception;

/**
 * Creates user friends access collection and migrates entity access_id
 */
class MigrateFriendsACL implements AsynchronousUpgrade {

	protected $prepared;
	
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

	protected function getItems(array $options = []) {

		$options['types'] = 'user';
		$options['wheres'][] = function(QueryBuilder $qb, $main_alias) {
			$subquery = $qb->subquery('entities', 'e2');
			$subquery->select('distinct(e2.owner_guid)')
				->where($qb->compare('e2.access_id', '=', ACCESS_FRIENDS, ELGG_VALUE_INTEGER));

			return $qb->compare("{$main_alias}.guid", 'IN', $subquery->getSQL());
		};

		return elgg_get_entities($options);
	}
	
	protected function prepareMigration() {
		if (!$this->isPreparationNeeded()) {
			return;
		}
		
		// create access collections in bulk
		$this->createFriendsACLs();
		
		// add friends to acls in bulk
		$this->addFriendsToACLs();
		
		// update annotations friends acl in bulk
		$this->updateAnnotations();
		
		// save for future checks
		$this->prepared = true;
		
	}
	
	protected function isPreparationNeeded() {
		
		if ($this->prepared === true) {
			return false;
		}
		
		// check if users are missing an acl
		$total = $this->getItems(['count' => true]);
		if ($total) {
			return true;
		}
		
		// check if friends are missing in friends acls
		$check_friends = \Elgg\Database\Select::fromTable('entities', 'e');
		
		$check_friends_sub = $check_friends->subquery('access_collection_membership', 'mem');
		$check_friends_sub->select('mem.user_guid')
			->join('mem', 'access_collections', 'acl', 'acl.id = mem.access_collection_id')
			->where($check_friends->compare('acl.owner_guid', '=', 'e.guid'))
			->andWhere($check_friends->compare('acl.subtype', '=', 'friends', ELGG_VALUE_STRING));
		
		$check_friends->select('count(r.guid_two) AS total');
		$check_friends->joinRelationshipTable('e', 'guid', 'friend', true, 'inner', 'r');
		$check_friends->where($check_friends->compare('e.type', '=', 'user', ELGG_VALUE_STRING))
			->andWhere($check_friends->compare('r.guid_two', 'NOT IN', $check_friends_sub->getSQL()));
		
		$total = $check_friends->execute()->fetchColumn();
		if ($total) {
			return true;
		}
		
		// check if annotations have friends acl
		$count_annotation = \Elgg\Database\Select::fromTable('annotations', 'a');
		$count_annotation->select('count(*) AS total');
		$count_annotation->joinEntitiesTable('a', 'owner_guid', 'inner', 'e');
		$count_annotation->where($count_annotation->compare('a.access_id', '=', ACCESS_FRIENDS, ELGG_VALUE_INTEGER))
			->andWhere($count_annotation->compare('e.type', '=', 'user', ELGG_VALUE_STRING));
		
		$total = $count_annotation->execute()->fetchColumn();
		if ($total) {
			return true;
		}
		
		// save for future checks
		$this->prepared = true;
		
		return false;
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		$count = (int) $this->isPreparationNeeded();
		
		$count += $this->getItems(['count' => true]);

		return $count;
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {

		try {
			$this->prepareMigration();
		} catch (\Exception $e) {
			$result->addError($e->getMessage());
			$result->addFailures($this->countItems());
			return $result;
		}
		
		$users = $this->getItems([
			'limit' => 10,
			'offset' => $offset,
			'batch' => true,
			'batch_inc_offset' => false,
		]);

		if (empty($users)) {
			// mark as complete
			$result->addSuccesses(1);
			return $result;
		}

		/* @var $user ElggUser */
		foreach ($users as $user) {
			$acl = $user->getOwnedAccessCollection('friends');
			
			if (!$acl) {
				$result->addError("Failed to find a friends ACL for [user: $user->guid]");
				$result->addFailures(1);
				continue;
			}
			
			try {
				$this->updateEntities($user, $acl);
	
				$result->addSuccesses(1);
			} catch (Exception $ex) {
				$result->addFailures(1);
				$result->addError($ex->getMessage());
			}
		}
		
		return $result;
	}

	protected function updateEntities(ElggUser $user, \ElggAccessCollection $acl) {
		$entities = elgg_get_entities([
			'type' => 'object',
			'owner_guid' => $user->guid,
			'access_id' => ACCESS_FRIENDS,
			'limit' => false,
			'batch' => true,
			'batch_inc_offset' => false,
		]);

		foreach ($entities as $entity) {
			$entity->access_id = $acl->id;
			$entity->save();
		}
	}

	protected function createFriendsACLs() {
		
		$dbprefix = elgg_get_config('dbprefix');

		$query = "
			INSERT INTO {$dbprefix}access_collections (name, subtype, owner_guid)
			SELECT 'friends', 'friends', e.guid
			FROM {$dbprefix}entities e
			WHERE e.type = 'user'
			AND e.guid NOT IN (
				SELECT acl.owner_guid
				FROM {$dbprefix}access_collections acl
				WHERE acl.subtype = 'friends'
			)
		";
		
		_elgg_services()->db->updateData($query);
	}

	protected function addFriendsToACLs() {
		$dbprefix = elgg_get_config('dbprefix');

		$query = "
			INSERT INTO {$dbprefix}access_collection_membership (user_guid, access_collection_id)
			SELECT r.guid_two, acl.id
			FROM {$dbprefix}entity_relationships r
			JOIN {$dbprefix}access_collections acl ON r.guid_one = acl.owner_guid
			WHERE r.relationship = 'friend'
			AND r.guid_two NOT IN (
				SELECT subacl.user_guid
				FROM {$dbprefix}access_collection_membership subacl
				WHERE subacl.access_collection_id = acl.id
			)
		";
		
		_elgg_services()->db->updateData($query);
	}
	
	protected function updateAnnotations() {
		$dbprefix = elgg_get_config('dbprefix');

		$query = "
			UPDATE {$dbprefix}annotations a
			JOIN {$dbprefix}access_collections acl ON a.owner_guid = acl.owner_guid AND acl.subtype = 'friends'
			SET a.access_id = acl.id
			WHERE a.access_id = " . ACCESS_FRIENDS;
		
		_elgg_services()->db->updateData($query);
	}
}
