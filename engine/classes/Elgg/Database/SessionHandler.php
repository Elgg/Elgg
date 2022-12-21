<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\Traits\TimeUsing;

/**
 * Database session handler
 *
 * @internal
 */
class SessionHandler implements \SessionHandlerInterface {

	use TimeUsing;
	
	/**
	 * @var string name of the users sessions database table
	 */
	const TABLE_NAME = 'users_sessions';

	protected Database $db;

	/**
	 * Constructor
	 *
	 * @param Database $db The database
	 */
	public function __construct(Database $db) {
		$this->db = $db;
	}

	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function open($save_path, $name) {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function read($session_id) {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('session', '=', $session_id, ELGG_VALUE_STRING));
		
		$result = $this->db->getDataRow($select);
		
		return $result ? (string) $result->data : '';
	}

	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function write($session_id, $session_data) {
		if (elgg_get_config('_disable_session_save')) {
			return true;
		}
		
		if ($this->read($session_id)) {
			$update = Update::table(self::TABLE_NAME);
			$update->set('data', $update->param($session_data, ELGG_VALUE_STRING))
				->set('ts', $update->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP))
				->where($update->compare('session', '=', $session_id, ELGG_VALUE_STRING));
			
			return $this->db->updateData($update);
		}
		
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'session' => $insert->param($session_id, ELGG_VALUE_STRING),
			'data' => $insert->param($session_data, ELGG_VALUE_STRING),
			'ts' => $insert->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);
		
		return $this->db->insertData($insert) !== false;
	}

	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function close() {
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function destroy($session_id) {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('session', '=', $session_id, ELGG_VALUE_STRING));
		
		$this->db->deleteData($delete);
		
		return true;
	}

	/**
	 * {@inheritDoc}
	 */
	#[\ReturnTypeWillChange]
	public function gc($max_lifetime) {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('ts', '<', $this->getCurrentTime("-{$max_lifetime} seconds")->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		return (bool) $this->db->deleteData($delete);
	}
}
