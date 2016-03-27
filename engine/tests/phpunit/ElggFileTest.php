<?php

class ElggFileTest extends \PHPUnit_Framework_TestCase {

	/**
	 * @var \ElggFile
	 */
	protected $file;

	protected function setUp() {

		$session = \ElggSession::getMock();
		_elgg_services()->setValue('session', $session);
		_elgg_services()->session->start();

		$file = new \ElggFile();
		$file->owner_guid = 1;
		$file->setFilename("foobar.txt");

		$this->file = $file;
	}

	/**
	 * @group FileService
	 */
	public function testCanSetModifiedTime() {
		$time = 1459067396;
		$this->file->setModifiedTime($time);
		$this->assertEquals($time, $this->file->getModifiedTime());
	}
}