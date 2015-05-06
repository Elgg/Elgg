<?php
namespace Elgg\AjaxApi;

/**
 * JSON endpoint response
 *
 * @since 2.0.0
 * @access private
 * @internal Devs should type hint the interface
 */
class ApiResponse implements \Elgg\Services\AjaxApi\ApiResponse {

	private $ttl = 0;
	private $data = null;
	private $cancelled = false;

	/**
	 * {@inheritdoc}
	 */
	public function setTtl($ttl = 0) {
		$this->ttl = (int)max($ttl, 0);
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getTtl() {
		return $this->ttl;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setData($data) {
		$this->data = $data;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getData() {
		return $this->data;
	}

	/**
	 * {@inheritdoc}
	 */
	public function cancel() {
		$this->cancelled = true;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isCancelled() {
		return $this->cancelled;
	}
}
