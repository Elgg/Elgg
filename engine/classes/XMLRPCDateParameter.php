<?php
/**
 * An ISO8601 data and time.
 *
 * @package    Elgg.Core
 * @subpackage XMLRPC
 */
class XMLRPCDateParameter extends XMLRPCParameter {
	/**
	 * Construct a date
	 *
	 * @param int $timestamp The unix timestamp, or blank for "now".
	 */
	function __construct($timestamp = 0) {
		parent::__construct();

		$this->value = $timestamp;

		if (!$timestamp) {
			$this->value = time();
		}
	}

	/**
	 * Convert to string
	 *
	 * @return string
	 */
	function __toString() {
		$value = date('c', $this->value);
		return "<value><dateTime.iso8601>{$value}</dateTime.iso8601></value>";
	}
}
