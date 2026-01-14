<?php

namespace Elgg;

use Elgg\Email\Attachment;
use Elgg\Exceptions\InvalidArgumentException;
use Psr\Log\LogLevel;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Part\DataPart;

/**
 * Email message
 */
final class Email {

	protected ?Address $from = null;

	/**
	 * @var Address[]
	 */
	protected array $to = [];

	/**
	 * @var Address[]
	 */
	protected array $cc = [];

	/**
	 * @var Address[]
	 */
	protected array $bcc = [];

	protected ?Address $sender = null;

	protected string $subject = '';

	protected ?string $body = null;

	protected array $params = [];

	protected array $headers = [];
	
	/**
	 * @var DataPart[]
	 */
	protected array $attachments = [];

	/**
	 * Create an email instance form an array of options
	 *
	 * @param array $options Options
	 *                       'from' - ElggEntity, or email string, or \Symfony\Component\Mime\Address
	 *                       'to' - ElggEntity, or email string, or \Symfony\Component\Mime\Address, or an array of one of these types
	 *                       'cc' - ElggEntity, or email string, or \Symfony\Component\Mime\Address, or an array of one of these types
	 *                       'bcc' - ElggEntity, or email string, or \Symfony\Component\Mime\Address, or an array of one of these types
	 *                       'subject' - subject string
	 *                       'body' - body string
	 *                       'params' - additional parameters
	 *                       'headers' - HTTP/IMF headers
	 *
	 * @return Email
	 */
	public static function factory(array $options = []): Email {
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
	public function setSender(mixed $sender): self {
		$prepared_sender = $this->prepareRecipients($sender);
		$this->sender = !empty($prepared_sender) ? $prepared_sender[0] : null;
		return $this;
	}

	/**
	 * Returns sender
	 *
	 * @return Address
	 */
	public function getSender(): Address {
		if (isset($this->sender)) {
			return $this->sender;
		}
		
		$site = elgg_get_site_entity();
		return new Address($site->getEmailAddress(), $site->getDisplayName());
	}

	/**
	 * Sets sender address
	 *
	 * @param Address $from Sender address
	 *
	 * @return self
	 */
	public function setFrom(Address $from): self {
		$this->from = $from;
		return $this;
	}

	/**
	 * Returns sender address
	 *
	 * @return Address|null
	 */
	public function getFrom(): ?Address {
		return $this->from;
	}

	/**
	 * Sets recipient address
	 *
	 * @param mixed $recipient \ElggEntity, or email string, or \Symfony\Component\Mime\Address, or an array of one of these types
	 *
	 * @return self
	 */
	public function setTo(mixed $recipient): self {
		$this->to = $this->prepareRecipients($recipient);
		return $this;
	}

	/**
	 * Returns recipient address
	 *
	 * @return Address[]
	 */
	public function getTo(): array {
		return $this->to;
	}

	/**
	 * Sets recipient address in cc
	 *
	 * @param mixed $recipient \ElggEntity, or email string, or \Symfony\Component\Mime\Address, or an array of one of these types
	 *
	 * @return self
	 */
	public function setCc(mixed $recipient): self {
		$this->cc = $this->prepareRecipients($recipient);
		return $this;
	}

	/**
	 * Returns recipient address from cc
	 *
	 * @return Address[]
	 */
	public function getCc(): array {
		return $this->cc;
	}
	
	/**
	 * Sets recipient address in bcc
	 *
	 * @param mixed $recipient \ElggEntity, or email string, or \Symfony\Component\Mime\Address, or an array of one of these types
	 *
	 * @return self
	 */
	public function setBcc(mixed $recipient): self {
		$this->bcc = $this->prepareRecipients($recipient);
		return $this;
	}

	/**
	 * Returns recipient address from bcc
	 *
	 * @return Address[]
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
	public function setSubject(string $subject = ''): self {
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
	public function getSubject(): string {
		return elgg_substr($this->subject, 0, (int) _elgg_services()->config->email_subject_limit);
	}

	/**
	 * Sets the email message body
	 *
	 * @param string $body Body
	 *
	 * @return self
	 */
	public function setBody(string $body = ''): self {
		$this->body = $body;
		return $this;
	}

	/**
	 * Returns email body
	 *
	 * @return string|null
	 */
	public function getBody(): ?string {
		return $this->body;
	}

	/**
	 * Sets additional params
	 *
	 * @param array $params Params
	 *
	 * @return self
	 */
	public function setParams(array $params = []): self {
		$this->params = $params;
		return $this;
	}

	/**
	 * Returns additional params
	 *
	 * @return array
	 */
	public function getParams(): array {
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
	public function addHeader(string $name, mixed $value): self {
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
	public function setHeaders(array $headers = []): self {
		$this->headers = $headers;
		return $this;
	}

	/**
	 * Returns headers
	 *
	 * @return array
	 */
	public function getHeaders(): array {
		return $this->headers;
	}
	
	/**
	 * Add an attachment
	 *
	 * @param mixed $attachment \Symfony\Component\Mime\Part\DataPart or \Elgg\Email\Attachment or \ElggFile or an array
	 *
	 * @see \Elgg\Email\Attachment::factory()
	 *
	 * @return self
	 */
	public function addAttachment(mixed $attachment): self {
		if ($attachment instanceof DataPart) {
			$this->attachments[] = $attachment;
			return $this;
		}
		
		try {
			$this->attachments[] = Attachment::factory($attachment);
		} catch (InvalidArgumentException $e) {
			elgg_log($e->getMessage(), LogLevel::ERROR);
		}
		
		return $this;
	}
	
	/**
	 * Get all attachments
	 *
	 * @return DataPart[]
	 */
	public function getAttachments(): array {
		return $this->attachments;
	}
	
	/**
	 * Create a Message-ID header string for the given entity
	 *
	 * @param \ElggEntity $entity        The entity to generate the header for
	 * @param bool        $add_microtime Add a microtime to the header (used for non create events)
	 *
	 * @return string
	 * @since 4.2
	 */
	public function createEntityMessageID(\ElggEntity $entity, bool $add_microtime = false): string {
		$microtime = '';
		if ($add_microtime) {
			$microtime = '.' . microtime(true);
		}
		
		$hostname = parse_url(elgg_get_site_url(), PHP_URL_HOST);
		$urlPath = parse_url(elgg_get_site_url(), PHP_URL_PATH);
		
		return "{$urlPath}.entity.{$entity->guid}{$microtime}@{$hostname}";
	}

	/**
	 * Converts mixed input to an instance of \Symfony\Component\Mime\Address
	 *
	 * @param mixed $from From input
	 *
	 * @return Address
	 * @throws InvalidArgumentException
	 */
	protected static function prepareFrom(mixed $from): Address {
		if (empty($from)) {
			// get the site email address
			$site = elgg_get_site_entity();
			$from = new Address($site->getEmailAddress(), $site->getDisplayName());
		} elseif ($from instanceof \ElggSite) {
			// use site email address
			$from = new Address($from->getEmailAddress(), $from->getDisplayName());
		} elseif ($from instanceof \ElggEntity) {
			// If there's an email address, use it - but only if it's not from a user.
			if (!$from instanceof \ElggUser && !empty($from->email)) {
				$from = new Address($from->email, $from->getDisplayName());
			} else {
				// get the site email address
				$site = elgg_get_site_entity();
				$from_display = elgg_echo('notification:method:email:from', [$from->getDisplayName(), $site->getDisplayName()]);
				$from = new Address($site->getEmailAddress(), $from_display);
			}
		} elseif (is_string($from)) {
			$from = Address::create($from);
		}
		
		if (!$from instanceof Address) {
			throw new InvalidArgumentException('From address is not in a valid format');
		}
		
		return $from;
	}

	/**
	 * Converts mixed input to an array of Symfony address instances
	 *
	 * @param mixed $recipients recipients input
	 *
	 * @return Address[]
	 * @throws InvalidArgumentException
	 */
	protected function prepareRecipients(mixed $recipients): array {
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
			
			if ($recipient instanceof \ElggSite) {
				$recipient = new Address($recipient->getEmailAddress(), $recipient->getDisplayName());
			} elseif ($recipient instanceof \ElggEntity && !empty($recipient->email)) {
				$recipient = new Address($recipient->email, $recipient->getDisplayName());
			} elseif (is_string($recipient)) {
				$recipient = Address::create($recipient);
			}
	
			if (!$recipient instanceof Address) {
				throw new InvalidArgumentException('Recipient address is not in a valid format');
			}
			
			$result[] = $recipient;
		}
		
		return $result;
	}
}
