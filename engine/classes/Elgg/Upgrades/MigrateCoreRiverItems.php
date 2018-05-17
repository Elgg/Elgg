<?php

namespace Elgg\Upgrades;

use Elgg\Database\QueryBuilder;
use Elgg\Database\Update;
use Elgg\Loggable;
use Elgg\Upgrade\AsynchronousUpgrade;
use Elgg\Upgrade\Result;
use Psr\Log\LogLevel;

/**
 * Migrate river items to new schema
 */
class MigrateCoreRiverItems implements AsynchronousUpgrade {

	use Loggable;

	/**
	 * {@inheritdoc}
	 */
	public function getVersion() {
		return 2018050502;
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
	 * Get items that need migration
	 *
	 * @param array $options Options
	 *
	 * @return mixed
	 */
	public function getItems(array $options = []) {
		$options['wheres'][] = function (QueryBuilder $qb, $alias) {
			$ors = [];

			$ors[] = $qb->compare("$alias.result_id", '=', 0, ELGG_VALUE_INTEGER);
			$ors[] = $qb->compare("$alias.result_type", '=', '', ELGG_VALUE_STRING);
			$ors[] = $qb->compare("$alias.result_subtype", '=', '', ELGG_VALUE_STRING);

			return $qb->merge($ors, 'OR');
		};

		return elgg_get_river($options);
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
		$items = $this->getItems([
			'offset' => $offset,
			'batch' => true,
			'limit' => 3,
		]);

		foreach ($items as $item) {
			/* @var $item \ElggRiverItem */
			$this->migrateItem($item, $result);
		}
	}

	/**
	 * Migrates river item
	 *
	 * @param \ElggRiverItem $item         Item
	 * @param Result         $batch_result Result
	 *
	 * @return void
	 */
	public function migrateItem(\ElggRiverItem $item, Result $batch_result) {

		$object = $item->getObjectEntity();
		$subject = $item->getSubjectEntity();

		$result = null;
		$view = $item->view;
		$action = $item->action_type;

		if ($object instanceof \ElggComment && $action == 'create') {
			$result = $object;
			while ($object instanceof \ElggComment) {
				$object = $object->getContainerEntity();
			}
			$action = $object->getSubtype();
			$view = '';
		} else if ($annotation = $item->getAnnotation()) {
			$result = $annotation;
		}

		if ($action == 'join') {
			if ($object instanceof \ElggGroup) {
				$result = check_entity_relationship($item->subject_guid, 'member', $item->object_guid);
				$object = get_entity($result->guid_two);
				$action = 'member';
				$view = '';
			}
		}

		switch ($view) {
			case 'river/user/default/profileiconupdate' :
				$view = '';
				$action = 'profileiconupdate';
				break;

			case 'river/object/comment/create' :
				$view = '';
				$action = 'comment';
				$result = $object;
				while ($object instanceof \ElggComment) {
					$object = $object->getContainerEntity();
				}
				break;

			case 'river/relationship/friend/create' :
				$view = '';
				$action = 'friend';
				$result = check_entity_relationship($item->subject_guid, 'friend', $item->object_guid);
				$object = $item->getObjectEntity();
				break;

			case 'river/object/blog/create' :
				$view = '';
				$action = 'publish';
				break;

			case 'river/object/bookmarks/create' :
			case 'river/object/thewire/create' :
			case 'river/object/messageboard/create' :
			case 'river/group/create' :
				$view = '';
				$action = 'create';
				break;
		}

		if (!isset($result)) {
			$result = $object;
		}

		$target = $item->getTargetEntity();
		if (!$target && $container = $object->getContainerEntity()) {
			$target = $container;
		}

		if (!$object || !$subject || !$result instanceof \ElggData) {
			$batch_result->addFailures(1);

			return;
		}

		$values = [
			'action_type' => $action,
			'view' => $view,
			'subject_guid' => $subject->guid,
			'object_guid' => $object->guid,
			'target_guid' => $target ? $target->guid : 0,
			'result_id' => $result instanceof \ElggEntity ? $result->guid : $result->id,
			'result_type' => $result->getType(),
			'result_subtype' => $result->getSubtype(),
			'annotation_id' => $result instanceof \ElggAnnotation ? $result->id : 0,
			'posted' => $item->posted,
		];

		$col_types = [
			'action_type' => ELGG_VALUE_STRING,
			'view' => ELGG_VALUE_STRING,
			'subject_guid' => ELGG_VALUE_INTEGER,
			'object_guid' => ELGG_VALUE_INTEGER,
			'target_guid' => ELGG_VALUE_INTEGER,
			'annotation_id' => ELGG_VALUE_INTEGER,
			'result_id' => ELGG_VALUE_INTEGER,
			'result_type' => ELGG_VALUE_STRING,
			'result_subtype' => ELGG_VALUE_STRING,
			'posted' => ELGG_VALUE_INTEGER,
		];

		try {
			$qb = Update::table('river');

			foreach ($values as $name => $value) {
				$qb->set($name, $qb->param($value, $col_types[$name]));
			}

			$qb->where($qb->compare('id', '=', $item->id, ELGG_VALUE_INTEGER));

			_elgg_services()->db->updateData($qb);

			$batch_result->addSuccesses(1);
		} catch (\DatabaseException $ex) {
			$this->log(LogLevel::ERROR, $ex);
			$batch_result->addFailures(1);
		}
	}
}
