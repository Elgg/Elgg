<?php

namespace Elgg\Http;

use Elgg\Exceptions\InvalidArgumentException;

/**
 * HTTP response builder interface
 *
 * @internal
 */
interface ResponseBuilder {

	/**
	 * Sets response body
	 *
	 * @param mixed $content Content of the response as a scalar value or an array
	 * @return self
	 * @throws InvalidArgumentException
	 */
	public function setContent($content = '');

	/**
	 * Returns response body
	 * @return mixed
	 */
	public function getContent();

	/**
	 * Sets response HTTP status code
	 *
	 * @param int $status_code Status code
	 * @return self
	 * @throws InvalidArgumentException
	 */
	public function setStatusCode(int $status_code);

	/**
	 * Returns status code
	 * @return int
	 */
	public function getStatusCode();

	/**
	 * Sets redirect URL
	 *
	 * @param string $forward_url Forward URL
	 * @return self
	 * @throws InvalidArgumentException
	 */
	public function setForwardURL($forward_url = REFERRER);

	/**
	 * Returns redirect URL
	 * @return string|int|null string the forward url, null when no url was set or the constant REFERER
	 */
	public function getForwardURL();

	/**
	 * Sets additional response headers
	 *
	 * @param array $headers Headers
	 * @return self
	 */
	public function setHeaders(array $headers = []);

	/**
	 * Returns additional response headers
	 * @return array
	 */
	public function getHeaders();

	/**
	 * Check if response is informational
	 * @return bool
	 */
	public function isInformational();

	/**
	 * Check if response is successful
	 * @return bool
	 */
	public function isSuccessful();

	/**
	 * Check if response is redirection
	 * @return bool
	 */
	public function isRedirection();

	/**
	 * Check if response is client error
	 * @return bool
	 */
	public function isClientError();

	/**
	 * Check if response is server error
	 * @return bool
	 */
	public function isServerError();

	/**
	 * Check if response is OK
	 * @return bool
	 */
	public function isOk();
	
	/**
	 * Check if response has been modified
	 * @return bool
	 */
	public function isNotModified();
	
	/**
	 * Set an exception for this response
	 *
	 * @param \Exception $e the exception for this response
	 *
	 * @return self
	 */
	public function setException(\Exception $e);
	
	/**
	 * Get the exception for this reponse
	 *
	 * @return \Exception|null
	 */
	public function getException();
}
