<?php

/**
 * @access private
 */
abstract class ElggAbstractQueryModifier implements ElggQueryModifierInterface {

	/**
	 * @var array
	 */
	protected $options;

	/**
	 * @var ElggQueryModifierInterface[]
	 */
	protected $modifiers = array();

	/**
	 * @param array $options
	 */
	public function __construct(array $options = array()) {
		$this->options = $options;
	}

	/**
	 * Modify the $options
	 *
	 * @return void
	 */
	abstract protected function execute();

	/**
	 * Get the modified $options array for an elgg_get_*() query
	 *
	 * @return array
	 */
	public function getOptions() {
		$this->execute();
		foreach ($this->modifiers as $mod) {
			$this->options = $mod->getOptions();
		}
	}

	/**
	 * Add a query modifier to be applied after this one. Generally getOptions() will return $options
	 * after all chained modifiers have had the chance to modify it.
	 *
	 * @param ElggQueryModifierInterface $modifier
	 */
	public function addChainedModifier(ElggQueryModifierInterface $modifier) {
		$this->modifiers[] = $modifier;
	}
}
