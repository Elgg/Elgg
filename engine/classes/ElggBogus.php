<?php

/**
 * The parent class for all Elgg Bogus.
 * Any subclass of this class cannot save any data to db.
 *
 * @author Krzysztof Rozalski <cristo.rabani@gmail.com>
 */
abstract class ElggBogus extends ElggEntity {
	private $true_guid;
	/**
	 * @see ElggEntity::load()
	 * @param int $guid 
	 */
	protected function load($guid) {
		$this->true_guid = $guid;
		parent::load();
	}
	/**
	 * creates bogus entity
	 * @param int $guid for which we create bogus entity
	 */
	function __construct($guid = null) {
		$this->true_guid = $guid;		
	}
	
	public function save() {
		return false;
	}
	
	
	public function set($name, $value) {
		return false;
	}
	
	public function get($name) {
		switch($name){
			case 'guid':
				return 0;
			case 'access_id':
				return ACCESS_PUBLIC;			
		}
		
	}
	/**
	 * @see ElggEntity::getGUID()
	 * @return int always 0
	 */
	public function getGUID() {
		return 0;
	}
	/**
	 *	Returns guid given in constructor
	 * @return int | null
	 */
	public function getTrueGuid(){
		return $this->true_guid;
	}
	/**
	 * @see ElggEntity::setMetaData()
	 * @param type $name
	 * @param type $value
	 * @param type $value_type
	 * @param type $multiple
	 * @return boolean 
	 */
	public function setMetaData($name, $value, $value_type = "", $multiple = false) {
		return false;
	}
	/**
	 * @see ElggEntity::clearMetaData()
	 * @param type $name
	 * @return boolean 
	 */
	public function clearMetaData($name = '') {
		return false;
	}
	/**
	 * @see ElggEntity::canEdit()
	 * @param type $user_guid
	 * @return boolean 
	 */
	function canEdit($user_guid = 0) {
		return false;
	}
	/**
	 * @see ElggEntity::canWriteToContainer()
	 * @param type $user_guid
	 * @param type $type
	 * @param type $subtype
	 * @return boolean 
	 */
	public function canWriteToContainer($user_guid = 0, $type = 'all', $subtype = 'all') {
		return false;
	}

}