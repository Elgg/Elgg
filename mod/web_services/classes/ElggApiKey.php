<?php

/**
 * Class to store API key information in
 *
 * @property string public_key the public key for this api object
 *
 * @since 3.2
 */
class ElggApiKey extends ElggObject {
	
	const SUBTYPE = 'api_key';
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::initializeAttributes()
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		
		$site = elgg_get_site_entity();
		
		$this->attributes['access_id'] = ACCESS_PUBLIC;
		$this->attributes['container_guid'] = $site->guid;
		$this->attributes['owner_guid'] = $site->guid;
		$this->attributes['subtype'] = self::SUBTYPE;
		
		$this->generateKeys();
	}
	
	/**
	 * {@inheritDoc}
	 * @see ElggEntity::delete()
	 */
	public function delete($recursive = true) {
		$public_key = $this->public_key;
		
		$result = parent::delete($recursive);
		if (!$result) {
			return $result;
		}
		
		remove_api_user($public_key);
		
		return $result;
	}
	
	/**
	 * Get the api keys
	 *
	 * @return false|stdClass
	 */
	public function getKeys() {
		
		if (empty($this->public_key)) {
			return false;
		}
		
		return get_api_user($this->public_key);
	}
	
	/**
	 * Get the public key for this api object
	 *
	 * @return null|string
	 */
	public function getPublicKey() {
		return $this->public_key;
	}
	
	/**
	 * Get the secret key for this api object
	 *
	 * @return false|string
	 */
	public function getSecretKey() {
		$keys = $this->getKeys();
		if (empty($keys)) {
			return false;
		}
		
		return $keys->secret;
	}
	
	/**
	 * Generate API keys
	 *
	 * @return bool
	 */
	public function generateKeys() {
		
		$keys = create_api_user();
		if (empty($keys)) {
			return false;
		}
		
		// save new key
		$this->public_key = $keys->api_key;
		
		return true;
	}
	
	/**
	 * Regenerate API keys
	 *
	 * NOTE: this will remove the old keys from the database, therefor the old keys no longer work
	 *
	 * @return bool
	 */
	public function regenerateKeys() {
		$current_public = $this->getPublicKey();
		
		if (!$this->generateKeys()) {
			return false;
		}
		
		// remove old keys from DB
		remove_api_user($current_public);
		
		return true;
	}
}
