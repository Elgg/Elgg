<?php

namespace Elgg\Http;

/**
 * To change this template use Tools | Templates.
 */
class RequestTest extends \PHPUnit_Framework_TestCase {
	
	public function testGetInputDefaultsToProvidedDefaultValue() {
		$this->markTestIncomplete();
	}
	
	public function testGetInputTriggersValidateInputHookIfAndOnlyIfFilteringIsEnabled() {
		$this->markTestIncomplete();
	}
	
	public function testGetInputCanBeOverriddenBySetInput() {
		$this->markTestIncomplete();
	}
	
	public function testGetInputChecksBothPostAndGetParams() {
		$this->markTestIncomplete();
	}
	
	public function testGetInputPushesInputContextDuringFiltering() {
		$this->markTestIncomplete();
	}
	
	public function testFilterTagsGlobal() {
		$this->markTestIncomplete();
	}
	
	public function testGlobals() {
		$this->markTestIncomplete();
		// elgg_is_xhr
		// get_input
		// set_input
		// filter_tags
		// current_page_url
		// full_url
		// forward
		// get_uploaded_file
		// get_resized_image_from_uploaded_file
		// elgg_make_sticky_form
		// elgg_normalize_url
	}
}