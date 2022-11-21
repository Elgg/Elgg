<?php

use Elgg\Exceptions\RuntimeException as ElggRuntimeException;

/**
 * @group Functions
 */
class ElggCallUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @dataProvider flagsDataProvider
	 */
	public function testCanCallWithFlags($access_before, $disabled_before, $ignore_access, $show_disabled) {

		$ia = elgg()->session_manager->setIgnoreAccess($access_before);
		$ha = elgg()->session_manager->getDisabledEntityVisibility();
		elgg()->session_manager->setDisabledEntityVisibility($disabled_before);
		
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

		$exception_function = function() use ($ignore_access, $show_disabled) {
			$this->assertEquals($ignore_access, elgg_get_ignore_access());
			$this->assertEquals($show_disabled, elgg()->session_manager->getDisabledEntityVisibility());

			throw new ElggRuntimeException();
		};

		$exception_thrown = false;
		try {
			elgg_call($flags, $exception_function);
		} catch (ElggRuntimeException $ex) {
			$exception_thrown = true;
		}

		$this->assertTrue($exception_thrown);

		$error_function = function() use ($ignore_access, $show_disabled) {
			$this->assertEquals($ignore_access, elgg_get_ignore_access());
			$this->assertEquals($show_disabled, elgg()->session_manager->getDisabledEntityVisibility());

			throw new ParseError();
		};

		$error_thrown = false;
		try {
			elgg_call($flags, $error_function);
		} catch (\ParseError $err) {
			$error_thrown = true;
		}

		$this->assertTrue($error_thrown);

		$this->assertEquals($access_before, elgg_get_ignore_access());
		$this->assertEquals($disabled_before, elgg()->session_manager->getDisabledEntityVisibility());

		elgg()->session_manager->setIgnoreAccess($ia);
		elgg()->session_manager->setDisabledEntityVisibility($ha);
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
