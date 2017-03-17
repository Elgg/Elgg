<?php
namespace Elgg;

/**
 * Entities that support icons should implement this interface
 */
interface EntityIcon {
	/**
	 * Saves icons using an uploaded file as the source.
	 *
	 * @param string $input_name Form input name
	 * @param string $type       The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array  $coords     An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 */
	public function saveIconFromUploadedFile($input_name, $type = 'icon', array $coords = []);
	
	/**
	 * Saves icons using a local file as the source.
	 *
	 * @param string $filename The full path to the local file
	 * @param string $type     The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array  $coords   An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 */
	public function saveIconFromLocalFile($filename, $type = 'icon', array $coords = []);
	
	/**
	 * Saves icons using a file located in the data store as the source.
	 *
	 * @param string $file   An ElggFile instance
	 * @param string $type   The name of the icon. e.g., 'icon', 'cover_photo'
	 * @param array  $coords An array of cropping coordinates x1, y1, x2, y2
	 * @return bool
	 */
	public function saveIconFromElggFile(\ElggFile $file, $type = 'icon', array $coords = []);
	
	/**
	 * Returns entity icon as an ElggIcon object
	 * The icon file may or may not exist on filestore
	 *
	 * @param string $size Size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return \ElggIcon
	 */
	public function getIcon($size, $type = 'icon');

	/**
	 * Removes all icon files and metadata for the passed type of icon.
	 *
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return bool
	 */
	public function deleteIcon($type = 'icon');
	
	/**
	 * Returns a URL of the icon.
	 *
	 * @param array $params An array of paramaters including:
	 *                      string 'size' => the size of the icon (default: medium)
	 *                      string 'type' => the icon type (default: icon)
	 * @return string
	 */
	public function getIconURL($params);
	
	/**
	 * Returns the timestamp of when the icon was changed.
	 *
	 * @param string $size The size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 *
	 * @return int|null A unix timestamp of when the icon was last changed, or null if not set.
	 */
	public function getIconLastChange($size, $type = 'icon');
	
	/**
	 * Returns if the entity has an icon of the passed type.
	 *
	 * @param string $size The size of the icon
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return bool
	 */
	public function hasIcon($size, $type = 'icon');
}
