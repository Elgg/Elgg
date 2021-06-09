<?php

namespace Elgg\Email;

use Elgg\Exceptions\InvalidArgumentException;
use Laminas\Mail\Address as ZendAddress;
use Laminas\Validator\EmailAddress as EmailAddressValidator;
use Laminas\Validator\Hostname;

/**
 * Email address
 *
 * @since 3.0
 */
class Address extends ZendAddress {
	
	/**
	 * @var \ElggEntity The related entity
	 */
	protected $entity = null;
	
	/**
	 * {@inheritdoc}
	 */
	public function __construct($email, $name = null, $comment = null) {
		if (isset($name) && is_string($name)) {
			$name = html_entity_decode($name, ENT_QUOTES | ENT_XHTML, 'UTF-8');
		}
		
		try {
			parent::__construct($email, $name, $comment);
		} catch (\Laminas\Mail\Exception\InvalidArgumentException $e) {
			throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
		}
	}
	
	/**
	 * Set the email address
	 *
	 * @param string $email the new email address
	 *
	 * @return void
	 * @throws \Elgg\Exceptions\InvalidArgumentException
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
	 * @throws \Elgg\Exceptions\InvalidArgumentException
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
	 * Store the Entity related to this Address
	 *
	 * @param \ElggEntity $entity
	 *
	 * @return void
	 *
	 * @since 4.0
	 */
	public function setEntity(\ElggEntity $entity): void {
		$this->entity = $entity;
	}
	
	/**
	 * Returns the saved entity
	 *
	 * @return \ElggEntity|null
	 *
	 * @since 4.0
	 */
	public function getEntity(): ?\ElggEntity {
		return $this->entity;
	}
	
	/**
	 * Parses strings like "Evan <evan@elgg.org>" into name/email objects.
	 *
	 * This is not very sophisticated and only used to provide a light BC effort.
	 *
	 * @param string $contact e.g. "Evan <evan@elgg.org>"
	 * @param string $ignored Ignored (required for Laminas\Mail\Address)
	 *
	 * @return \Elgg\Email\Address
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 * @since 3.0
	 */
	public static function fromString($contact, $ignored = null): Address {
		$containsName = preg_match('/<(.*)>/', $contact, $matches) == 1;
		if ($containsName) {
			$name = trim(elgg_substr($contact, 0, elgg_strpos($contact, '<')));
			return new static($matches[1], $name);
		} else {
			return new static(trim($contact));
		}
	}
	
	/**
	 * Create an Address based on a Entity
	 *
	 * @param \ElggEntity $entity the entity to create the address for
	 *
	 * @return \Elgg\Email\Address
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 * @since 4.0
	 */
	public static function fromEntity(\ElggEntity $entity): Address {
		$address = new static($entity->email, $entity->getDisplayName());
		$address->setEntity($entity);
		return $address;
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
	 * @throws \Elgg\Exceptions\InvalidArgumentException
	 * @since 3.0
	 */
	public static function getFormattedEmailAddress($email, $name = null) {
		$mail = new static($email, $name);
		return $mail->toString();
	}
}
