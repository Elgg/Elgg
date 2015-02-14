<?php
namespace Elgg\Json;

/**
 * Encode and decode JSON while converting empty string keys to a unique token.
 *
 * This gets around PHP's limitation of not allowing empty string object property names.
 * https://bugs.php.net/bug.php?id=46600
 *
 * @package    Elgg.Core
 * @subpackage Json
 * @access     private
 */
class EmptyKeyEncoding {

	/**
	 * @var string
	 */
	protected $token;

	/**
	 * Constructor
	 *
	 * @param string $empty_key Optional key to replace "" keys with in JSON decode
	 */
	public function __construct($empty_key = '') {
		if (!$empty_key) {
			$empty_key = sha1(microtime(true) . mt_rand());
		}
		$this->token = $empty_key;
	}

	/**
	 * Get the key that represents an empty string key in JSON
	 *
	 * @return string
	 */
	public function getEmptyKey() {
		return $this->token;
	}

	/**
	 * Decode JSON while converting empty keys to a unique token.
	 *
	 * @param string $json    JSON string
	 * @param bool   $assoc   Convert objects to assoc arrays?
	 * @param int    $depth   Allowed recursion depth
	 * @param int    $options Bitmask json_decode options
	 *
	 * @return mixed
	 * @see json_decode
	 */
	public function decode($json, $assoc = false, $depth = 512, $options = 0) {
		// Replace empty keys with the unique token
		$json = preg_replace('~([^"\\\\])""\\s*\\:~', "$1\"{$this->token}\":", $json, -1, $count);

		return json_decode($json, $assoc, $depth, $options);
	}

	/**
	 * Encode JSON while converting unique token keys to empty strings
	 *
	 * @param mixed $value   Value to encode
	 * @param int   $options Encoding options
	 * @param int   $depth   Allowed recursion depth. Do not set this before PHP 5.5
	 *
	 * @return string|false
	 */
	public function encode($value, $options = 0, $depth = 512) {
		if ($depth == 512) {
			// PHP 5.4 and earlier will choke if depth is passed in
			$json = json_encode($value, $options);
		} else {
			$json = json_encode($value, $options, $depth);
		}

		// Replace unique tokens with empty strings
		if (is_string($json)) {
			$json = str_replace("\"{$this->token}\"", '""', $json);
		}

		return $json;
	}
}
