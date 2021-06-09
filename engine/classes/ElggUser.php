<?php

use Elgg\Exceptions\InvalidArgumentException as ElggInvalidArgumentException;
use Elgg\Exceptions\InvalidParameterException;
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
		setPluginSetting as protected psSetPluginSetting;
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
	public function getType() {
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
	public function getLanguage($fallback = null) {
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
	 */
	public function __set($name, $value) {
		switch ($name) {
			case 'salt':
			case 'password':
				_elgg_services()->logger->error("User entities no longer contain {$name}");
				return;
			case 'password_hash':
				_elgg_services()->logger->error("password_hash is a readonly attribute.");
				return;
			case 'email':
				try {
					elgg()->accounts->assertValidEmail($value);
				} catch (RegistrationException $ex) {
					throw new InvalidParameterException($ex->getMessage(), $ex->getCode(), $ex);
				}
				break;
			case 'username':
				try {
					elgg()->accounts->assertValidUsername($value);
				} catch (RegistrationException $ex) {
					throw new InvalidParameterException($ex->getMessage(), $ex->getCode(), $ex);
				}
				$existing_user = get_user_by_username($value);
				if ($existing_user && ($existing_user->guid !== $this->guid)) {
					throw new InvalidParameterException("{$name} is supposed to be unique for ElggUser");
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
	 * {@inheritdoc}
	 */
	public function getURL() {
		
		$result = parent::getURL();
		if ($result !== '') {
			return $result;
		}
		
		return elgg_normalize_url("user/view/{$this->guid}");
	}

	/**
	 * Ban this user.
	 *
	 * @param string $reason Optional reason
	 *
	 * @return bool
	 */
	public function ban($reason = '') {

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
	public function unban() {
		
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
	public function isBanned() {
		return $this->banned == 'yes';
	}

	/**
	 * Is this user admin?
	 *
	 * @return bool
	 */
	public function isAdmin() {
		$ia = _elgg_services()->session->setIgnoreAccess(true);
		$is_admin = ($this->admin == 'yes');
		_elgg_services()->session->setIgnoreAccess($ia);
		
		return $is_admin;
	}

	/**
	 * Make the user an admin
	 *
	 * @return bool
	 */
	public function makeAdmin() {
		
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
	public function removeAdmin() {

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
	public function setLastLogin() {
		
		$time = $this->getCurrentTime()->getTimestamp();
		
		if ($this->last_login == $time) {
			// no change required
			return;
		}
		
		// these writes actually work, we just type hint read-only.
		$this->prev_last_login = $this->last_login;
		$this->last_login = $time;
	}
	
	/**
	 * Sets the last action time of the given user to right now.
	 *
	 * @see _elgg_session_boot() The session boot calls this at the beginning of every request
	 *
	 * @return void
	 */
	public function setLastAction() {
		
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
	public function isValidated() {
		if (!isset($this->validated)) {
			return null;
		}
		return (bool) $this->validated;
	}
	
	/**
	 * Set the validation status for a user.
	 *
	 * @param bool   $status    Validated (true) or unvalidated (false)
	 * @param string $method    Optional method to say how a user was validated
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
	 * Returns it's own GUID if the user is not owned.
	 *
	 * @return int
	 */
	public function getOwnerGUID() {
		if ($this->owner_guid == 0) {
			return $this->guid;
		}

		return $this->owner_guid;
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
	 * Can a user comment on this user?
	 *
	 * @see \ElggEntity::canComment()
	 *
	 * @param int  $user_guid User guid (default is logged in user)
	 * @param bool $default   Default permission
	 * @return bool
	 * @since 1.8.0
	 */
	public function canComment($user_guid = 0, $default = null) {
		return false;
	}

	/**
	 * Set the necessary metadata to store a hash of the user's password.
	 *
	 * @param string $password The password to be hashed
	 * @return void
	 * @since 1.10.0
	 */
	public function setPassword($password) {
		$this->setMetadata('password_hash', _elgg_services()->passwords->generateHash($password));
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
	public function setNotificationSetting(string $method, bool $enabled = true, string $purpose = 'default') {
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
	public function getNotificationSettings(string $purpose = 'default') {
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
	public function delete($recursive = true) {
		$result = parent::delete($recursive);
		if ($result) {
			// cleanup remember me cookie records
			_elgg_services()->persistentLogin->removeAllHashes($this);
		}
		
		return $result;
	}
	
	/**
	 * Save a plugin setting
	 *
	 * @param string $plugin_id plugin ID
	 * @param string $name      setting name
	 * @param mixed  $value     setting value (needs to be a scalar)
	 *
	 * @return bool
	 * @see \Elgg\Traits\Entity\PluginSettings::setPluginSetting()
	 */
	public function setPluginSetting(string $plugin_id, string $name, $value): bool {
		$value = _elgg_services()->hooks->triggerDeprecated('usersetting', 'plugin', [
			'user' => $this,
			'plugin' => _elgg_services()->plugins->get($plugin_id),
			'plugin_id' => $plugin_id,
			'name' => $name,
			'value' => $value,
		], $value, "Please user the plugin hook 'plugin_settings', '{$this->getType()}'", '4.0');
		
		return $this->psSetPluginSetting($plugin_id, $name, $value);
	}
	
	/**
	 * Get a plugin setting
	 *
	 * @param string $plugin_id plugin ID
	 * @param string $name      setting name
	 * @param mixed  $default   default setting value (will be cast to string)
	 *
	 * @return string
	 * @see \Elgg\Traits\Entity\PluginSettings::getPluginSetting()
	 */
	public function getPluginSetting(string $plugin_id, string $name, $default = null): string {
		$plugin = _elgg_services()->plugins->get($plugin_id);
		if ($plugin instanceof \ElggPlugin) {
			$static_defaults = (array) $plugin->getStaticConfig('user_settings', []);
			
			$default = elgg_extract($name, $static_defaults, $default);
		}
		
		return $this->psGetPluginSetting($plugin_id, $name, $default);
	}
}
