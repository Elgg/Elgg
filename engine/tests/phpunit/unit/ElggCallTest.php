<?php

/**
 * @group Functions
 */
class ElggCallTest extends \Elgg\UnitTestCase {

	public function up() {

	}

	public function down() {

	}

	/**
	 * @dataProvider flagsDataProvider
	 */
	public function testCanCallWithFlags($access_before, $disabled_before, $ignore_access, $show_disabled) {

		$ia = elgg_set_ignore_access($access_before);
		$ha = access_get_show_hidden_status();
		access_show_hidden_entities($disabled_before);

		$flags = null;
		if ($ignore_access === true) {
			$flags |= ELGG_IGNORE_ACCESS;
		} else if ($ignore_access === false) {
			$flags |= ELGG_ENFORCE_ACCESS;
		} else {
			$ignore_access = $ia;
		}

		if ($show_disabled === true) {
			$flags |= ELGG_SHOW_DISABLED_ENTITIES;
		} else if ($show_disabled === false) {
			$flags |= ELGG_HIDE_DISABLED_ENTITIES;
		} else {
			$show_disabled = $ha;
		}

		$calls = 0;
		$function = function() use ($ignore_access, $show_disabled, &$calls) {
			$this->assertEquals($ignore_access, elgg_get_ignore_access());
			$this->assertEquals($show_disabled, access_get_show_hidden_status());

			throw new RuntimeException();
		};

		$exception_thrown = false;
		try {
			elgg_call($flags, $function);
		} catch (\RuntimeException $ex) {
			$exception_thrown = true;
		}

		$this->assertTrue($exception_thrown);

		$this->assertEquals($access_before, elgg_get_ignore_access());
		$this->assertEquals($disabled_before, access_get_show_hidden_status());

		elgg_set_ignore_access($ia);
		access_show_hidden_entities($ha);
	}

	public function flagsDataProvider() {
		return [
			[false, false, false, false],
			[false, false, false, true],
			[false, false, true, false],
			[false, false, true, true],
			[true, true, false, false],
			[true, true, false, true],
			[true, true, true, false],
			[true, true, true, true],
			[false, false, null, true],
			[false, false, true, null],
		];
	}

	public function testCanCallInstanceMethod() {

		$object = $this->createObject();

		$result = elgg_call(ELGG_IGNORE_ACCESS, function() use ($object) {
			return $object->delete();
		});

		$this->assertTrue($result);
	}

}