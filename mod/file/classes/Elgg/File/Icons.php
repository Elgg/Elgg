<?php

namespace Elgg\File;

/**
 * Hook callbacks for icons
 *
 * @since 4.0
 *
 * @internal
 */
class Icons {
	
	/**
	 * Handle an object being deleted
	 *
	 * @param \Elgg\Event $event 'delete', 'object'
	 *
	 * @return void
	 */
	public static function deleteIconOnElggFileDelete(\Elgg\Event $event) {
		$file = $event->getObject();
		if (!$file instanceof \ElggFile) {
			return;
		}
		if (!$file->guid) {
			// this is an ElggFile used as temporary API
			return;
		}
	
		$file->deleteIcon();
	}
	
	/**
	 * Set custom icon sizes for file objects
	 *
	 * @param \Elgg\Hook $hook "entity:icon:url", "object"
	 *
	 * @return array
	 */
	public static function setIconSizes(\Elgg\Hook $hook) {
	
		if ($hook->getParam('entity_subtype') !== 'file') {
			return;
		}
	
		$return = $hook->getValue();
		
		$return['small'] = [
			'w' => 60,
			'h' => 60,
			'square' => true,
			'upscale' => true,
		];
		$return['medium'] = [
			'w' => 153,
			'h' => 153,
			'square' => true,
			'upscale' => true,
		];
		$return['large'] = [
			'w' => 600,
			'h' => 600,
			'upscale' => false,
		];
		
		return $return;
	}
	
	/**
	 * Set custom file thumbnail location
	 *
	 * @param \Elgg\Hook $hook "entity:icon:file", "object"
	 *
	 * @return \ElggIcon
	 */
	public static function setIconFile(\Elgg\Hook $hook) {
	
		$entity = $hook->getEntityParam();
		if (!$entity instanceof \ElggFile) {
			return;
		}
		
		$size = $hook->getParam('size', 'large');
		switch ($size) {
			case 'small' :
				$filename_prefix = 'thumb';
				$metadata_name = 'thumbnail';
				break;
	
			case 'medium' :
				$filename_prefix = 'smallthumb';
				$metadata_name = 'smallthumb';
				break;
	
			default :
				$filename_prefix = "{$size}thumb";
				$metadata_name = $filename_prefix;
				break;
		}
	
		$icon = $hook->getValue();
		
		$icon->owner_guid = $entity->owner_guid;
		if (isset($entity->$metadata_name)) {
			$icon->setFilename($entity->$metadata_name);
		} else {
			$filename = pathinfo($entity->getFilenameOnFilestore(), PATHINFO_FILENAME);
			$filename = "file/{$filename_prefix}{$filename}.jpg";
			$icon->setFilename($filename);
		}
		
		return $icon;
	}
}
