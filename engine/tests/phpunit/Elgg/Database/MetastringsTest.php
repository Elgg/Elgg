<?php
namespace Elgg\Database;

class MetastringsTest extends \PHPUnit_Framework_TestCase {

	/**
	 * Check that in both case sensitive and case insensitive you are always getting an ID
	 */
	public function testMetastringIsAlwaysAddedWhenGettingItsID() {
		
		// test case sensitive (should return id)
		//$this->assertNotEmpty(elgg_get_metastring_id(time() . "_a", true));
		
		//test case insensitive (should return array with ids)
		//$this->assertNotEmpty(elgg_get_metastring_id(time() . "_b", false));
		
		// marked as incomplete as there is no way to clean up the just created metastring ids
		$this->markTestIncomplete();
		
	}

}