<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\Traits\TimeUsing;

/**
 * Manage the users_remember_me_cookies table
 *
 * @internal
 * @since 4.1
 */
class UsersRememberMeCookiesTable {

	use TimeUsing;
	
	/**
	 * @var string name of the persistent cookies database table
	 */
	public const TABLE_NAME = 'users_remember_me_cookies';

	protected Database $database;
	
	/**
	 * Create a new service
	 *
	 * @param Database $database the database service
	 */
	public function __construct(Database $database) {
		$this->database = $database;
	}
	
	/**
	 * Store a hash in the DB
	 *
	 * @param \ElggUser $user The user for whom we're storing the hash
	 * @param string    $hash The hashed token
	 *
	 * @return int
	 */
	public function insertHash(\ElggUser $user, string $hash): int {
		// This prevents inserting the same hash twice, which seems to be happening in some rare cases
		// and for unknown reasons. See https://github.com/Elgg/Elgg/issues/8104
		$this->deleteHash($hash);
		
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'code' => $insert->param($hash, ELGG_VALUE_STRING),
			'guid' => $insert->param($user->guid, ELGG_VALUE_GUID),
			'timestamp' => $insert->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);
		
		return $this->database->insertData($insert);
	}
	
	/**
	 * Get the database row for a hash
	 *
	 * @param string $hash the hashed token
	 *
	 * @return \stdClass|null
	 */
	public function getRowFromHash(string $hash): ?\stdClass {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('code', '=', $hash, ELGG_VALUE_STRING));
		
		return $this->database->getDataRow($select) ?: null;
	}
	
	/**
	 * Update the timestamp of a used hash
	 *
	 * @param \ElggUser $user the user of the associated hash
	 * @param string    $hash the hashed token
	 *
	 * @return bool
	 */
	public function updateHash(\ElggUser $user, string $hash): bool {
		$update = Update::table(self::TABLE_NAME);
		$update->set('timestamp', $update->param($this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP))
			->where($update->compare('guid', '=', $user->guid, ELGG_VALUE_GUID))
			->andWhere($update->compare('code', '=', $hash, ELGG_VALUE_STRING));
		
		// not interested in number of updated rows, as an update in the same second won't update the row
		return $this->database->updateData($update);
	}
	
	/**
	 * Remove a hash from the DB
	 *
	 * @param string $hash The hashed token to remove
	 *
	 * @return int
	 */
	public function deleteHash(string $hash): int {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('code', '=', $hash, ELGG_VALUE_STRING));
		
		return $this->database->deleteData($delete);
	}
	
	/**
	 * Remove all the hashes associated with a user
	 *
	 * @param \ElggUser $user The user for whom we're removing hashes
	 *
	 * @return int
	 */
	public function deleteAllHashes(\ElggUser $user): int {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('guid', '=', $user->guid, ELGG_VALUE_GUID));
		
		return $this->database->deleteData($delete);
	}
	
	/**
	 * Remove all expired hashes from the database
	 *
	 * @param int $expiration the expiration timestamp
	 *
	 * @return int
	 */
	public function deleteExpiredHashes(int $expiration): int {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('timestamp', '<', $expiration, ELGG_VALUE_TIMESTAMP));
		
		return $this->database->deleteData($delete);
	}
}
