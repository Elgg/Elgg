<?php

use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;
use Elgg\Exceptions\Configuration\RegistrationException;
use Elgg\Traits\Entity\Friends;
use Elgg\Traits\Entity\PluginSettings;
use Elgg\Traits\Entity\ProfileData;

/**
 * A user entity
 *
 * @property      string $name             The display name that the user will be known by in the network
 * @property      string $username         The short, reference name for the user in the network
 * @property      string $email            The email address to which Elgg will send email notifications
 * @property      string $language         The language preference of the user (ISO 639-1 formatted)
 * @property-read string $banned           'yes' if the user is banned from the network, 'no' otherwise
 * @property      string $ban_reason       The reason why the user was banned
 * @property-read string $admin            'yes' if the user is an administrator of the network, 'no' otherwise
 * @property      bool   $validated        User validation status
 * @property      string $validated_method User validation method
 * @property      int    $validated_ts     A UNIX timestamp of the last moment a users validation status is set to true
 * @property-read string $password_hash    The hashed password of the user
 * @property-read int    $prev_last_action A UNIX timestamp of the previous last action
 * @property-read int    $first_login      A UNIX timestamp of the first login
 * @property-read int    $last_login       A UNIX timestamp of the last login
 * @property-read int    $prev_last_login  A UNIX timestamp of the previous login
 */
class ElggUser extends \ElggEntity {

	use Friends;
	use PluginSettings {
		getPluginSetting as protected psGetPluginSetting;
	}
	use ProfileData;
	
	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'user';
		
		$this->attributes['access_id'] = ACCESS_PUBLIC;
		$this->attributes['owner_guid'] = 0; // Users aren't owned by anyone, even if they are admin created.
		$this->attributes['container_guid'] = 0; // Users aren't contained by anyone, even if they are admin created.
		
		// Before Elgg 3.0 this was handled by database logic
		$this->setMetadata('banned', 'no');
		$this->setMetadata('admin', 'no');
		$this->language = elgg_get_config('language');
		$this->prev_last_action = 0;
		$this->last_login = 0;
		$this->prev_last_login = 0;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType(): string {
		return 'user';
	}
	
	/**
	 * Get user language or default to site language
	 *
	 * @param string $fallback If this is provided, it will be returned if the user doesn't have a language set.
	 *                         If null, the site language will be returned.
	 *
	 * @return string
	 */
	public function getLanguage(string $fallback = null): string {
		if (!empty($this->language)) {
			return $this->language;
		}
		
		if ($fallback !== null) {
			return $fallback;
		}
		
		return elgg_get_config('language');
	}

	/**
	 * {@inheritdoc}
	 *
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 */
	public function __set($name, $value) {
		switch ($name) {
			case 'salt':
			case 'password':
				_elgg_services()->logger->error("User entities no longer contain {$name}");
				return;
			case 'password_hash':
				_elgg_services()->logger->error('password_hash is a readonly attribute.');
				return;
			case 'email':
				try {
					_elgg_services()->accounts->assertValidEmail($value);
				} catch (RegistrationException $ex) {
					throw new ElggInvalidArgumentException($ex->getMessage(), $ex->getCode(), $ex);
				}
				break;
			case 'username':
				try {
					_elgg_services()->accounts->assertValidUsername($value);
				} catch (RegistrationException $ex) {
					throw new ElggInvalidArgumentException($ex->getMessage(), $ex->getCode(), $ex);
				}
				
				$existing_user = elgg_get_user_by_username($value);
				if ($existing_user instanceof \ElggUser && ($existing_user->guid !== $this->guid)) {
					throw new ElggInvalidArgumentException("{$name} is supposed to be unique for ElggUser");
				}
				break;
			case 'admin':
				throw new ElggInvalidArgumentException(_elgg_services()->translator->translate('ElggUser:Error:SetAdmin', ['makeAdmin() / removeAdmin()']));
			case 'banned':
				throw new ElggInvalidArgumentException(_elgg_services()->translator->translate('ElggUser:Error:SetBanned', ['ban() / unban()']));
		}
		
		parent::__set($name, $value);
	}
	
	/**
	 * Ban this user.
	 *
	 * @param string $reason Optional reason
	 *
	 * @return bool
	 */
	public function ban(string $reason = ''): bool {

		if (!$this->canEdit()) {
			return false;
		}
		
		if (!_elgg_services()->events->trigger('ban', 'user', $this)) {
			return false;
		}

		$this->ban_reason = $reason;
		$this->setMetadata('banned', 'yes');

		$this->invalidateCache();

		return true;
	}

	/**
	 * Unban this user.
	 *
	 * @return bool
	 */
	public function unban(): bool {
		
		if (!$this->canEdit()) {
			return false;
		}

		if (!_elgg_services()->events->trigger('unban', 'user', $this)) {
			return false;
		}

		unset($this->ban_reason);
		$this->setMetadata('banned', 'no');

		$this->invalidateCache();

		return true;
	}

	/**
	 * Is this user banned or not?
	 *
	 * @return bool
	 */
	public function isBanned(): bool {
		return $this->banned === 'yes';
	}

	/**
	 * Is this user admin?
	 *
	 * @return bool
	 */
	public function isAdmin(): bool {
		return $this->admin === 'yes';
	}

	/**
	 * Make the user an admin
	 *
	 * @return bool
	 */
	public function makeAdmin(): bool {
		
		if ($this->isAdmin()) {
			return true;
		}

		if (!_elgg_services()->events->trigger('make_admin', 'user', $this)) {
			return false;
		}

		$this->setMetadata('admin', 'yes');

		$this->invalidateCache();
		
		return true;
	}

	/**
	 * Remove the admin flag for user
	 *
	 * @return bool
	 */
	public function removeAdmin(): bool {

		if (!$this->isAdmin()) {
			return true;
		}

		if (!_elgg_services()->events->trigger('remove_admin', 'user', $this)) {
			return false;
		}

		$this->setMetadata('admin', 'no');

		$this->invalidateCache();
		
		return true;
	}
	
	/**
	 * Sets the last logon time of the user to right now.
	 *
	 * @return void
	 */
	public function setLastLogin(): void {
		$time = $this->getCurrentTime()->getTimestamp();
		
		if ($this->last_login == $time) {
			// no change required
			return;
		}
		
		elgg_call(ELGG_IGNORE_ACCESS | ELGG_DISABLE_SYSTEM_LOG, function() use ($time) {
			// these writes actually work, we just type hint read-only.
			$this->prev_last_login = $this->last_login;
			$this->last_login = $time;
		});
	}
	
	/**
	 * Sets the last action time of the given user to right now.
	 *
	 * @return void
	 */
	public function setLastAction(): void {
		
		$time = $this->getCurrentTime()->getTimestamp();
		
		if ($this->last_action == $time) {
			// no change required
			return;
		}
		
		$user = $this;
		
		elgg_register_event_handler('shutdown', 'system', function () use ($user, $time) {
			// these writes actually work, we just type hint read-only.
			$user->prev_last_action = $user->last_action;
		
			$user->updateLastAction($time);
		});
	}
	
	/**
	 * Gets the validation status of a user.
	 *
	 * @return bool|null Null means status was not set for this user.
	 */
	public function isValidated(): ?bool {
		if (!isset($this->validated)) {
			return null;
		}
		
		return (bool) $this->validated;
	}
	
	/**
	 * Set the validation status for a user.
	 *
	 * @param bool   $status Validated (true) or unvalidated (false)
	 * @param string $method Optional method to say how a user was validated
	 *
	 * @return void
	 */
	public function setValidationStatus(bool $status, string $method = ''): void {
		if ($status === $this->isValidated()) {
			// no change needed
			return;
		}
		
		$this->validated = $status;
		
		if ($status) {
			$this->validated_method = $method;
			$this->validated_ts = time();
		
			// make sure the user is enabled
			if (!$this->isEnabled()) {
				$this->enable();
			}
			
			// let the system know the user is validated
			_elgg_services()->events->triggerAfter('validate', 'user', $this);
		} else {
			// invalidating
			unset($this->validated_ts);
			unset($this->validated_method);
			_elgg_services()->events->triggerAfter('invalidate', 'user', $this);
		}
	}

	/**
	 * Gets the user's groups
	 *
	 * @param array $options Options array.
	 *
	 * @return \ElggGroup[]|int|mixed
	 */
	public function getGroups(array $options = []) {
		$options['type'] = 'group';
		$options['relationship'] = 'member';
		$options['relationship_guid'] = $this->guid;

		return elgg_get_entities($options);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getObjects(array $options = []) {
		$options['type'] = 'object';
		$options['owner_guid'] = $this->guid;

		return elgg_get_entities($options);
	}

	/**
	 * Get a user's owner GUID
	 *
	 * Returns its own GUID if the user is not owned.
	 *
	 * @return int
	 */
	public function getOwnerGUID(): int {
		$owner_guid = parent::getOwnerGUID();
		if ($owner_guid === 0) {
			$owner_guid = (int) $this->guid;
		}

		return $owner_guid;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function prepareObject(\Elgg\Export\Entity $object) {
		$object = parent::prepareObject($object);
		$object->name = $this->getDisplayName();
		$object->username = $this->username;
		$object->language = $this->language;
		unset($object->read_access);
		return $object;
	}

	/**
	 * Set the necessary metadata to store a hash of the user's password.
	 *
	 * @param string $password The password to be hashed
	 *
	 * @return void
	 * @since 1.10.0
	 */
	public function setPassword(string $password): void {
		$this->setMetadata('password_hash', _elgg_services()->passwords->generateHash($password));
		if ($this->guid === elgg_get_logged_in_user_guid()) {
			// update the session user token, so this session remains valid
			// other sessions for this user will be invalidated
			_elgg_services()->session_manager->setUserToken();
		}
	}

	/**
	 * Enable or disable a notification delivery method
	 *
	 * @param string $method  Method name
	 * @param bool   $enabled Enabled or disabled (default: true)
	 * @param string $purpose For what purpose is the notification setting used (default: 'default')
	 *
	 * @return bool
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 */
	public function setNotificationSetting(string $method, bool $enabled = true, string $purpose = 'default'): bool {
		if (empty($purpose)) {
			throw new ElggInvalidArgumentException(__METHOD__ . ' requires $purpose to be set to a non-empty string');
		}
		
		$this->{"notification:{$purpose}:{$method}"} = (int) $enabled;
		return $this->save();
	}

	/**
	 * Returns users's notification settings
	 * <code>
	 *    [
	 *       'email' => true, // enabled
	 *       'ajax' => false, // disabled
	 *    ]
	 * </code>
	 *
	 * @param string $purpose For what purpose to get the notification settings (default: 'default')
	 *
	 * @return array
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 */
	public function getNotificationSettings(string $purpose = 'default'): array {
		if (empty($purpose)) {
			throw new ElggInvalidArgumentException(__METHOD__ . ' requires $purpose to be set to a non-empty string');
		}
		
		$settings = [];

		$methods = _elgg_services()->notifications->getMethods();
		foreach ($methods as $method) {
			if ($purpose !== 'default' && !isset($this->{"notification:{$purpose}:{$method}"})) {
				// fallback to the default settings
				$settings[$method] = (bool) $this->{"notification:default:{$method}"};
			} else {
				$settings[$method] = (bool) $this->{"notification:{$purpose}:{$method}"};
			}
		}

		return $settings;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function delete(bool $recursive = true): bool {
		$result = parent::delete($recursive);
		if ($result) {
			// cleanup remember me cookie records
			_elgg_services()->users_remember_me_cookies_table->deleteAllHashes($this);
		}
		
		return $result;
	}
	
	/**
	 * Get a plugin setting
	 *
	 * @param string $plugin_id plugin ID
	 * @param string $name      setting name
	 * @param mixed  $default   default setting value
	 *
	 * @return mixed
	 * @see \Elgg\Traits\Entity\PluginSettings::getPluginSetting()
	 */
	public function getPluginSetting(string $plugin_id, string $name, $default = null) {
		$plugin = _elgg_services()->plugins->get($plugin_id);
		if ($plugin instanceof \ElggPlugin) {
			$static_defaults = (array) $plugin->getStaticConfig('user_settings', []);
			
			$default = elgg_extract($name, $static_defaults, $default);
		}
		
		return $this->psGetPluginSetting($plugin_id, $name, $default);
	}
}
