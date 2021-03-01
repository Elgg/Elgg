<?php

namespace Elgg\Email;

use Elgg\UnitTestCase;
use Laminas\Mime\Mime;

class PlainTextPartUnitTest extends UnitTestCase {
	public function up() {
		
	}

	public function down() {

	}
	
	public function testConstructor() {
		$body = '<p>foo &amp; bar test</p>';
		$part = new PlainTextPart($body);
		
		$this->assertEquals('foo & bar test', $part->getRawContent());
		$this->assertEquals('plaintext', $part->getId());
		$this->assertEquals('UTF-8', $part->getCharset());
		$this->assertEquals(Mime::TYPE_TEXT, $part->getType());
	}
}
