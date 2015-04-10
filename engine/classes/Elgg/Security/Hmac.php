<?php
namespace Elgg\Security;

/**
 * Component for creating HMAC tokens
 */
class Hmac {

	/**
	 * @var string
	 */
	private $key;

	/**
	 * @var callable
	 */
	private $comparator;

	/**
	 * @var string
	 */
	private $data;

	/**
	 * @var string
	 */
	private $algo;

	/**
	 * Constructor
	 *
	 * @param string          $key        HMAC key
	 * @param callable        $comparator Function that returns true if given two equal strings, else false
	 * @param string[]|string $data       HMAC data string (or array of strings)
	 * @param string          $algo       Hash algorithm
	 */
	public function __construct($key, callable $comparator, $data, $algo = 'sha256') {
		$this->key = $key;
		$this->comparator = $comparator;
		if (!$data) {
			throw new \InvalidArgumentException('$data cannot be empty');
		}
		if (is_array($data)) {
			$data = json_encode(array_map('strval', $data));
		} else {
			$data = (string)$data;
		}
		$this->data = $data;
		$this->algo = $algo;
	}

	/**
	 * Get the HMAC token in Base64URL encoding
	 *
	 * @return string
	 */
	public function getToken() {
		$bytes = hash_hmac($this->algo, $this->data, $this->key, true);
		return strtr(rtrim(base64_encode($bytes), '='), '+/', '-_');
	}

	/**
	 * Does the MAC match the given token?
	 *
	 * @param string $token HMAC token in Base64URL encoding
	 * @return bool
	 */
	public function matchesToken($token) {
		$expected_token = $this->getToken();
		return call_user_func($this->comparator, $expected_token, $token);
	}
}
