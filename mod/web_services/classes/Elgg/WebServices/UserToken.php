<?php

namespace Elgg\WebServices;

/**
 * User token object
 *
 * @property int    $id        DB row id
 * @property int    $user_guid GUID of the user
 * @property int    $site_guid Site entity GUID this token applies to
 * @property string $token     Token
 * @property int    $expires   Expiry timestamp
 */
class UserToken {
	
	/**
	 * Constructs a UserToken object from DB row
	 * 
	 * @param \stdClass $row DB row
	 */
	public function __construct(\stdClass $row) {
		$this->id = (int) $row->id;
		$this->user_guid = (int) $row->user_guid;
		$this->site_guid = (int) $row->site_guid;
		$this->token = $row->token;
		$this->expires = (int) $row->expires;
	}
	
	/**
	 * Loads a token from the DB
	 * 
	 * @param string $token Token
	 * @return UserToken|false
	 */
	public static function load($token) {
		$dbprefix = elgg_get_config('dbprefix');
		$token = sanitize_string($token);
		$row = get_data_row("SELECT * FROM {$dbprefix}users_apisessions WHERE token='{$token}'");
		if (!$row) {
			return false;
		}
		return new UserToken($row);
	}

	/**
	 * Removes a token from the database
	 * @return bool
	 */
	public function delete() {
		$dbprefix = elgg_get_config('dbprefix');
		return delete_data("DELETE FROM {$dbprefix}users_apisessions WHERE id={$this->id}");
	}

	/**
	 * Validate a token against a given site and expiration time
	 *
	 * A token registered with one site can not be used from a
	 * different apikey(site), so be aware of this during development.
	 *
	 * @param \ElggSite $site  Site entity to validate against
	 * @return \ElggUser|false User that owns the token
	 */
	public function validate(\ElggSite $site = null) {

		if ($this->expires < time()) {
			$this->delete();
			return false;
		}

		if ($site) {
			if ($this->site_guid != $site->guid) {
				return false;
			}
		}

		return get_entity($this->user_guid);
	}
}
