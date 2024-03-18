<?php

namespace Elgg\Security;

use Elgg\Exceptions\InvalidArgumentException;

/**
 * Component for creating HMAC tokens
 */
class Hmac {

	/**
	 * @var callable
	 */
	protected $comparator;

	protected string $data;

	/**
	 * Constructor
	 *
	 * @param string   $key        HMAC key
	 * @param callable $comparator Function that returns true if given two equal strings, else false
	 * @param mixed    $data       HMAC data string or serializable data
	 * @param string   $algo       Hash algorithm
	 *
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 */
	public function __construct(protected string $key, callable $comparator, $data, protected string $algo = 'sha256') {
		$this->comparator = $comparator;
		if (!$data) {
			throw new InvalidArgumentException('$data cannot be empty');
		}
		
		if (!is_string($data)) {
			$data = serialize($data);
		}
		
		$this->data = $data;
	}

	/**
	 * Get the HMAC token in Base64URL encoding
	 *
	 * @return string
	 */
	public function getToken(): string {
		$bytes = hash_hmac($this->algo, $this->data, $this->key, true);
		return Base64Url::encode($bytes);
	}

	/**
	 * Does the MAC match the given token?
	 *
	 * @param string $token HMAC token in Base64URL encoding
	 *
	 * @return bool
	 */
	public function matchesToken($token): bool {
		$expected_token = $this->getToken();
		return call_user_func($this->comparator, $expected_token, $token);
	}
}
