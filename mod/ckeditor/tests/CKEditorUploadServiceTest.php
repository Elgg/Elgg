<?php

class CKEditorUploadServiceTest extends PHPUnit_Framework_TestCase {

	function testRetrieveWithNonExistentFile() {
		$service = new CKEditorUploadService('/tmp/', 1);
		$this->assertEquals('', $service->retrieve('does_not_exist'));
	}

}
