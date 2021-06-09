<?php

namespace Elgg\Email;

use Elgg\Exceptions\InvalidArgumentException;

/**
 * @group EmailService
 * @group UnitTests
 */
class AddressUnitTest extends \Elgg\UnitTestCase {

	/**
	 * @var Address
	 */
	protected $address;
	
	public function up() {

		$this->address = new Address('example@elgg.org', 'Example');
	}

	public function down() {

	}
	
	public function testEmail() {
		
		$address = new Address('example@elgg.org');
		
		$this->assertEquals('<example@elgg.org>', $address->toString());
	}
	
	public function testEmailName() {
		
		$address = new Address('example@elgg.org', 'Example');
		
		$this->assertEquals('Example <example@elgg.org>', $address->toString());
	}
	
	public function testInvalidEmail() {
		$this->expectException(InvalidArgumentException::class);
		new Address('invalid_email');
	}
	
	public function testEmailInvalidName() {
		$this->expectException(InvalidArgumentException::class);
		new Address('example@elgg.org', []);
	}
	
	public function testChangeEmail() {
		
		$address = $this->address;
		
		$this->assertEquals('example@elgg.org', $address->getEmail());
		
		$address->setEmail('example2@elgg.org');
		
		$this->assertEquals('example2@elgg.org', $address->getEmail());
		$this->assertEquals('Example <example2@elgg.org>', $address->toString());
	}
	
	public function testChangeName() {
		
		$address = $this->address;
		
		$this->assertEquals('Example', $address->getName());
		
		$address->setName('Example 2');
		
		$this->assertEquals('Example 2', $address->getName());
		$this->assertEquals('Example 2 <example@elgg.org>', $address->toString());
	}
	
	public function testChangeInvalidEmail() {
		
		$address = $this->address;
		
		$this->assertEquals('example@elgg.org', $address->getEmail());
		
		$this->expectException(InvalidArgumentException::class);
		$address->setEmail('invalid_email');
	}
	
	public function testChangeInvalidName() {
		
		$address = $this->address;
		
		$this->assertEquals('Example', $address->getName());
		
		$this->expectException(InvalidArgumentException::class);
		$address->setName([]);
	}
	
	public function testUnsetName() {
		
		$address = $this->address;
		
		$this->assertEquals('Example', $address->getName());
		
		$address->unsetName();
		
		$this->assertNull($address->getName());
	}
	
	public function testInvalidUnsetName() {
		
		$address = $this->address;
		
		$this->assertEquals('Example', $address->getName());
		
		$this->expectException(InvalidArgumentException::class);
		$address->setName(null);
	}
	
	public function testFromStringEmail() {
		
		$address = Address::fromString('example@elgg.org');
		
		$this->assertEquals('example@elgg.org', $address->getEmail());
		$this->assertNull($address->getName());
	}
	
	public function testFromStringEmailName() {
		
		$address = Address::fromString('Example <example@elgg.org>');
		
		$this->assertEquals('example@elgg.org', $address->getEmail());
		$this->assertEquals('Example', $address->getName());
	}
	
	public function testFromStringInvalidEmail() {
		$this->expectException(InvalidArgumentException::class);
		Address::fromString('invalid_email');
	}
	
	public function testFromEntity() {
		$entity = $this->createUser();
		$address = Address::fromEntity($entity);
		
		$this->assertEquals($entity->email, $address->getEmail());
		$this->assertEquals($entity->getDisplayName(), $address->getName());
		$this->assertEquals($entity, $address->getEntity());
	}
	
	public function testEntityGetterAndSetter() {
		$entity = $this->createUser();
		$address = Address::fromString('example@elgg.org');
		
		$this->assertNull($address->getEntity());
		
		$address->setEntity($entity);
		$this->assertEquals($entity, $address->getEntity());
	}
	
	public function testGetFormattedEmailAddressEmail() {
		
		$address_string = Address::getFormattedEmailAddress('example@elgg.org');
		
		$this->assertEquals('<example@elgg.org>', $address_string);
	}
	
	public function testGetFormattedEmailAddressEmailName() {
		
		$address_string = Address::getFormattedEmailAddress('example@elgg.org', 'Example');
		
		$this->assertEquals('Example <example@elgg.org>', $address_string);
	}
	
	public function testGetFormattedEmailAddressInvalidEmail() {
		$this->expectException(InvalidArgumentException::class);
		Address::getFormattedEmailAddress('invalid_email');
	}
	
	public function testGetFormattedEmailAddressEmailInvalidName() {
		$this->expectException(InvalidArgumentException::class);
		Address::getFormattedEmailAddress('example@elgg.org', []);
	}
	
	/**
	 * @dataProvider nameHtmlDecodingProvider
	 */
	public function testConstructorNameHtmlDecoding($input_name, $expected_output) {
		$address = new Address('john.doe@example.com', $input_name);
		
		$this->assertEquals($expected_output, $address->getName());
	}
	
	/**
	 * @dataProvider nameHtmlDecodingProvider
	 */
	public function testSetNameHtmlDecoding($input_name, $expected_output) {
		$address = new Address('john.doe@example.com');
		$address->setName($input_name);
		
		$this->assertEquals($expected_output, $address->getName());
	}
	
	public function nameHtmlDecodingProvider() {
		return [
			['Doe, John', 'Doe, John'],
			['John Doe', 'John Doe'],
			['John & Jane Doe', 'John & Jane Doe'],
			['John &amp; Jane Doe', 'John & Jane Doe'],
			['J&ocirc;hn &amp; Jane Doe', 'Jôhn & Jane Doe'],
			['John &quot;Doe&quot;', 'John "Doe"'],
			['John O&apos;Doe', 'John O\'Doe'],
		];
	}
}
