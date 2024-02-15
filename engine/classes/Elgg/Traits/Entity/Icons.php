<?php

namespace Elgg\Traits\Entity;

use Elgg\Exceptions\InvalidArgumentException;
use Elgg\Exceptions\RangeException;

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
	
	/**
	 * Save cropping coordinates for an icon type
	 *
	 * @param array  $coords An array of cropping coordinates x1, y1, x2, y2
	 * @param string $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return void
	 * @since 6.0
	 */
	public function saveIconCoordinates(array $coords, string $type = 'icon'): void {
		// remove noise from the coords array
		$allowed_keys = ['x1', 'x2', 'y1', 'y2'];
		$coords = array_filter($coords, function($value, $key) use ($allowed_keys) {
			return in_array($key, $allowed_keys) && is_int($value);
		}, ARRAY_FILTER_USE_BOTH);
		
		if (!isset($coords['x1']) || !isset($coords['x2']) || !isset($coords['y1']) || !isset($coords['y2'])) {
			throw new InvalidArgumentException('Please provide correct coordinates [x1, x2, y1, y2]');
		}
		
		if ($coords['x1'] < 0 || $coords['x2'] < 0 || $coords['y1'] < 0 || $coords['y2'] < 0) {
			throw new RangeException("Coordinates can't have negative numbers");
		}
		
		$this->{"{$type}_coords"} = serialize($coords);
	}
	
	/**
	 * Get the cropping coordinates for an icon type
	 *
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return null|array
	 * @since 6.0
	 */
	public function getIconCoordinates(string $type = 'icon'): array {
		if (!isset($this->{"{$type}_coords"})) {
			return [];
		}
		
		$coords = unserialize($this->{"{$type}_coords"}) ?: [];
		
		// cast to integers
		array_walk($coords, function(&$value) {
			$value = (int) $value;
		});
		
		// remove invalid values
		return array_filter($coords, function($value) {
			return $value >= 0;
		});
	}
	
	/**
	 * Remove the cropping coordinates of an icon type
	 *
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return void
	 * @since 6.0
	 */
	public function removeIconCoordinates(string $type = 'icon'): void {
		unset($this->{"{$type}_coords"});
	}
	
	/**
	 * Lock thumbnail generation during icon upload/resize
	 *
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return void
	 * @since 6.0
	 * @internal for use in the \Elgg\EntityIconService
	 */
	public function lockIconThumbnailGeneration(string $type = 'icon'): void {
		$this->{"{$type}_thumbnail_locked"} = time();
	}
	
	/**
	 * Is thumbnail generation prevented
	 *
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param int    $ttl  Time-to-live for the lock in seconds (in case of errors)
	 *
	 * @return bool
	 * @since 6.0
	 * @internal for use in the \Elgg\EntityIconService
	 */
	public function isIconThumbnailGenerationLocked(string $type = 'icon', int $ttl = 30): bool {
		if (!isset($this->{"{$type}_thumbnail_locked"})) {
			return false;
		}
		
		$locked = (int) $this->{"{$type}_thumbnail_locked"};
		return $locked > (time() - $ttl);
	}
	
	/**
	 * Unlock thumbnail generation when upload/resize is complete
	 *
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return void
	 * @since 6.0
	 * @internal for use in the \Elgg\EntityIconService
	 */
	public function unlockIconThumbnailGeneration(string $type = 'icon'): void {
		unset($this->{"{$type}_thumbnail_locked"});
	}
}
