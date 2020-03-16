<?php

namespace Elgg\Http;

use Elgg\Exceptions\InvalidArgumentException;

/**
 * Response builder
 *
 * @since 4.0
 * @internal
 */
abstract class Response implements ResponseBuilder {

	/**
	 * @var string
	 */
	protected $content;

	/**
	 * @var int
	 */
	protected $status_code;

	/**
	 * @var string
	 */
	protected $forward_url;

	/**
	 * @var array
	 */
	protected $headers;

	/**
	 * @var \Exception
	 */
	protected $exception;

	/**
	 * {@inheritdoc}
	 */
	public function setContent($content = '') {
		if (isset($content) && !is_scalar($content) && !is_array($content)) {
			throw new InvalidArgumentException(__METHOD__ . ' expects content as a scalar value or array');
		}
		$this->content = $content;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContent() {
		return $this->content;
	}

	/**
	 * {@inheritDoc}
	 */
	public function setException(\Exception $e) {
		$this->exception = $e;
		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getException() {
		return $this->exception;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setStatusCode(int $status_code) {
		if ($status_code < 100 || $status_code > 599) {
			throw new InvalidArgumentException(__METHOD__ . ' expects a valid HTTP status code');
		}
		$this->status_code = (int) $status_code;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getStatusCode() {
		return $this->status_code;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setForwardURL($forward_url = REFERRER) {
		if (isset($forward_url) && !is_string($forward_url) && $forward_url !== REFERRER) {
			throw new InvalidArgumentException(__METHOD__ . ' expects a valid URL or REFERRER');
		}
		$this->forward_url = $forward_url;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getForwardURL() {
		return $this->forward_url;
	}

	/**
	 * {@inheritdoc}
	 */
	public function setHeaders(array $headers = []) {
		$this->headers = $headers;
		return $this;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getHeaders() {
		return (array) $this->headers;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isInformational() {
		return $this->status_code >= 100 && $this->status_code <= 199;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isSuccessful() {
		return $this->status_code >= 200 && $this->status_code <= 299;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isOk() {
		return $this->status_code === 200;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isRedirection() {
		return in_array($this->status_code, [201, 301, 302, 303, 307, 308]);
	}

	/**
	 * {@inheritdoc}
	 */
	public function isClientError() {
		return $this->status_code >= 400 && $this->status_code <= 499;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isServerError() {
		return $this->status_code >= 500 && $this->status_code <= 599;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isNotModified() {
		return $this->status_code === 304;
	}
}
