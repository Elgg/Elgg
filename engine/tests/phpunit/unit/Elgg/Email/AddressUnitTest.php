<?php

namespace Elgg\Email;

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
	
	/**
	 * @expectedException Zend\Mail\Exception\InvalidArgumentException
	 */
	public function testInvalidEmail() {
		
		$address = new Address('invalid_email');
	}
	
	/**
	 * @expectedException Zend\Mail\Exception\InvalidArgumentException
	 */
	public function testEmailInvalidName() {
		
		$address = new Address('example@elgg.org', []);
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
	
	/**
	 * @expectedException Zend\Mail\Exception\InvalidArgumentException
	 */
	public function testChangeInvalidEmail() {
		
		$address = $this->address;
		
		$this->assertEquals('example@elgg.org', $address->getEmail());
		
		$address->setEmail('invalid_email');
	}
	
	/**
	 * @expectedException Zend\Mail\Exception\InvalidArgumentException
	 */
	public function testChangeInvalidName() {
		
		$address = $this->address;
		
		$this->assertEquals('Example', $address->getName());
		
		$address->setName([]);
	}
	
	public function testUnsetName() {
		
		$address = $this->address;
		
		$this->assertEquals('Example', $address->getName());
		
		$address->unsetName();
		
		$this->assertNull($address->getName());
	}
	
	/**
	 * @expectedException Zend\Mail\Exception\InvalidArgumentException
	 */
	public function testInvalidUnsetName() {
		
		$address = $this->address;
		
		$this->assertEquals('Example', $address->getName());
		
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
	
	/**
	 * @expectedException Zend\Mail\Exception\InvalidArgumentException
	 */
	public function testFromStringInvalidEmail() {
		
		$address = Address::fromString('invalid_email');
	}
	
	public function testGetFormattedEmailAddressEmail() {
		
		$address_string = Address::getFormattedEmailAddress('example@elgg.org');
		
		$this->assertEquals('<example@elgg.org>', $address_string);
	}
	
	public function testGetFormattedEmailAddressEmailName() {
		
		$address_string = Address::getFormattedEmailAddress('example@elgg.org', 'Example');
		
		$this->assertEquals('Example <example@elgg.org>', $address_string);
	}
	
	/**
	 * @expectedException Zend\Mail\Exception\InvalidArgumentException
	 */
	public function testGetFormattedEmailAddressInvalidEmail() {
		
		$address_string = Address::getFormattedEmailAddress('invalid_email');
	}
	
	/**
	 * @expectedException Zend\Mail\Exception\InvalidArgumentException
	 */
	public function testGetFormattedEmailAddressEmailInvalidName() {
		
		$address_string = Address::getFormattedEmailAddress('example@elgg.org', []);
	}
}
