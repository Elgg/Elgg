<?php

namespace Elgg\Database;

use Elgg\Database;
use Elgg\Security\Crypto;
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
	 * @var string name of the users api sessions database table
	 */
	public const TABLE_NAME = 'users_apisessions';
	
	/**
	 * Create a new table handler
	 *
	 * @param Database $database the Elgg database handler
	 * @param Crypto   $crypto   crypto handler
	 */
	public function __construct(protected Database $database, protected Crypto $crypto) {
	}
	
	/**
	 * Obtain a token for a user
	 *
	 * @param int $user_guid the user guid
	 * @param int $expires   minutes until token expires (default is 60 minutes)
	 *
	 * @return false|string
	 */
	public function createToken(int $user_guid, int $expires = 60): string|false {
		$token = $this->crypto->getRandomString(32, Crypto::CHARS_HEX);
		$expires = $this->getCurrentTime("+{$expires} minutes");
		
		$insert = Insert::intoTable(self::TABLE_NAME);
		$insert->values([
			'user_guid' => $insert->param($user_guid, ELGG_VALUE_GUID),
			'token' => $insert->param($token, ELGG_VALUE_STRING),
			'expires' => $insert->param($expires->getTimestamp(), ELGG_VALUE_TIMESTAMP),
		]);
		
		return $this->database->insertData($insert) ? $token : false;
	}
	
	/**
	 * Get all tokens attached to a user
	 *
	 * @param int $user_guid The user GUID
	 *
	 * @return false|\stdClass[]
	 */
	public function getUserTokens(int $user_guid) {
		$select = Select::fromTable(self::TABLE_NAME);
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
	public function validateToken(string $token): int|false {
		$select = Select::fromTable(self::TABLE_NAME);
		$select->select('*')
			->where($select->compare('token', '=', $token, ELGG_VALUE_STRING))
			->andWhere($select->compare('expires', '>', $this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		$row = $this->database->getDataRow($select);
		
		return $row ? (int) $row->user_guid : false;
	}
	
	/**
	 * Remove user token
	 *
	 * @param string $token The token
	 *
	 * @return bool
	 */
	public function removeToken(string $token) {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('token', '=', $token, ELGG_VALUE_STRING));
		
		return (bool) $this->database->deleteData($delete);
	}
	
	/**
	 * Remove expired tokens
	 *
	 * @return int Number of rows removed
	 */
	public function removeExpiresTokens() {
		$delete = Delete::fromTable(self::TABLE_NAME);
		$delete->where($delete->compare('expires', '<', $this->getCurrentTime()->getTimestamp(), ELGG_VALUE_TIMESTAMP));
		
		return $this->database->deleteData($delete);
	}
}
