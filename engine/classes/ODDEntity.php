<?php

/**
 * ODD Entity class.
 *
 * @package    Elgg.Core
 * @subpackage ODD
 */
class ODDEntity extends ODD {

	/**
	 * New ODD Entity
	 *
	 * @param string $uuid     A universally unique ID
	 * @param string $class    Class
	 * @param string $subclass Subclass
	 */
	function __construct($uuid, $class, $subclass = "") {
		parent::__construct();

		$this->setAttribute('uuid', $uuid);
		$this->setAttribute('class', $class);
		$this->setAttribute('subclass', $subclass);
	}

	/**
	 * Returns entity.
	 *
	 * @return 'entity'
	 */
	protected function getTagName() {
		return "entity";
	}
}
