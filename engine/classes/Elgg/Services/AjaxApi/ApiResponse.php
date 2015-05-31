<?php
namespace Elgg\Services\AjaxApi;

/**
 * JSON endpoint response
 *
 * @since 2.0.0
 */
interface ApiResponse {

	/**
	 * Set the max-age for client caching
	 *
	 * @param int $ttl Time to cache in seconds
	 * @return self
	 */
	public function setTtl($ttl = 0);

	/**
	 * Get the max-age for client caching
	 *
	 * @return int
	 */
	public function getTtl();

	/**
	 * Set the response data
	 *
	 * @param mixed $data Response data. Must be able to be encoded in JSON.
	 * @return self
	 */
	public function setData($data);

	/**
	 * Get the response data
	 *
	 * @return mixed
	 */
	public function getData();

	/**
	 * Cancel the response and send a 403 header
	 *
	 * @return self
	 */
	public function cancel();

	/**
	 * Has the response been cancelled?
	 *
	 * @return bool
	 */
	public function isCancelled();
}
