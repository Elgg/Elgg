<?php
namespace Elgg\Services;

/**
 * JSON endpoint response
 *
 * @since 1.12.0
 */
interface AjaxResponse {

	const RESPONSE_HOOK = 'ajax_response';

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
	 * @param \stdClass $data Response data. Must be able to be encoded in JSON.
	 * @return self
	 */
	public function setData(\stdClass $data);

	/**
	 * Get the response data, which will be a stdClass object with property "value"
	 *
	 * @return \stdClass
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
