<?php

namespace Elgg\Email;

use Elgg\UnitTestCase;
use Laminas\Mime\Mime;

class HtmlPartUnitTest extends UnitTestCase {
	public function up() {
		
	}

	public function down() {

	}
	
	public function testConstructor() {
		$body = '<p>foo &amp; bar test</p>';
		$part = new HtmlPart($body);
		
		$this->assertEquals($body, $part->getRawContent());
		$this->assertEquals('htmltext', $part->getId());
		$this->assertEquals('UTF-8', $part->getCharset());
		$this->assertEquals(Mime::TYPE_HTML, $part->getType());
		$this->assertEquals(Mime::ENCODING_BASE64, $part->getEncoding());
	}
}
