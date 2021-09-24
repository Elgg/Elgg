<?php

namespace Elgg;

use Elgg\Email\Address;
use Elgg\Email\Attachment;
use Elgg\Exceptions\InvalidParameterException;
use Laminas\Mime\Part;

/**
 * Email message
 */
final class Email {

	/**
	 * @var \Elgg\Email\Address
	 */
	protected $from;

	/**
	 * @var \Elgg\Email\Address[]
	 */
	protected $to = [];

	/**
	 * @var \Elgg\Email\Address[]
	 */
	protected $cc = [];

	/**
	 * @var \Elgg\Email\Address[]
	 */
	protected $bcc = [];

	/**
	 * @var mixed
	 */
	protected $sender;

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
	 *                       'to' - ElggEntity, or email string, or \Elgg\Email\Address, or an array of one of these types
	 *                       'cc' - ElggEntity, or email string, or \Elgg\Email\Address, or an array of one of these types
	 *                       'bcc' - ElggEntity, or email string, or \Elgg\Email\Address, or an array of one of these types
	 *                       'subject' - subject string
	 *                       'body' - body string
	 *                       'params' - additional parameters
	 *                       'headers' - HTTP/IMF headers
	 *
	 * @return \Elgg\Email
	 */
	public static function factory(array $options = []) {
		$from = elgg_extract('from', $options);
		$to = elgg_extract('to', $options);
		$cc = elgg_extract('cc', $options);
		$bcc = elgg_extract('bcc', $options);
		$subject = elgg_extract('subject', $options);
		$body = elgg_extract('body', $options);
		$params = elgg_extract('params', $options, []);
		$headers = elgg_extract('headers', $options, []);

		$email = new self();
		$email->setSender($from);
		$email->setFrom(self::prepareFrom($from));
		$email->setTo($to);
		$email->setCc($cc);
		$email->setBcc($bcc);
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
	 * Sets email sender
	 *
	 * @param mixed $sender Original sender of the Email
	 *
	 * @return self
	 */
	public function setSender($sender) {
		$this->sender = $sender;
		return $this;
	}

	/**
	 * Returns sender
	 *
	 * @return mixed
	 */
	public function getSender() {
		return $this->sender;
	}

	/**
	 * Sets sender address
	 *
	 * @param \Elgg\Email\Address $from Sender address
	 *
	 * @return self
	 */
	public function setFrom(Address $from) {
		$this->from = $from;
		return $this;
	}

	/**
	 * Returns sender address
	 *
	 * @return \Elgg\Email\Address
	 */
	public function getFrom() {
		return $this->from;
	}

	/**
	 * Sets recipient address
	 *
	 * @param mixed $recipient ElggEntity, or email string, or \Elgg\Email\Address, or an array of one of these types
	 *
	 * @return self
	 */
	public function setTo($recipient): self {
		$this->to = $this->prepareRecipients($recipient);
		return $this;
	}

	/**
	 * Returns recipient address
	 *
	 * @return \Elgg\Email\Address[]
	 */
	public function getTo(): array {
		return $this->to;
	}

	/**
	 * Sets recipient address in cc
	 *
	 * @param mixed $recipient ElggEntity, or email string, or \Elgg\Email\Address, or an array of one of these types
	 *
	 * @return self
	 */
	public function setCc($recipient): self {
		$this->cc = $this->prepareRecipients($recipient);
		return $this;
	}

	/**
	 * Returns recipient address from cc
	 *
	 * @return \Elgg\Email\Address[]
	 */
	public function getCc(): array {
		return $this->cc;
	}
	
	/**
	 * Sets recipient address in bcc
	 *
	 * @param mixed $recipient ElggEntity, or email string, or \Elgg\Email\Address, or an array of one of these types
	 *
	 * @return self
	 */
	public function setBcc($recipient): self {
		$this->bcc = $this->prepareRecipients($recipient);
		return $this;
	}

	/**
	 * Returns recipient address from bcc
	 *
	 * @return \Elgg\Email\Address[]
	 */
	public function getBcc(): array {
		return $this->bcc;
	}

	/**
	 * Sets email subject
	 *
	 * @param string $subject Subject
	 *
	 * @return self
	 */
	public function setSubject($subject = '') {
		$this->subject = $subject;
		return $this;
	}

	/**
	 * Returns the subject
	 *
	 * It is possible to limit the length of the subject string. This is sometimes needed for certain mail servers / clients.
	 *
	 * @return string
	 */
	public function getSubject() {
		return elgg_substr($this->subject, 0, (int) _elgg_services()->config->email_subject_limit);
	}

	/**
	 * Sets the email message body
	 *
	 * @param string $body Body
	 *
	 * @return self
	 */
	public function setBody($body = '') {
		$this->body = $body;
		return $this;
	}

	/**
	 * Returns email body
	 *
	 * @return string
	 */
	public function getBody() {
		return $this->body;
	}

	/**
	 * Sets additional params
	 *
	 * @param array $params Params
	 *
	 * @return self
	 */
	public function setParams(array $params = []) {
		$this->params = $params;
		return $this;
	}

	/**
	 * Returns additional params
	 *
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
	 *
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
	 *
	 * @return self
	 */
	public function setHeaders(array $headers = []) {
		$this->headers = $headers;
		return $this;
	}

	/**
	 * Returns headers
	 *
	 * @return array
	 */
	public function getHeaders() {
		return $this->headers;
	}
	
	/**
	 * Add an attachment
	 *
	 * @param mixed $attachment \Laminas\Mime\Part or \Elgg\Email\Attachment or \ElggFile or an array
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
	 * @return \Laminas\Mime\Part[]
	 */
	public function getAttachments() {
		return $this->attachments;
	}

	/**
	 * Converts mixed input to an instance of Laminas addres
	 *
	 * @param mixed $from From input
	 *
	 * @return Address
	 *
	 * @throws InvalidParameterException
	 */
	protected static function prepareFrom($from) {
		if (empty($from)) {
			// get the site email address
			$site = elgg_get_site_entity();
			$from = new Address($site->getEmailAddress(), $site->getDisplayName());
		} elseif ($from instanceof \ElggSite) {
			// use site email address
			$from = new Address($from->getEmailAddress(), $from->getDisplayName());
		} elseif ($from instanceof \ElggEntity) {
			// If there's an email address, use it - but only if it's not from a user.
			if (!$from instanceof \ElggUser && $from->email) {
				$from = Address::fromEntity($from);
			} else {
				// get the site email address
				$site = elgg_get_site_entity();
				$from_display = elgg_echo('notification:method:email:from', [$from->getDisplayName(), $site->getDisplayName()]);
				$from = new Address($site->getEmailAddress(), $from_display);
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
	 * Converts mixed input to an array of Laminas address instances
	 *
	 * @param mixed $recipients recipients input
	 *
	 * @return Address[]
	 *
	 * @throws InvalidParameterException
	 */
	protected function prepareRecipients($recipients) {
		if (empty($recipients)) {
			return [];
		}
		
		if (!is_array($recipients)) {
			$recipients = [$recipients];
		}
		
		$result = [];
		foreach ($recipients as $recipient) {
			if ($recipient instanceof Address) {
				$result[] = $recipient;
				continue;
			}
			
			if ($recipient instanceof \ElggEntity) {
				$recipient = Address::fromEntity($recipient);
			} elseif (is_string($recipient)) {
				$recipient = Address::fromString($recipient);
			}
	
			if (!$recipient instanceof Address) {
				throw new InvalidParameterException("Recipient address is not in a valid format");
			}
			
			$result[] = $recipient;
		}
		
		return $result;
	}
}
