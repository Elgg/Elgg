<?php

use Elgg\Groups\Tool;

/**
 * A group entity, used as a container for other entities.
 *
 * @property string $name        A short name that captures the purpose of the group
 * @property string $description A longer body of content that gives more details about the group
 */
class ElggGroup extends \ElggEntity {

	const CONTENT_ACCESS_MODE_UNRESTRICTED = 'unrestricted';
	const CONTENT_ACCESS_MODE_MEMBERS_ONLY = 'members_only';

	/**
	 * {@inheritdoc}
	 */
	protected function initializeAttributes() {
		parent::initializeAttributes();
		$this->attributes['subtype'] = 'group';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getType() {
		return 'group';
	}

	/**
	 * Add an \ElggObject to this group.
	 *
	 * @param \ElggObject $object The object.
	 *
	 * @return bool
	 */
	public function addObjectToGroup(\ElggObject $object) {
		$object->container_guid = $this->guid;
		return $object->save();
	}

	/**
	 * Remove an object from this containing group and sets the container to be
	 * object's owner
	 *
	 * @param \ElggObject $object The object.
	 *
	 * @return bool
	 */
	public function removeObjectFromGroup(ElggObject $object) {
		$object->container_guid = $object->owner_guid;
		return $object->save();
	}

	/**
	 * Get an array of group members.
	 *
	 * @param array $options Options array. See elgg_get_entities
	 *                       for a complete list. Common ones are 'limit', 'offset',
	 *                       and 'count'. Options set automatically are 'relationship',
	 *                       'relationship_guid', 'inverse_relationship', and 'type'.
	 *
	 * @return array
	 */
	public function getMembers(array $options = []) {
		$options['relationship'] = 'member';
		$options['relationship_guid'] = $this->getGUID();
		$options['inverse_relationship'] = true;
		$options['type'] = 'user';

		return elgg_get_entities($options);
	}

	/**
	 * Returns whether the current group has open membership or not.
	 *
	 * @return bool
	 */
	public function isPublicMembership() {
		return ($this->membership == ACCESS_PUBLIC);
	}

	/**
	 * Return the content access mode used by group_gatekeeper()
	 *
	 * @return string One of CONTENT_ACCESS_MODE_* constants
	 * @access private
	 * @since 1.9.0
	 */
	public function getContentAccessMode() {
		$mode = $this->content_access_mode;

		if (!isset($mode)) {
			if ($this->isPublicMembership()) {
				$mode = self::CONTENT_ACCESS_MODE_UNRESTRICTED;
			} else {
				$mode = self::CONTENT_ACCESS_MODE_MEMBERS_ONLY;
			}
		}

		// only support two modes for now
		if ($mode === self::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
			return $mode;
		}
		return self::CONTENT_ACCESS_MODE_UNRESTRICTED;
	}

	/**
	 * Set the content access mode used by group_gatekeeper()
	 *
	 * @param string $mode One of CONTENT_ACCESS_MODE_* constants. If empty string, mode will not be changed.
	 * @return void
	 * @access private
	 * @since 1.9.0
	 */
	public function setContentAccessMode($mode) {
		if (!$mode && $this->content_access_mode) {
			return;
		}

		// only support two modes for now
		if ($mode !== self::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
			$mode = self::CONTENT_ACCESS_MODE_UNRESTRICTED;
		}

		$this->content_access_mode = $mode;
	}

	/**
	 * Is the given user a member of this group?
	 *
	 * @param \ElggUser $user The user. Default is logged in user.
	 *
	 * @return bool
	 */
	public function isMember(\ElggUser $user = null) {
		if ($user == null) {
			$user = _elgg_services()->session->getLoggedInUser();
		}
		if (!$user) {
			return false;
		}

		$result = (bool) check_entity_relationship($user->guid, 'member', $this->guid);

		$params = [
			'user' => $user,
			'group' => $this,
		];
		return _elgg_services()->hooks->trigger('is_member', 'group', $params, $result);
	}

	/**
	 * Join a user to this group.
	 *
	 * @param \ElggUser $user   User joining the group.
	 * @param array     $params Additional params to pass to the 'join', 'group' event
	 *
	 * @return bool Whether joining was successful.
	 */
	public function join(\ElggUser $user, $params = []) {
		$result = add_entity_relationship($user->guid, 'member', $this->guid);
	
		if (!$result) {
			return false;
		}
		
		$event_params = [
			'group' => $this,
			'user' => $user,
		];
		
		if (is_array($params)) {
			$event_params = array_merge($params, $event_params);
		}
		
		_elgg_services()->events->trigger('join', 'group', $event_params);
	
		return true;
	}

	/**
	 * Remove a user from the group.
	 *
	 * @param \ElggUser $user User to remove from the group.
	 *
	 * @return bool Whether the user was removed from the group.
	 */
	public function leave(\ElggUser $user) {
		// event needs to be triggered while user is still member of group to have access to group acl
		$params = ['group' => $this, 'user' => $user];
		_elgg_services()->events->trigger('leave', 'group', $params);

		return remove_entity_relationship($user->guid, 'member', $this->guid);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function prepareObject(\Elgg\Export\Entity $object) {
		$object = parent::prepareObject($object);
		$object->name = $this->getDisplayName();
		$object->description = $this->description;
		unset($object->read_access);
		return $object;
	}

	/**
	 * Can a user comment on this group?
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
	 * Checks if a tool option is enabled
	 *
	 * @param string $name Tool name
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function isToolEnabled($name) {
		if (empty($name)) {
			return false;
		}

		$tool = $this->getTool($name);
		if (!$tool) {
			return false;
		}

		$md_name = $tool->mapMetadataName();
		$setting = $this->$md_name;

		if (!isset($setting)) {
			return $tool->isEnabledByDefault();
		}

		return $setting == 'yes';
	}

	/**
	 * Enables a tool option
	 *
	 * @param string $name The option to enable
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function enableTool($name) {
		if (!$tool = $this->getTool($name)) {
			return false;
		}

		$md_name = $tool->mapMetadataName();
		$md_value = $tool->mapMetadataValue('yes');

		$this->$md_name = $md_value;

		return true;
	}
	
	/**
	 * Disables a tool option
	 *
	 * @param Tool|string $name The option to disable
	 *
	 * @return bool
	 * @since 3.0.0
	 */
	public function disableTool($name) {
		if (!$tool = $this->getTool($name)) {
			return false;
		}

		$md_name = $tool->mapMetadataName();
		$md_value = $tool->mapMetadataValue('no');

		$this->$md_name = $md_value;

		return true;
	}

	/**
	 * Returns the registered tool configuration
	 *
	 * @param string $name Tool name
	 *
	 * @return Tool|null
	 * @deprecated 3.0 Use ElggGroup::getTool
	 */
	protected function getToolConfig($name) {
		return $this->getTool($name);
	}

	/**
	 * Returns the registered tool configuration
	 *
	 * @param string $name Tool name
	 *
	 * @return Tool|null
	 */
	protected function getTool($name) {
		return elgg()->group_tools->group($this)->get($name);
	}

	/**
	 * Check if current user can access group content based on his/her membership status
	 * and group's content access policy
	 *
	 * @param ElggUser|null $user User
	 * @return bool
	 */
	public function canAccessContent(ElggUser $user = null) {
		if (!isset($user)) {
			$user = _elgg_services()->session->getLoggedInUser();
		}

		if ($this->getContentAccessMode() == self::CONTENT_ACCESS_MODE_MEMBERS_ONLY) {
			if (!$user) {
				return false;
			}

			return $this->isMember($user) || $user->isAdmin();
		}

		return true;
	}
}
