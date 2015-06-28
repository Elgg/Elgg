<?php

namespace Elgg\WebServices;

/**
 * ApiUser object
 *
 * @property int    $id DB row id
 * @property int    $site_guid Site entity GUID this key applies to
 * @property string $api_key Public API key
 * @property string $secret  Secret key
 * @property bool   $active Is this key active?
 */
class ApiUser {
	
	/**
	 * Constructs an ApiUser object from DB row
	 * 
	 * @param \stdClass $row DB row
	 */
	public function __construct(\stdClass $row) {
		$this->id = $row->id;
		$this->site_guid = $row->site_guid;
		$this->api_key = $row->api_key;
		$this->secret = $row->secret;
		$this->active = (bool) $row->active;
	}
}
