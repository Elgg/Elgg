<?php

namespace Elgg\Notifications;

use Elgg\Config;
use Elgg\Database\Delete;
use Elgg\Database\Select;
use Elgg\Database\Update;
use Elgg\Queue\DatabaseQueue;

/**
 * Database queue for notifications
 *
 * @internal
 * @since 4.0
 */
class NotificationsQueue extends DatabaseQueue {
	
	/**
	 * @var string name of the queue
	 */
	const QUEUE_NAME = 'notifications';
	
	/**
	 * @var Config
	 */
	protected $config;
	
	/**
	 * Create a queue
	 *
	 * @param string         $name   Name of the queue. Must be less than 256 characters.
	 * @param \Elgg\Database $db     Database adapter
	 * @param \Elgg\Config   $config Global config
	 */
	public function __construct(string $name, \Elgg\Database $db, \Elgg\Config $config) {
		parent::__construct($name, $db);
		
		$this->config = $config;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function dequeue() {
		$delay = (int) $this->config->notifications_queue_delay;
		if ($delay < 1) {
			// no delay, so rely on parent logic
			return parent::dequeue();
		}
		
		// get a record for processing
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('name', '=', $this->name, ELGG_VALUE_STRING))
			->andWhere($select->expr()->isNull('worker'))
			->andWhere($select->compare('timestamp', '<', $this->getCurrentTime("-{$delay} seconds")->getTimestamp(), ELGG_VALUE_TIMESTAMP))
			->orderBy('id', 'ASC')
			->setMaxResults(1);
		
		$row = $this->db->getDataRow($select);
		if (empty($row)) {
			return;
		}
		
		// lock a record for processing
		$update = Update::table(self::TABLE_NAME);
		$update->set('worker', $update->param($this->workerId, ELGG_VALUE_STRING))
			->where($update->compare('name', '=', $this->name, ELGG_VALUE_STRING))
			->andWhere($update->compare('id', '=', $row->id, ELGG_VALUE_ID))
			->andWhere($update->expr()->isNull('worker'));
		
		if ($this->db->updateData($update, true) !== 1) {
			return;
		}
		
		// remove locked record from database
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('id', '=', $row->id, ELGG_VALUE_ID));
		
		$this->db->deleteData($delete);
		
		return unserialize($row->data);
	}
}
