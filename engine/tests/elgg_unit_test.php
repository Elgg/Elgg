<?php

/**
 * Elgg Core Unit Tester
 *
 * This class is to be extended by all Elgg unit tests. As such, any method here
 * will be available to the tests.
 */
abstract class ElggCoreUnitTest extends UnitTestCase
{
	/**
	 * Class constructor.
	 *
	 * A simple wrapper to call the parent constructor.
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Class destructor.
	 *
	 * The parent does not provide a destructor, so including an explicit one here.
	 */
	public function __destruct()
	{
	}
}
