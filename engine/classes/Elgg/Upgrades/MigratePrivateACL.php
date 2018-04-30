<?php

namespace Elgg\Upgrades;

use Elgg\Database\QueryBuilder;
use Elgg\HttpException;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;

/**
 * Creates private access collection and migrates entity access_id
 */
class MigratePrivateACL implements AsynchronousUpgrade {

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2018043000;
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
	 * Get items
	 *
	 * @param array $options ege* options
	 * @return mixed
	 */
	protected function getItems(array $options = []) {
		$options['wheres'][] = function(QueryBuilder $qb) {
			// Grab all users
			// As well as any objects/groups that own another entity or annotation
			$owns_entity = $qb->subquery('entities');
			$owns_entity->select(1)
				->where($qb->compare('owner_guid', '=', 'e.guid'))
				->andWhere($qb->compare('access_id', '=', ACCESS_PRIVATE, ELGG_VALUE_INTEGER));

			$owns_ann = $qb->subquery('annotations');
			$owns_ann->select(1)
				->where($qb->compare('owner_guid', '=', 'e.guid'))
				->andWhere($qb->compare('access_id', '=', ACCESS_PRIVATE, ELGG_VALUE_INTEGER));

			return $qb->merge([
				$qb->compare('e.type', '=', 'user', ELGG_VALUE_STRING),
				"EXISTS ({$owns_entity->getSQL()})",
				"EXISTS ({$owns_ann->getSQL()})",
			], 'OR');
		};

		$options['wheres'][] = function(QueryBuilder $qb) {
			// Filter out entities that already have private ACL

			$has_private_acl = $qb->subquery('access_collections');
			$has_private_acl->select(1)
				->where($qb->compare('owner_guid', '=', 'e.guid'))
				->andWhere($qb->compare('subtype', '=', \ElggAccessCollection::PRIVATE, ELGG_VALUE_STRING));

			return "NOT EXISTS ({$has_private_acl->getSQL()})";
		};

		return elgg_get_entities($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function countItems() {
		return $this->getItems(['count' => true]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function run(Result $result, $offset) {

		$owners = $this->getItems([
			'limit' => 1,
			'offset' => $offset,
		]);
		
		if (empty($owners)) {
			// mark as complete
			$result->addSuccesses(1);
			return $result;
		}
		
		$owner = $owners[0];

		// create acl
		$acl_id = create_access_collection('private', $owner->guid, \ElggAccessCollection::PRIVATE);
		if (!$acl_id) {
			$result->addError("Failed to create a private ACL for [$owner->type: $owner->guid]");
			$result->addFailures(1);

			return $result;
		}

		$acl = get_access_collection($acl_id);

		try {
			$this->updateEntities($owner, $acl);
			$this->updateAnnotations($owner, $acl);

			$result->addSuccesses(1);
		} catch (HttpException $ex) {
			$result->addFailures(1);
			$result->addError($ex->getMessage());
		}
	}
	
	protected function updateEntities(\ElggEntity $owner, \ElggAccessCollection $acl) {
		$entities = elgg_get_entities([
			'type' => 'object',
			'owner_guid' => $owner->guid,
			'access_id' => ACCESS_PRIVATE,
			'limit' => false,
			'batch' => true,
		]);
		
		foreach ($entities as $entity) {
			$entity->access_id = $acl->id;
			$entity->save();
		}
	}
	
	protected function updateAnnotations(\ElggEntity $owner, \ElggAccessCollection $acl) {
		$annotations = elgg_get_annotations([
			'type' => 'object',
			'owner_guid' => $owner->guid,
			'access_id' => ACCESS_PRIVATE,
			'limit' => false,
			'batch' => true,
		]);
		
		foreach ($annotations as $annotation) {
			$annotation->access_id = $acl->id;
			$annotation->save();
		}
	}

}
