<?php

namespace Elgg\Traits\Entity;

/**
 * Adds helper functions to \ElggEntity in relation to icons.
 *
 * @since 6.0
 */
trait Icons {
	
	/**
	 * Saves icons using an uploaded file as the source.
	 *
	 * @param string $input_name Form input name
	 * @param string $type       The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array  $coords     An array of cropping coordinates x1, y1, x2, y2
	 *
	 * @return bool
	 */
	public function saveIconFromUploadedFile(string $input_name, string $type = 'icon', array $coords = []): bool {
		return _elgg_services()->iconService->saveIconFromUploadedFile($this, $input_name, $type, $coords);
	}
	
	/**
	 * Saves icons using a local file as the source.
	 *
	 * @param string $filename The full path to the local file
	 * @param string $type     The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array  $coords   An array of cropping coordinates x1, y1, x2, y2
	 *
	 * @return bool
	 */
	public function saveIconFromLocalFile(string $filename, string $type = 'icon', array $coords = []): bool {
		return _elgg_services()->iconService->saveIconFromLocalFile($this, $filename, $type, $coords);
	}
	
	/**
	 * Saves icons using a file located in the data store as the source.
	 *
	 * @param string $file   An ElggFile instance
	 * @param string $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array  $coords An array of cropping coordinates x1, y1, x2, y2
	 *
	 * @return bool
	 */
	public function saveIconFromElggFile(\ElggFile $file, string $type = 'icon', array $coords = []): bool {
		return _elgg_services()->iconService->saveIconFromElggFile($this, $file, $type, $coords);
	}
	
	/**
	 * Returns entity icon as an ElggIcon object
	 * The icon file may or may not exist on filestore
	 *
	 * @param string $size Size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return \ElggIcon
	 */
	public function getIcon(string $size, string $type = 'icon'): \ElggIcon {
		return _elgg_services()->iconService->getIcon($this, $size, $type);
	}
	
	/**
	 * Removes all icon files and metadata for the passed type of icon.
	 *
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return bool
	 */
	public function deleteIcon(string $type = 'icon'): bool {
		return _elgg_services()->iconService->deleteIcon($this, $type);
	}
	
	/**
	 * Returns the timestamp of when the icon was changed.
	 *
	 * @param string $size The size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return int|null A unix timestamp of when the icon was last changed, or null if not set.
	 */
	public function getIconLastChange(string $size, string $type = 'icon'): ?int {
		return _elgg_services()->iconService->getIconLastChange($this, $size, $type);
	}
	
	/**
	 * Returns if the entity has an icon of the passed type.
	 *
	 * @param string $size The size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return bool
	 */
	public function hasIcon(string $size, string $type = 'icon'): bool {
		return _elgg_services()->iconService->hasIcon($this, $size, $type);
	}
	
	/**
	 * Get the URL for this entity's icon
	 *
	 * Plugins can register for the 'entity:icon:url', '<type>' event
	 * to customize the icon for an entity.
	 *
	 * @param mixed $params A string defining the size of the icon (e.g. tiny, small, medium, large)
	 *                      or an array of parameters including 'size'
	 *
	 * @return string The URL
	 * @since 1.8.0
	 */
	public function getIconURL(string|array $params = []): string {
		return _elgg_services()->iconService->getIconURL($this, $params);
	}
}
