<?php

namespace Elgg\Database;

use Elgg\Database;

/**
 * Manage the contents of the api_users table
 *
 * @since 4.0
 * @internal
 */
class ApiUsersTable {

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
	protected $table = 'api_users';
	
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
	 * Generate a new API user for a site, returning a new keypair on success
	 *
	 * @return false|\stdClass object or false
	 */
	public function createApiUser() {
		$public = $this->crypto->getRandomString(40, \ElggCrypto::CHARS_HEX);
		$secret = $this->crypto->getRandomString(40, \ElggCrypto::CHARS_HEX);
		
		$insert = Insert::intoTable($this->table);
		$insert->values([
			'api_key' => $insert->param($public, ELGG_VALUE_STRING),
			'secret' => $insert->param($secret, ELGG_VALUE_STRING),
		]);
		
		if ($this->database->insertData($insert) === false) {
			return false;
		}
		
		return $this->getApiUser($public);
	}
	
	/**
	 * Find an API User's details based on the provided public api key.
	 * These users are not users in the traditional sense.
	 *
	 * @param string $public_api_key The API Key (public)
	 * @param bool   $only_active    Only return if the API key is active (default: true)
	 *
	 * @return false|\stdClass stdClass representing the database row or false
	 */
	public function getApiUser(string $public_api_key, bool $only_active = true) {
		$select = Select::fromTable($this->table);
		$select->select('*')
			->where($select->compare('api_key', '=', $public_api_key, ELGG_VALUE_STRING));
		
		if ($only_active) {
			$select->andWhere($select->compare('active', '=', 1, ELGG_VALUE_INTEGER));
		}
		
		$row = $this->database->getDataRow($select);
		if (empty($row)) {
			return false;
		}
		
		return $row;
	}
	
	/**
	 * Revoke an api user key.
	 *
	 * @param string $public_api_key The API Key (public)
	 *
	 * @return bool
	 */
	public function removeApiUser(string $public_api_key) {
		$row = $this->getApiUser($public_api_key);
		if (empty($row)) {
			return false;
		}
		
		$delete = Delete::fromTable($this->table);
		$delete->where($delete->compare('id', '=', $row->id, ELGG_VALUE_ID));
		
		return (bool) $this->database->deleteData($delete);
	}
	
	/**
	 * Enable an api user key
	 *
	 * @param string $public_api_key The API Key (public)
	 *
	 * @return bool
	 */
	public function enableAPIUser(string $public_api_key) {
		$update = Update::table($this->table);
		$update->set('active', $update->param(1, ELGG_VALUE_INTEGER))
			->where($update->compare('api_key', '=', $public_api_key, ELGG_VALUE_STRING));
		
		return (bool) $this->database->updateData($update);
	}
	
	/**
	 * Disable an api user key
	 *
	 * @param string $public_api_key The API Key (public)
	 *
	 * @return bool
	 */
	public function disableAPIUser(string $public_api_key) {
		$update = Update::table($this->table);
		$update->set('active', $update->param(0, ELGG_VALUE_INTEGER))
			->where($update->compare('api_key', '=', $public_api_key, ELGG_VALUE_STRING));
		
		return (bool) $this->database->updateData($update);
	}
}
