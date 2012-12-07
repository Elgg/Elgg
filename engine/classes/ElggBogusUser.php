<?php

/**
 * ElggBogusUser
 * Representation of a "bogus user" in the system.
 * @see ElggBogus
 * @author Krzysztof Rozalski <cristo.rabani@gmail.com>
 */
class ElggBogusUser extends ElggBogus {
	public function getType(){
		return "bogus:user";
	}
	public function get($name) {
		switch($name){			
			case 'name':
				return elgg_echo('elgg:bogus:user:name');
			case 'username':
				return '';
			default:
				return parent::get($name);
		}
		
	}
}
