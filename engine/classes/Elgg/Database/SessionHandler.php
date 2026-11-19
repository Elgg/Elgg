<?php

namespace Elgg\Database;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Elgg\Database;
use Elgg\Exceptions\DatabaseException;
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
	public const TABLE_NAME = 'users_sessions';

	/**
	 * Constructor
	 *
	 * @param Database $db The database
	 */
	public function __construct(protected Database $db) {
	}

	/**
	 * {@inheritdoc}
	 */
	#[\ReturnTypeWillChange]
	public function open($path, $name) {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	#[\ReturnTypeWillChange]
	public function read($id) {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('session', '=', $id, ELGG_VALUE_STRING));
		
		$result = $this->db->getDataRow($select);
		
		return $result ? (string) $result->data : '';
	}

	/**
	 * {@inheritdoc}
	 */
	#[\ReturnTypeWillChange]
	public function write($id, $data) {
		if (elgg_get_config('_disable_session_save')) {
			return true;
		}
		
		if ($this->read($id)) {
			$update = Update::table(self::TABLE_NAME);
			$update->set('data', $update->param($data, ELGG_VALUE_STRING))
				->set('ts', $update->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP))
				->where($update->compare('session', '=', $id, ELGG_VALUE_STRING));
			
			return $this->db->updateData($update);
		}
		
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'session' => $insert->param($id, ELGG_VALUE_STRING),
			'data' => $insert->param($data, ELGG_VALUE_STRING),
			'ts' => $insert->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);

		try {
			// not returning the result of the database call as the session table doesn't support an autoincrement column
			// so the result of this call will always be 0
			$this->db->insertData($insert);
		} catch (DatabaseException $e) {
			$prev = $e->getPrevious();
			if ($prev instanceof UniqueConstraintViolationException) {
				// ignoring issues with duplicate key insertions
				return true;
			}

			throw $e;
		}

		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	#[\ReturnTypeWillChange]
	public function close() {
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	#[\ReturnTypeWillChange]
	public function destroy($id) {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('session', '=', $id, ELGG_VALUE_STRING));
		
		$this->db->deleteData($delete);
		
		return true;
	}

	/**
	 * {@inheritdoc}
	 */
	#[\ReturnTypeWillChange]
	public function gc($max_lifetime) {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('ts', '<', $this->getCurrentTime("-{$max_lifetime} seconds")->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		return (bool) $this->db->deleteData($delete);
	}
	
	/**
	 * Create Session ID
	 *
	 * @return string
	 * @since 7.2
	 */
	public function create_sid(): string {
		return (string) session_create_id();
	}
	
	/**
	 * Validate that the given session ID is valid and not used
	 *
	 * @param string $id session ID
	 *
	 * @return bool
	 * @since 7.2
	 */
	public function validateId(string $id): bool {
		if (empty($id)) {
			return false;
		}
		
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('session', '=', $id, ELGG_VALUE_STRING));
		
		return empty($this->db->getDataRow($select));
	}
}
