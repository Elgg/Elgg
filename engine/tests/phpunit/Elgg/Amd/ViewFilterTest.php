<?php

class Elgg_Amd_ViewFilterTest extends PHPUnit_Framework_TestCase {

	public function testInsertsNamesForAnonymousModules() {
		$viewFilter = new Elgg_Amd_ViewFilter();

		$originalContent = "// Comment\ndefine({})";
		$filteredContent = $viewFilter->filter('js/my/mod.js', $originalContent);

		$this->assertEquals("// Comment\ndefine(\"my/mod\", {})", $filteredContent);
	}

	public function testLeavesNamedModulesAlone() {
		$viewFilter = new Elgg_Amd_ViewFilter();

		$originalContent = "// Comment\ndefine('any/mod', {})";
		$filteredContent = $viewFilter->filter('js/my/mod.js', $originalContent);
		$this->assertEquals($originalContent, $filteredContent);
	}
}
