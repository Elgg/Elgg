<?php

/**
 * @group XML
 * @group Plugins
 */
class ElggXMLElementUnitTest extends \Elgg\UnitTestCase {

	private $xml;

	public function up() {
		$this->xml = '
			<book id="bk101">
			  <author>Gambardella, Matthew</author>
			  <title>XML Developer\'s Guide</title>
			  <genre>Computer</genre>
			  <price>44.95</price>
			  <publish_date>2000-10-01</publish_date>
			  <description>An in-depth look at creating applications with XML.</description>
			</book>
   		';
	}

	public function down() {

	}

	public function testConstructor() {

		$xml = new ElggXMLElement($this->xml);

		$this->assertTrue(isset($xml->name));
		$this->assertTrue(isset($xml->attributes));
		$this->assertTrue(isset($xml->content));
		$this->assertTrue(isset($xml->children));

		$this->assertEquals('book', $xml->name);
		$this->assertEquals(['id' => 'bk101'], $xml->attributes);
		$this->assertEquals('', preg_replace('/\r|\r\n|\t|\s|\n/m', '', $xml->content));
		$this->assertEquals(6, count($xml->children));

		$this->assertEquals($xml->name, $xml->getName());
		$this->assertEquals($xml->attributes, $xml->getAttributes());
		$this->assertEquals($xml->content, $xml->getContent());
		$this->assertEquals($xml->children, $xml->getChildren());

		$this->assertFalse(isset($xml->foo));
		$this->assertNull($xml->foo);
	}

	public function testWrapsChildren() {
		$xml = new ElggXMLElement($this->xml);

		$children = $xml->getChildren();

		$child = array_shift($children);

		$this->assertInstanceOf(ElggXMLElement::class, $child);
		$this->assertEquals('author', $child->getName());
		$this->assertEquals('Gambardella, Matthew', $child->getContent());
		$this->assertEmpty($child->getAttributes());
		$this->assertEmpty($child->getChildren());
	}

}