<?php
class ElggViewVars extends ArrayObject /*implements 
	ArrayAccess,
	Iterator*/
{
	
	/**
	* The main attributes of an entity.
	* Holds attributes to save to database
	* This contains the site's main properties (id, etc)
	* Blank entries for all database fields should be created by the constructor.
	* Subclasses should add to this in their constructors.
	* Any field not appearing in this will be viewed as a
	*/
// 	protected $attributes = array();
	
	function __construct($vars = array()) {
		parent::__construct($vars, ArrayObject::ARRAY_AS_PROPS);
		$this->modified = false;
// 		$this->attributes = (array)$vars;
	}
	
	function __clone() {
		var_dump('cloned');
		$this->modified = false;
	}
	
	private $stack = array();
	
	public function saveStatePoint() {
		//todo do it lazy
// 		var_dump('PUSH');
		$copy = (array)$this;
		array_push($this->stack, $this->exchangeArray($copy));
		$this->checkModified = true;
		$this->modified = false;
	}
	
	public function restoreStatePoint() {
// 		var_dump('POP');
		$this->exchangeArray(array_pop($this->stack));
		$this->checkModified = true;
		$this->modified = false;
	}
	
	private $monitoring = false;
	
	private $checkModified = false;
	private $modified = false;
	
	function setMonitoring($value=true) {
		$this->monitoring = $value;
	}
	
// 	function offsetGet($index) {
// 		$val = parent::offsetGet($index);
// 		return $val;
// 	}
	
	function offsetExists($index) {
		$res = parent::offsetExists($index);
// 		var_dump('isset', $index, $res);
		return $res && parent::offsetGet($index)!==null;
	}
	
	function offsetSet($index, $newval) {
		if($this->checkModified && !$this->modified) {
			$this->modified = true;
// 			var_dump('MOD');
		}
		return parent::offsetSet($index, $newval);
	}
	
	function offsetUnset($index) {
		if($this->checkModified && !$this->modified) {
			$this->modified = true;
// 			var_dump('MOD');
		}
		return parent::offsetUnset($index);
	}
	
	/**
	 * Array access interface
	 *
	 * @see ArrayAccess::offsetGet()
	 *
	 * @param mixed $key Name
	 *
	 * @return void
	 */
	public function offsetGet($key) {
		switch ($key) {
			case 'user':
				return elgg_get_logged_in_user_entity();
				break;
			case 'config':
				global $CONFIG;
				return $CONFIG;
				break;
			case 'url':
				return elgg_get_site_url();
				break;
			case 'full_view':
				if (!isset($this['full_view']) && isset($this['full'])) {
					elgg_deprecated_notice("Use \$vars['full_view'] instead of \$vars['full']", 1.8, 2);
					$this['full_view'] = $this['full'];
				}
				break;
			case 'full_view':
				if (isset($this['full_view'])) {
					$this['full'] = $this['full_view'];
				}
				break;
		}
		
		// 	// internalname => name (1.8)
		// 	if (isset($vars['internalname']) && !isset($vars['__ignoreInternalname']) && !isset($vars['name'])) {
		// 		elgg_deprecated_notice('You should pass $vars[\'name\'] now instead of $vars[\'internalname\']', 1.8, 2);
		// 		$vars['name'] = $vars['internalname'];
		// 	} elseif (isset($vars['name'])) {
		// 		if (!isset($vars['internalname'])) {
		// 			$vars['__ignoreInternalname'] = '';
		// 		}
		// 		$vars['internalname'] = $vars['name'];
		// 	}
		
		// internalid => id (1.8)
		// 	if (isset($vars['internalid']) && !isset($vars['__ignoreInternalid']) && !isset($vars['name'])) {
		// 		elgg_deprecated_notice('You should pass $vars[\'id\'] now instead of $vars[\'internalid\']', 1.8, 2);
		// 		$vars['id'] = $vars['internalid'];
		// 	} elseif (isset($vars['id'])) {
		// 		if (!isset($vars['internalid'])) {
		// 			$vars['__ignoreInternalid'] = '';
		// 		}
		// 		$vars['internalid'] = $vars['id'];
		// 	}
		
		
// 		if ($this->monitoring) {
// 			var_dump("get", $key);
// 		}
		return parent::offsetGet($key);
// 		if (array_key_exists($key, $this->attributes)) {
// 			return $this->attributes[$key];
// 		}
	}
}