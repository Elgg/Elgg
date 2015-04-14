<?php
namespace Elgg;

/**
 * Entities that support icons should implement this interface
 */
interface EntityIcon {
	/**
	 * Saves icons using an uploaded file as the source.
	 * 
	 * @param string $filename The temp filename given to uploaded files by PHP.
	 *                         Use _elgg_services()->request->files
	 *                             ->get($fileInputName)->getPathName()
	 * @param string $type     The name of the icon. e.g., 'icon', 'cover_photo'
	 * 
	 * @return bool
	 */
	public function saveIconFromUploadedFile($filename, $type = 'icon');
	
	/**
	 * Saves icons using a local file as the source.
	 * 
	 * @param string $filename The full path to the local file
	 * @param string $type     The name of the icon. e.g., 'icon', 'cover_photo'
	 * 
	 * @return bool
	 */
	public function saveIconFromLocalFile($filename, $type = 'icon');
	
	/**
	 * Saves icons using a file located in the data store as the source.
	 * 
	 * @param string $file An ElggFile instance
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * 
	 * @return bool
	 */
	public function saveIconFromElggFile(ElggFile $file, $type = 'icon');
	
	/**
	 * Removes all icon files and metadata for the passed type of icon.
	 * 
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * 
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
	public function getIconUrl($params);
	
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
	 * @param string $type The name of the icon. e.g., 'icon', 'cover_photo'
	 * @return bool
	 */
	public function hasIcon($size, $type = 'icon');
}