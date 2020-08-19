<?php

namespace Elgg\Helpers\Cli;

/**
 * @see \Elgg\Cli\SimpleTestCommandTest
 */
class CliSimpletest extends \ElggCoreUnitTest {
	
	public function up() {
		
	}
	
	public function down() {
		
	}
	
	public function testMe() {
		system_message(__METHOD__);
	}
}
