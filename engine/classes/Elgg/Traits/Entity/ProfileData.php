<?php

namespace Elgg\Traits\Entity;

/**
 * Adds methods to save profile data to an ElggEntity.
 * Data is stored in Annotations (and Metadata for BC reasons)
 *
 * @since 3.1
 */
trait ProfileData {
	
	/**
	 * @var array all profile data
	 */
	protected $_profile_data = [];

	/**
	 * Store profile data
	 *
	 * @param string $profile_field_name profile field name
	 * @param mixed  $value              profile data
	 * @param int    $access_id          access of the profile data
	 *
	 * @return void
	 */
	public function setProfileData(string $profile_field_name, $value, int $access_id = ACCESS_PRIVATE) {
		// remove old values
		$this->deleteProfileData($profile_field_name);
		
		if (is_null($value) || $value === '') {
			// don't try to store empty values
			return;
		}
		
		// store new value(s)
		if (!is_array($value)) {
			$value = [$value];
		}
		
		foreach ($value as $v) {
			$this->annotate("profile:{$profile_field_name}", $v, $access_id, $this->guid);
		}
		
		$this->_profile_data = null;
		
		// for BC, keep storing fields in MD, but we'll read annotations only
		$this->$profile_field_name = $value;
	}
	
	/**
	 * Get profile data
	 *
	 * @param string $profile_field_name profile field name
	 *
	 * @return null|mixed null if no profile data was found
	 */
	public function getProfileData(string $profile_field_name) {
		if (_elgg_services()->userCapabilities->canBypassPermissionsCheck()) {
			// can use metadata for performance benefits if access is ignored
			return $this->{$profile_field_name};
		}

		if (empty($this->guid)) {
			// no way to return all temp annotations for an unsaved entity
			$annotations = $this->getAnnotations([
				'annotation_name' => "profile:{$profile_field_name}",
				'limit' => false,
			]);
		} else {
			$annotations = elgg_extract("profile:{$profile_field_name}", $this->getAllProfileAnnotations());
		}
		
		if (empty($annotations)) {
			return null;
		}
		
		if (!is_array($annotations)) {
			$annotations = [$annotations];
		}
		
		$result = [];
		foreach ($annotations as $annotation) {
			if ($annotation instanceof \ElggAnnotation) {
				$result[] = $annotation->value;
				continue;
			}
			
			// non saved entity has annotation as pure value
			$result[] = $annotation;
		}
		
		if (count($result) === 1) {
			return $result[0];
		}
		
		return $result;
	}
	
	/**
	 * Returns all profile annotations
	 *
	 * @return array
	 */
	protected function getAllProfileAnnotations(): array {
		// store logged in user guid to prevent unwanted access to annotations when switching logged in user during script run (e.g. ElggCoreUserTest)
		$logged_in_user_guid = elgg_get_logged_in_user_guid();
		if (!isset($this->_profile_data[$logged_in_user_guid])) {
			$annotations = $this->getAnnotations([
				'limit' => false,
				'wheres' => function(\Elgg\Database\QueryBuilder $qb, $main_alias) {
					return $qb->compare("{$main_alias}.name", 'LIKE', 'profile:%', ELGG_VALUE_STRING);
				},
			]);
			
			$profile_data = [];
			foreach ($annotations as $annotation) {
				if (!isset($profile_data[$annotation->name])) {
					$profile_data[$annotation->name] = [];
				}
				
				$profile_data[$annotation->name][] = $annotation;
			}
			
			$this->_profile_data[$logged_in_user_guid] = $profile_data;
		}
		
		return $this->_profile_data[$logged_in_user_guid];
	}
	
	/**
	 * Remove profile data
	 *
	 * @param string $profile_field_name the profile field name to remove
	 *
	 * @return bool
	 */
	public function deleteProfileData(string $profile_field_name) {
		$result = $this->deleteAnnotations("profile:{$profile_field_name}");
		$result &= $this->deleteMetadata($profile_field_name);
		
		$this->_profile_data = null;
		
		return $result;
	}
}
