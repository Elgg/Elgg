<?php

namespace Elgg\Amd;

/**
 * @group UnitTests
 */
class ViewFilterUnitTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	public function testHandlesShortViewNames() {
		$viewFilter = new \Elgg\Amd\ViewFilter();

		$originalContent = "define({})";

		$this->assertEquals('define("foo", {})', $viewFilter->filter('foo.js', $originalContent));
	}

	public function testInsertsNamesForAnonymousModules() {
		$viewFilter = new \Elgg\Amd\ViewFilter();

		$originalContent = "// Comment\ndefine({})";
		$filteredContent = $viewFilter->filter('js/my/mod.js', $originalContent);

		$this->assertEquals("// Comment\ndefine(\"my/mod\", {})", $filteredContent);
	}

	public function testAllowsWhitespacePrecedingDefine() {
		$viewFilter = new \Elgg\Amd\ViewFilter();

		$originalContent = "// Comment\n\t  define({})";
		$filteredContent = $viewFilter->filter('js/my/mod.js', $originalContent);

		$this->assertEquals("// Comment\n\t  define(\"my/mod\", {})", $filteredContent);
	}

	public function testLeavesNamedModulesAlone() {
		$viewFilter = new \Elgg\Amd\ViewFilter();

		$originalContent = "// Comment\ndefine('any/mod', {})";
		$filteredContent = $viewFilter->filter('js/my/mod.js', $originalContent);
		$this->assertEquals($originalContent, $filteredContent);
	}

	public function testExtensionlessViewsMustBeInJs() {
		$viewFilter = new \Elgg\Amd\ViewFilter();

		$originalContent = "// Comment\ndefine({})";
		$filteredContent = $viewFilter->filter('nonjs/foobar/my/mod', $originalContent);
		$this->assertEquals($originalContent, $filteredContent);
	}

	public function testIgnoresNonJsExtensions() {
		$viewFilter = new \Elgg\Amd\ViewFilter();

		$originalContent = "// Comment\ndefine('any/mod', {})";
		$filteredContent = $viewFilter->filter('js/foobar/my/mod.jst', $originalContent);
		$this->assertEquals($originalContent, $filteredContent);
	}

}
