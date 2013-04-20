<?php

/**
 * FIFO queue that uses ElggObjects for persistence
 */
class Elgg_Util_DatabaseQueue implements Elgg_Util_FifoQueue {

	/* @var string Name of the queue */
	protected $name;

	/**
	 * Create a queue
	 *
	 * @param string $name Name of the queue
	 */
	public function __construct($name) {
		$this->name = sanitize_string($name);
	}

	/**
	 * {@inheritdoc}
	 */
	public function enqueue($item) {
		$object = new ElggObject();
		$object->subtype = 'elgg:queue';
		$object->access_id = ACCESS_PUBLIC;
		$object->title = $this->name;
		$object->description = serialize($item);
		return (bool)$object->save();
	}

	/**
	 * {@inheritdoc}
	 */
	public function dequeue() {
		$db_prefix = elgg_get_config('dbprefix');
		$object = elgg_get_entities(array(
			'type' => 'object',
			'subtype' => 'elgg:queue',
			'order_by' => 'e.guid ASC',
			'limit' => 1,
			'joins' => array("JOIN {$db_prefix}objects_entity as oe on oe.guid = e.guid"),
			'wheres' => array("oe.title = '$this->name'"),
		));
		if ($object) {
			$result = unserialize($object[0]->description);
			$object[0]->delete();
			return $result;
		}
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function clear() {
		$db_prefix = elgg_get_config('dbprefix');
		$options = array(
			'type' => 'object',
			'subtype' => 'elgg:queue',
			'order_by' => 'e.guid ASC',
			'limit' => 0,
			'joins' => array("JOIN {$db_prefix}objects_entity as oe on oe.guid = e.guid"),
			'wheres' => array("oe.title = '$this->name'"),
		);
		$batch = new ElggBatch('elgg_get_entities', $options);
		foreach ($batch as $item) {
			$item->delete();
		}
	}
}
