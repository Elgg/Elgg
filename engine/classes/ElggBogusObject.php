<?php
/**
 * ElggBogusObject
 *
 * @author Krzysztof Rozalski <cristo.rabani@gmail.com>
 */
class ElggBogusObject {
	public function getType(){
		return "bogus:object";
	}
	public function get($name) {
		switch($name){			
			case 'name':
				return elgg_echo('elgg:bogus:object:name');
			case 'title':
				return elgg_echo('elgg:bogus:title');
			default:
				return parent::get($name);
		}
		
	}	
}
