<?php

namespace Elgg;

class EmailServiceUnitTest extends UnitTestCase {
	
	protected ?EmailService $service = null;
	
	public function up() {
		$this->service = _elgg_services()->emails;
	}
	
	public function testFindImages() {
		$string = "This is a test to find the image URLs
			<img src='http://some.domain.ext/image.jpg' />
			And we add a non http link which shouldn't be found
			<img src='file://localhost/image.jpg' />
			and repeat the same url which only should be returned once
			<img src='http://some.domain.ext/image.jpg' />
			also make sure we check for https links
			<img src='https://secure.domain.ext/image.jpg' />
			make sure not to fetch hyperlinks
			<a href='https://hyper.domain.ext/'>some link</a>
		";
		
		$result = $this->invokeInaccessableMethod($this->service, 'findImages', $string);
		$this->assertCount(2, $result);
		$this->assertContains("'http://some.domain.ext/image.jpg'", $result);
		$this->assertContains("'https://secure.domain.ext/image.jpg'", $result);
		$this->assertNotContains("'file://localhost/image.jpg'", $result);
		$this->assertNotContains("'https://hyper.domain.ext/'", $result);
	}
}
