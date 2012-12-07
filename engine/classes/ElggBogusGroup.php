<?php

/**
 * ElggBogusGroup
 *
 * @author Krzysztof Rozalski <cristo.rabani@gmail.com>
 */
class ElggBogusGroup {
	public function getType(){
		return "bogus:group";
	}
	public function get($name) {
		switch($name){			
			case 'name':
				return elgg_echo('elgg:bogus:name');			
			default:
				return parent::get($name);
		}
		
	}
}
