<?php
namespace Elgg;

/**
 * Models the API handed to an action handler
 *
 * @access private
 */
class ActionRequest implements \Elgg\Services\ActionRequest {

	private $name;
	private $elgg;

	/**
	 * Constructor
	 *
	 * @param Application $elgg Elgg application
	 * @param string      $name Action name
	 */
	public function __construct(Application $elgg, $name) {
		$this->elgg = $elgg;
		$this->name = $name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function elgg() {
		return $this->elgg;
	}
}
