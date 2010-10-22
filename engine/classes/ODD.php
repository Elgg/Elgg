<?php
/**
 * Open Data Definition (ODD) superclass.
 * @package Elgg
 * @subpackage Core
 */
abstract class ODD {
	/**
	 * Attributes.
	 */
	private $attributes = array();

	/**
	 * Optional body.
	 */
	private $body;

	/**
	 * Construct an ODD document with initial values.
	 */
	public function __construct() {
		$this->body = "";
	}

	public function getAttributes() {
		return $this->attributes;
	}

	public function setAttribute($key, $value) {
		$this->attributes[$key] = $value;
	}

	public function getAttribute($key) {
		if (isset($this->attributes[$key])) {
			return $this->attributes[$key];
		}

		return NULL;
	}

	public function setBody($value) {
		$this->body = $value;
	}

	public function getBody() {
		return $this->body;
	}

	/**
	 * Set the published time.
	 *
	 * @param int $time Unix timestamp
	 */
	public function setPublished($time) {
		$this->attributes['published'] = date("r", $time);
	}

	/**
	 * Return the published time as a unix timestamp.
	 *
	 * @return int or false on failure.
	 */
	public function getPublishedAsTime() {
		return strtotime($this->attributes['published']);
	}

	/**
	 * For serialisation, implement to return a string name of the tag eg "header" or "metadata".
	 * @return string
	 */
	abstract protected function getTagName();

	/**
	 * Magic function to generate valid ODD XML for this item.
	 */
	public function __toString() {
		// Construct attributes
		$attr = "";
		foreach ($this->attributes as $k => $v) {
			$attr .= ($v!="") ? "$k=\"$v\" " : "";
		}

		$body = $this->getBody();
		$tag = $this->getTagName();

		$end = "/>";
		if ($body!="") {
			$end = "><![CDATA[$body]]></{$tag}>";
		}

		return "<{$tag} $attr" . $end . "\n";
	}
}
