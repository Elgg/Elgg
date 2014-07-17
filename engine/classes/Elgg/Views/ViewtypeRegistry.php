<?php

namespace Elgg\Views;

use Elgg\Structs\ArrayCollection;
use Elgg\Structs\Map;
use Elgg\Http\Input;


/**
 * Manages the list of registered views as well as the current system viewtype.
 * 
 * @since 2.0.0
 * @access private
 */
class ViewtypeRegistry implements Map/*<string,Viewtype>*/ {
	/** @var \stdClass */
	private $config;
	
	/** @var Viewtype[] */
	private $items;
	
	/** @var Input */
	private $input;
	
	/** @var ?Viewtype */
	private $current;
	
	/**
	 * Constructor
	 * 
	 * @param \stdClass $config The Elgg config object
	 * @param Input     $input  Request inputs
	 */
	public function __construct(\stdClass $config, Input $input) {
		$this->config = $config;
		$this->input = $input;
		$this->items = [];
	}
	
	/** @inheritDoc */
	public function get($name) {
		if (!$this->has($name)) {
			$this->items[$name] = Viewtype::create($name);
		}
		
		return $this->items[$name];
	}
	
	/** @inheritDoc */
	public function has($name) {
		return isset($this->items[$name]);
	}
	
	/** @inheritDoc */
	public function keys() {
		return new ArrayCollection(array_keys($this->items));
	}

	/** @inheritDoc */
	public function values() {
		return new ArrayCollection(array_values($this->items));
	}
	
	
	/**
	 * Forces the current viewtype to a new value.
	 * 
	 * @param Viewtype $viewtype The new current viewtype.
	 * 
	 * @return void
	 */
	public function setCurrent(Viewtype $viewtype = null) {
		if (!$this->values()->contains($viewtype)) {
			throw new \Exception();
		}
		
		$this->current = $viewtype;
	}

	/**
	 * Returns the current view type.
	 *
	 * Viewtypes are automatically detected and can be set with
	 * $_REQUEST['view'] or {@link elgg_set_viewtype()}.
	 *
	 * @internal Viewtype is determined in this order:
	 *  * $this->current: Any overrides by {@link setCurrent()}
	 *  * $CONFIG->view: The default view as saved in the DB.
	 *
	 * @return Viewtype
	 */
	public function getCurrent() {
		if ($this->current !== null) {
			return $this->current;
		}

		try {
			$viewtypeInput = $this->input->get('view', '', false);
			return $this->get($viewtypeInput);
		} catch (\Exception $e) {}
		
		try {
			return $this->get($this->config->view);
		} catch (\Exception $e) {}
		
		return $this->get('default');
	}
}
