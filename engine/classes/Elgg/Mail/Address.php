<?php
namespace Elgg\Mail;

/**
 * TODO(ewinslow): Contribute something like this back to Zend project.
 *
 * @access private
 */
class Address {
	/**
	 * Parses strings like "Evan <evan@elgg.org>" into name/email objects.
	 *
	 * This is not very sophisticated and only used to provide a light BC effort.
	 *
	 * @param string $contact e.g. "Evan <evan@elgg.org>"
	 *
	 * @return \Zend\Mail\Address
	 */
	public static function fromString($contact) {
		$containsName = preg_match('/<(.*)>/', $contact, $matches) == 1;
		if ($containsName) {
			$name = trim(substr($contact, 0, strpos($contact, '<')));
			return new \Zend\Mail\Address($matches[1], $name);
		} else {
			return new \Zend\Mail\Address(trim($contact));
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
	 */
	public static function getFormattedEmailAddress($email, $name = null) {
		$mail = new \Zend\Mail\Address($email, $name);
		return $mail->toString();
	}
}
