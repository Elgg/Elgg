<?php

namespace Elgg\Upgrades;

use Elgg\Database\QueryBuilder;
use Elgg\HttpException;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;
use ElggUser;
use Exception;

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

	protected function getItems(array $options = []) {

		$options['types'] = 'user';
		$options['wheres'][] = function(QueryBuilder $qb, $main_alias) {
			$subquery = $qb->subquery('access_collections', 'acl');
			$subquery->select('acl.owner_guid')
				->where($qb->compare('acl.subtype', '=', 'friends', ELGG_VALUE_STRING));

			return $qb->compare("{$main_alias}.guid", 'NOT IN', $subquery->getSQL());
		};

		return elgg_get_entities($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		// users without a friends acl
		$db = elgg()->db;

		return $this->getItems(['count' => true]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {

		$users = $this->getItems([
			'limit' => 1,
			'offset' => $offset,
		]);

		if (empty($users)) {
			// mark as complete
			$result->addSuccesses(1);
			return $result;
		}

		$user = $users[0];
		/* @var $user ElggUser */

		// create acl
		$acl_id = create_access_collection('friends', $user->guid, 'friends');
		if (!$acl_id) {
			$result->addError("Failed to create a friends ACL for [user: $user->guid]");
			$result->addFailures(1);

			return $result;
		}

		$acl = get_access_collection($acl_id);

		try {
			$this->addFriendsToACL($user, $acl);
			$this->updateEntities($user, $acl);
			$this->updateAnnotations($user, $acl);

			$result->addSuccesses(1);
		} catch (Exception $ex) {
			$result->addFailures(1);
			$result->addError($ex->getMessage());
		}
	}

	protected function addFriendsToACL(ElggUser $user, \ElggAccessCollection $acl) {
		$dbprefix = elgg_get_config('dbprefix');

		$query = "
			INSERT INTO {$dbprefix}access_collection_membership (user_guid, access_collection_id)
			SELECT guid_two, {$acl->id} FROM {$dbprefix}entity_relationships
			WHERE relationship = 'friend'
			AND guid_one = {$user->guid}
		";
		
		_elgg_services()->db->updateData($query);
	}

	protected function updateEntities(ElggUser $user, \ElggAccessCollection $acl) {
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

	protected function updateAnnotations(ElggUser $user, \ElggAccessCollection $acl) {
		$update = \Elgg\Database\Update::table('annotations');
		$update->set('access_id', $acl->id);
		$update->where($update->compare('access_id', '=', ACCESS_FRIENDS, ELGG_VALUE_INTEGER));
		$update->andWhere($update->compare('owner_guid', '=', $user->guid, ELGG_VALUE_GUID));
		
		_elgg_services()->db->updateData($update);
	}
}
