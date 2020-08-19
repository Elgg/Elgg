<?php

namespace Elgg\Helpers;

/**
 * @see \Elgg\EntityPreloaderUnitTest
 */
class PreloaderMock20140623 {
	
	function isCached($guid) {
		return $guid < 100;
	}
	
	function load($opts) {
		
	}
}
