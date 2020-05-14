<?php

namespace Elgg\Email;

use Zend\Mail\Address as ZendAddress;
use Zend\Mail\Exception\InvalidArgumentException;
use Zend\Validator\EmailAddress as EmailAddressValidator;
use Zend\Validator\Hostname;

/**
 * Email address
 *
 * @since 3.0
 */
class Address extends ZendAddress {
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct($email, $name = null, $comment = null) {
		if (isset($name) && is_string($name)) {
			$name = html_entity_decode($name, ENT_QUOTES | ENT_XHTML, 'UTF-8');
		}
		
		parent::__construct($email, $name, $comment);
	}
	
	/**
	 * Set the email address
	 *
	 * @param string $email the new email address
	 *
	 * @return void
	 * @throws \Zend\Mail\Exception\InvalidArgumentException
	 * @since 3.0
	 */
	public function setEmail($email) {
		
		if (!is_string($email) || empty($email)) {
			throw new InvalidArgumentException('Email must be a valid email address');
		}
		
		if (preg_match("/[\r\n]/", $email)) {
			throw new InvalidArgumentException('CRLF injection detected');
		}
		
		$emailAddressValidator = new EmailAddressValidator(Hostname::ALLOW_DNS | Hostname::ALLOW_LOCAL);
		if (!$emailAddressValidator->isValid($email)) {
			$invalidMessages = $emailAddressValidator->getMessages();
			throw new InvalidArgumentException(array_shift($invalidMessages));
		}
		
		$this->email = $email;
	}
	
	/**
	 * Set the name
	 *
	 * @param string $name the new name
	 *
	 * @return void
	 * @throws \Zend\Mail\Exception\InvalidArgumentException
	 * @since 3.0
	 */
	public function setName($name) {
		
		if (!is_string($name)) {
			throw new InvalidArgumentException('Name must be a string');
		}
		
		if (preg_match("/[\r\n]/", $name)) {
			throw new InvalidArgumentException('CRLF injection detected');
		}
		
		$this->name = html_entity_decode($name, ENT_QUOTES | ENT_XHTML, 'UTF-8');
	}
	
	/**
	 * Clear the name from the email address
	 *
	 * @return void
	 * @since 3.0
	 */
	public function unsetName() {
		$this->name = null;
	}
	
	/**
	 * Parses strings like "Evan <evan@elgg.org>" into name/email objects.
	 *
	 * This is not very sophisticated and only used to provide a light BC effort.
	 *
	 * @param string $contact e.g. "Evan <evan@elgg.org>"
	 * @param string $ignored Ignored
	 *
	 * @return \Elgg\Email\Address
	 * @throws \Zend\Mail\Exception\InvalidArgumentException
	 * @since 3.0
	 */
	public static function fromString($contact, $ignored = null) {
		$containsName = preg_match('/<(.*)>/', $contact, $matches) == 1;
		if ($containsName) {
			$name = trim(elgg_substr($contact, 0, elgg_strpos($contact, '<')));
			return new self($matches[1], $name);
		} else {
			return new self(trim($contact));
		}
	}
	
	/**
	 * Format an email address and name into a formatted email address
	 *
	 * eg "Some name <someone@example.com>"
	 *
	 * @param string $email the email address
	 * @param string $name  the name
	 *
	 * @return string
	 * @throws \Zend\Mail\Exception\InvalidArgumentException
	 * @since 3.0
	 */
	public static function getFormattedEmailAddress($email, $name = null) {
		$mail = new self($email, $name);
		return $mail->toString();
	}
}
