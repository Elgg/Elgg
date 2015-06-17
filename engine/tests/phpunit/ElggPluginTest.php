<?php

use PHPUnit_Framework_TestCase as TestCase;

class ElggPluginTest extends TestCase {

	public function testPicksUpViewsFromTheViewsDirWhenEnabled() {
	    // Assume a plugin directory with a valid views directory
	    // Try to register a plugin to that directory
	    // Check that all views and viewtypes present are correctly registered
	    $this->markTestIncomplete();
	}
	
	public function testThrowsExceptionOnInvalidViewtypeInViewsDir() {
	    // Assume a plugin directory with an invalid viewtype in the views dir
	    // Try to register a plugin to that directory
	    // Check that an exception was thrown with details about which viewtype caused the failure
	    $this->markTestIncomplete();
	}
	
	public function testViewsDirectoryIsOptional() {
	    // Assume plugin directory with no views directory
	    // Try to register a new plugin on that directory
	    // Expect it to succeed just file
	    $this->markTestIncomplete();
	}
	
	public function testThrowsExceptionOnUnreadableViewsDirectory() {
	    // Assume plugin directory with an unreadable views file/directory
	    // Try to register a new plugin on that directory
	    // Expect an exception to have been thrown
	    $this->markTestIncomplete();
	}
}
