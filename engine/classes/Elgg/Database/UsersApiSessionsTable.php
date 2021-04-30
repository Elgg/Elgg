<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\Traits\TimeUsing;

/**
 * Manage the contents of the users_apisessions table
 *
 * @since 4.0
 * @internal
 */
class UsersApiSessionsTable {
	
	use TimeUsing;
	
	/**
	 * @var Database
	 */
	protected $database;
	
	/**
	 * @var \ElggCrypto
	 */
	protected $crypto;
	
	/**
	 * @var string Table being managed, DON'T CHANGE
	 */
	protected $table = 'users_apisessions';
	
	/**
	 * Create a new table handler
	 *
	 * @param Database    $database the Elgg database handler
	 * @param \ElggCrypto $crypto   crypto handler
	 */
	public function __construct(Database $database, \ElggCrypto $crypto) {
		$this->database = $database;
		$this->crypto = $crypto;
	}
	
	/**
	 * Obtain a token for a user
	 *
	 * @param int $user_guid the user guid
	 * @param int $expires   minutes until token expires (default is 60 minutes)
	 *
	 * @return false|string
	 */
	public function createToken(int $user_guid, int $expires = 60) {
		$token = $this->crypto->getRandomString(32, \ElggCrypto::CHARS_HEX);
		$expires = $this->getCurrentTime("+{$expires} minutes");
		
		$insert = Insert::intoTable($this->table);
		$insert->values([
			'user_guid' => $insert->param($user_guid, ELGG_VALUE_GUID),
			'token' => $insert->param($token, ELGG_VALUE_STRING),
			'expires' => $insert->param($expires->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);
		
		if ($this->database->insertData($insert)) {
			return $token;
		}
		
		return false;
	}
	
	/**
	 * Get all tokens attached to a user
	 *
	 * @param int $user_guid The user GUID
	 *
	 * @return false|\stdClass[]
	 */
	public function getUserTokens(int $user_guid) {
		$select = Select::fromTable($this->table);
		$select->select('*')
			->where($select->compare('user_guid', '=', $user_guid, ELGG_VALUE_GUID));
		
		return $this->database->getData($select);
	}
	
	/**
	 * Validate that a given token is still valid
	 *
	 * @param string $token the token to verify
	 *
	 * @return false|int the user guid attached to the token, or false
	 */
	public function validateToken(string $token) {
		$select = Select::fromTable($this->table);
		$select->select('*')
			->where($select->compare('token', '=', $token, ELGG_VALUE_STRING))
			->andWhere($select->compare('expires', '>', $this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		$row = $this->database->getDataRow($select);
		if (empty($row)) {
			return false;
		}
		
		return (int) $row->user_guid;
	}
	
	/**
	 * Remove user token
	 *
	 * @param string $token The token
	 *
	 * @return bool
	 */
	public function removeToken(string $token) {
		$delete = Delete::fromTable($this->table);
		$delete->where($delete->compare('token', '=', $token, ELGG_VALUE_STRING));
		
		return (bool) $this->database->deleteData($delete);
	}
	
	/**
	 * Remove expired tokens
	 *
	 * @return int Number of rows removed
	 */
	public function removeExpiresTokens() {
		$delete = Delete::fromTable($this->table);
		$delete->where($delete->compare('expires', '<', $this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		return $this->database->deleteData($delete);
	}
}
