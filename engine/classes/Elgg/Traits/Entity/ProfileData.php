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
		$annotations = $this->getAnnotations([
			'annotation_name' => "profile:{$profile_field_name}",
			'limit' => false,
		]);
		
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
	 * Remove profile data
	 *
	 * @param string $profile_field_name the profile field name to remove
	 *
	 * @return bool
	 */
	public function deleteProfileData(string $profile_field_name) {
		$result = $this->deleteAnnotations("profile:{$profile_field_name}");
		$result &= $this->deleteMetadata($profile_field_name);
		
		return $result;
	}
}
