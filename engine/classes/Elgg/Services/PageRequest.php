<?php
namespace Elgg\Services;

/**
 * Models the API handed to page handler
 */
interface PageRequest {

	/**
	 * Get the identifier
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * Get the URL segments (not including the identifier)
	 *
	 * @return string[]
	 */
	public function getSegments();

	/**
	 * Get a particular URL segment (not including the identifier)
	 *
	 * @param int   $index   Index of the URL segment
	 * @param mixed $default Return value if index is out of range
	 *
	 * @return mixed
	 */
	public function getSegment($index, $default = null);

	/**
	 * Get the Elgg application
	 *
	 * @return \Elgg\Application
	 */
	public function elgg();
}
