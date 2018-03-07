<?php

namespace Elgg;

use ElggEntity;
use ElggUser;
use InvalidParameterException;
use Elgg\Email\Address;
use Elgg\Email\Attachment;
use Zend\Mime\Part;

/**
 * Email message
 */
final class Email {

	/**
	 * @var \Elgg\Email\Address
	 */
	protected $from;

	/**
	 * @var \Elgg\Email\Address
	 */
	protected $to;

	/**
	 * @var string
	 */
	protected $subject;

	/**
	 * @var string
	 */
	protected $body;

	/**
	 * @var array
	 */
	protected $params = [];

	/**
	 * @var array
	 */
	protected $headers = [];
	
	/**
	 * @var Part[]
	 */
	protected $attachments = [];

	/**
	 * Create an email instance form an array of options
	 *
	 * @param array $options Options
	 *                       'from' - ElggEntity, or email string, or \Elgg\Email\Address
	 *                       'to' - ElggEntity, or email string, or \Elgg\Email\Address
	 *                       'subject' - subject string
	 *                       'body' - body string
	 *                       'params' - additional parameters
	 *                       'headers' - HTTP/IMF headers
	 * @return \Elgg\Email
	 */
	public static function factory(array $options = []) {
		$from = elgg_extract('from', $options);
		$to = elgg_extract('to', $options);
		$subject = elgg_extract('subject', $options);
		$body = elgg_extract('body', $options);
		$params = elgg_extract('params', $options, []);
		$headers = elgg_extract('headers', $options, []);

		$email = new self();
		$email->setFrom(self::prepareFrom($from));
		$email->setTo(self::prepareTo($to));
		$email->setSubject($subject);
		$email->setBody($body);
		$email->setParams($params);
		$email->setHeaders($headers);
		
		if (isset($params['attachments']) && is_array($params['attachments'])) {
			foreach ($params['attachments'] as $attachment) {
				$email->addAttachment($attachment);
			}
		}
		
		return $email;
	}

	/**
	 * Sets sender address
	 *
	 * @param \Elgg\Email\Address $from Sender address
	 * @return self
	 */
	public function setFrom(Address $from) {
		$this->from = $from;
		return $this;
	}

	/**
	 * Returns sender address
	 * @return \Elgg\Email\Address
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * Sets recipient address
	 *
	 * @param \Elgg\Email\Address $to Recipient address
	 * @return self
	 */
	public function setTo(Address $to) {
		$this->to = $to;
		return $this;
	}

	/**
	 * Returns recipient address
	 * @return \Elgg\Email\Address
	 */
	public function getTo() {
		return $this->to;
	}

	/**
	 * Sets email subject
	 *
	 * @param string $subject Subject
	 * @return self
	 */
	public function setSubject($subject = '') {
		$this->subject = $subject;
		return $this;
	}

	/**
	 * Returns the subject
	 * @return string
	 */
	public function getSubject() {
		return $this->subject;
	}

	/**
	 * Sets the email message body
	 *
	 * @param string $body Body
	 * @return self
	 */
	public function setBody($body = '') {
		$this->body = $body;
		return $this;
	}

	/**
	 * Returns email body
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Sets additional params
	 *
	 * @param array $params Params
	 * @return self
	 */
	public function setParams(array $params = []) {
		$this->params = $params;
		return $this;
	}

	/**
	 * Returns additional params
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}

	/**
	 * Adds/replaces an HTTP/IMF header
	 *
	 * @param string $name  Header name
	 * @param mixed  $value Header value
	 * @return self
	 */
	public function addHeader($name, $value) {
		$this->headers[$name] = $value;
		return $this;
	}

	/**
	 * Replaces header bag
	 *
	 * @param array $headers Headers
	 * @return self
	 */
	public function setHeaders(array $headers = []) {
		$this->headers = $headers;
		return $this;
	}

	/**
	 * Returns headers
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}
	
	/**
	 * Add an attachment
	 *
	 * @param mixed $attachment \Zend\Mime\Part or \Elgg\Email\Attachment or \ElggFile or an array
	 *
	 * @see \Elgg\Email\Attachment::factory()
	 *
	 * @return self
	 */
	public function addAttachment($attachment) {
		
		if ($attachment instanceof Part) {
			$this->attachments[] = $attachment;
			return $this;
		}
		
		if ($attachment instanceof \ElggFile) {
			$this->attachments[] = Attachment::fromElggFile($attachment);
			return $this;
		}
		
		$attachment = Attachment::factory($attachment);
		if (!empty($attachment)) {
			$this->attachments[] = $attachment;
		}
		
		return $this;
	}
	
	/**
	 * Get all attachments
	 *
	 * @return \Zend\Mime\Part[]
	 */
	public function getAttachments() {
		return $this->attachments;
	}

	/**
	 * Converts mixed input to an instance of Zend addres
	 *
	 * @param mixed $from From input
	 * @return Address
	 * @throws InvalidParameterException
	 */
	protected static function prepareFrom($from) {
		if (empty($from)) {
			// get the site email address
			$site = elgg_get_site_entity();
			$from = new Address($site->getEmailAddress(), $site->getDisplayName());
		} else if ($from instanceof ElggEntity) {
			// If there's an email address, use it - but only if it's not from a user.
			if (!$from instanceof ElggUser && $from->email) {
				$from = new Address($from->email, $from->getDisplayName());
			} else {
				// get the site email address
				$site = elgg_get_site_entity();
				$from = new Address($site->getEmailAddress(), $site->getDisplayName());
			}
		} elseif (is_string($from)) {
			$from = Address::fromString($from);
		}
		
		if (!$from instanceof Address) {
			throw new InvalidParameterException("From address is not in a valid format");
		}

		return $from;
	}

	/**
	 * Converts mixed input to an instance of Zend addres
	 *
	 * @param mixed $to To input
	 * @return Address
	 * @throws InvalidParameterException
	 */
	protected static function prepareTo($to) {
		if ($to instanceof ElggEntity) {
			$to = new Address($to->email, $to->getDisplayName());
		} elseif (is_string($to)) {
			$to = Address::fromString($to);
		}

		if (!$to instanceof Address) {
			throw new InvalidParameterException("To address is not in a valid format");
		}

		return $to;
	}
}
