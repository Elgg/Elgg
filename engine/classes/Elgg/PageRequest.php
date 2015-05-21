<?php
namespace Elgg;

/**
 * Models the API handed to page handler
 *
 * @access private
 */
class PageRequest implements \Elgg\Services\PageRequest {

	private $id;
	private $segments;
	private $elgg;

	/**
	 * Constructor
	 *
	 * @param Application $elgg     Elgg application
	 * @param string      $id       ID
	 * @param string[]    $segments URL segments
	 */
	public function __construct(Application $elgg, $id, $segments) {
		$this->elgg = $elgg;
		$this->id = $id;
		$this->segments = $segments;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSegments() {
		return $this->segments;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSegment($index, $default = null) {
		return isset($this->segments[$index]) ? $this->segments[$index] : $default;
	}

	/**
	 * {@inheritdoc}
	 */
	public function elgg() {
		return $this->elgg;
	}
}
