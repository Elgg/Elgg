<?php
/**
 * Class used as part of multiple database connections configuration. 
 * It defines sets of connection parameters for different types of connection links.
 * 
 * @package    Elgg.Core
 * @subpackage Database
 */
class ElggDatabaseConfig implements ArrayAccess {
	
	/**
	 * @var bool determines if we should have single read/write connection or separate ones
	 */
	public $split = true;

	/**
	 * @var array
	 */
	private $data = array();

	/**
	 * ArrayAccess interface
	 * 
	 * @param mixed $offset Array key
	 * @return bool
	 * @see ArrayAccess::offsetExists()
	 */
	public function offsetExists($offset) {
		return isset($this->data[$offset]);
	}

	/**
	 * ArrayAccess interface
	 * 
	 * @param mixed $offset Array key
	 * @return mixed
	 * @see ArrayAccess::offsetGet()
	 */
	public function offsetGet($offset) {
		return $this->data[$offset];
	}

	/**
	 * ArrayAccess interface
	 * 
	 * @param mixed $offset Array key
	 * @param mixed $value Value to be set
	 * @return null
	 * @see ArrayAccess::offsetSet()
	 */
	public function offsetSet($offset, $value) {
		$this->data[$offset] = $value;
	}

	/**
	 * ArrayAccess interface
	 * 
	 * @param mixed $offset Array key
	 * @return null
	 * @see ArrayAccess::offsetUnset()
	 */
	public function offsetUnset($offset) {
		unset($this->data[$offset]);
	}

	/**
	 * Shortcut for adding multiple configurations
	 * 
	 * @param string         $dblinkname usually read|write
	 * @param stdClass|array $conf object with dbuser, dbpass, etc. fields or array with equivalent keys
	 * @param bool           $default marks this config as site-default one, used by cache handlers. False by default.
	 * @return null
	 */
	public function addConfig($dblinkname, $conf, $default = false) {
		if (!isset($this->data[$dblinkname]) || !is_array($this->data[$dblinkname])) {
			if (isset($this[$dblinkname])) {
				$this->data[$dblinkname] = array($this->data[$dblinkname]);
			} else {
				$this->data[$dblinkname] = array();
			}
		}
		if ($conf instanceof stdClass) {
			$this->data[$dblinkname][] = $conf;
			if ($default) {
				$this->setDefaultConfig($conf);
			}
		} elseif (is_array($conf)) {
			$res = new stdClass();
			foreach ($conf as $k => $v) {
				$res->$k = $v;
			}
			$this->data[$dblinkname][] = $res;
			if ($default) {
				$this->setDefaultConfig($res);
			}
		} else {
			throw Exception("Invalid parameter passed to " . __METHOD__);
		}
	}

	/**
	 * Sets the config as default one, used by cache handlers
	 * 
	 * @param stdClass|array $conf representation of Elgg main DB configuration
	 * @return null
	 */
	public function setDefaultConfig($conf) {
		global $CONFIG;
		foreach ($conf as $k => $v) {
			$CONFIG->$k = $v;
		}
	}

	/**
	 * Get config by the policy. Random by default.
	 * 
	 * @param string $dblinkname Name of the type of DB link, usually read|write
	 * @return stdClass|null returns random config of specified class or returns null on failure.
	 */
	public function getConfig($dblinkname) {
		if (isset($this->data[$dblinkname])) {
			if (is_array($this->data[$dblinkname])) {//get random
				$index = rand(0, count($this->data[$dblinkname]) - 1);
				return $this->data[$dblinkname][$index];
			} else {
				return $this->data[$dblinkname];
			}
		}
		return null;
	}
}
