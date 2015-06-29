<?php

namespace Elgg\WebServices;

/**
 * Service to create, retrieve and revoke API user keys
 */
class KeyService {

	/**
	 * GUID of the site entity
	 * @var int 
	 */
	protected $site_guid;

	/**
	 * Constructor
	 *
	 * @param int $site_guid GUID of the site entity
	 */
	public function __construct($site_guid = null) {
		$this->site_guid = $site_guid ? (int) $site_guid : elgg_get_site_entity()->guid;
		$this->dbprefix = elgg_get_config('dbprefix');
	}

	/**
	 * Generate a new API user for a site, returning a new keypair on success
	 * @return \Elgg\WebServices\ApiUser|false
	 */
	public function create() {

		$public = sha1(rand() . $this->site_guid . microtime());
		$secret = sha1(rand() . $this->site_guid . microtime() . $public);

		$insert = insert_data("INSERT into {$this->dbprefix}api_users
								(site_guid, api_key, secret) values
								($this->site_guid, '$public', '$secret')");

		if (empty($insert)) {
			return false;
		}

		return $this->get($public);
	}

	/**
	 * Find an API User's details based on the provided public api key.
	 * These users are not users in the traditional sense.
	 *
	 * @param string $api_key Pulic API key
	 * @return \Elgg\WebServices\ApiUser|false
	 */
	public function get($api_key) {
		$api_key = sanitise_string($api_key);
		$row = get_data_row("SELECT * FROM {$this->dbprefix}api_users
								WHERE api_key='$api_key' AND site_guid={$this->site_guid} AND active=1");

		if (!$row) {
			return false;
		}

		return new ApiUser($row);
	}

	/**
	 * Revoke an api user key.
	 *
	 * @param string $api_key   The API Key (public)
	 * @return bool
	 */
	function revoke($api_key) {
		$user = $this->get($api_key);
		if ($user) {
			return delete_data("DELETE from {$this->dbprefix}api_users
									WHERE id={$user->id}");
		}
		return false;
	}

}
