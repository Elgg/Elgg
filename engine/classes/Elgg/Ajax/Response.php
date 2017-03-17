<?php
namespace Elgg\Ajax;

/**
 * JSON endpoint response
 *
 * @since 1.12.0
 * @access private
 * @internal Devs should type hint the interface
 */
class Response implements \Elgg\Services\AjaxResponse {

	private $ttl = 0;
	private $data = null;
	private $cancelled = false;

	/**
	 * {@inheritdoc}
	 */
	public function setTtl($ttl = 0) {
		$this->ttl = (int) max($ttl, 0);
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
	public function setData(\stdClass $data) {
		if (!property_exists($data, 'value')) {
			throw new \InvalidArgumentException('$data must have a property "value"');
		}
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
