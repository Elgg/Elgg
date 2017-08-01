<?php

namespace Elgg;

use RuntimeException;
use Zend\Mail\Message;
use Zend\Mail\Transport\TransportInterface;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @access private
 * @since  3.0
 */
class EmailService {

	/**
	 * @var Config
	 */
	private $config;

	/**
	 * @var PluginHooksService
	 */
	private $hooks;

	/**
	 * @var TransportInterface
	 */
	private $mailer;

	/**
	 * @var Logger
	 */
	private $logger;

	/**
	 * Constructor
	 *
	 * @param Config             $config Config
	 * @param PluginHooksService $hooks  Hook registration service
	 * @param TransportInterface $mailer Mailer
	 * @param Logger             $logger Logger
	 */
	public function __construct(Config $config, PluginHooksService $hooks, TransportInterface $mailer, Logger $logger) {
		$this->config = $config;
		$this->hooks = $hooks;
		$this->mailer = $mailer;
		$this->logger = $logger;
	}

	/**
	 * Sends an email
	 *
	 * @param Email $email Email
	 *
	 * @return bool
	 * @throws RuntimeException
	 */
	public function send(Email $email) {
		$email = $this->hooks->trigger('prepare', 'system:email', null, $email);
		if (!$email instanceof Email) {
			$msg = "'prepare','system:email' hook handlers should return an instance of " . Email::class;
			throw new RuntimeException($msg);
		}

		$hook_params = [
			'email' => $email,
		];

		$is_valid = $email->getFrom() && $email->getTo();
		if (!$this->hooks->trigger('validate', 'system:email', $hook_params, $is_valid)) {
			return false;
		}

		return $this->transport($email);
	}

	/**
	 * Transports an email
	 *
	 * @param Email $email Email
	 *
	 * @return bool
	 * @throws RuntimeException
	 */
	public function transport(Email $email) {

		$hook_params = [
			'email' => $email,
		];

		if ($this->hooks->trigger('transport', 'system:email', $hook_params, false)) {
			return true;
		}

		$subject = elgg_strip_tags($email->getSubject());
		$subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');
		// Sanitise subject by stripping line endings
		$subject = preg_replace("/(\r\n|\r|\n)/", " ", $subject);
		$subject = trim($subject);

		$body = elgg_strip_tags($email->getBody());
		$body = html_entity_decode($body, ENT_QUOTES, 'UTF-8');
		$body = wordwrap($body);

		$message = new Message();
		$message->setEncoding('UTF-8');
		$message->addFrom($email->getFrom());
		$message->addTo($email->getTo());
		$message->setSubject($subject);
		$message->setBody($body);

		$headers = [
			"Content-Type" => "text/plain; charset=UTF-8; format=flowed",
			"MIME-Version" => "1.0",
			"Content-Transfer-Encoding" => "8bit",
		];
		$headers = array_merge($headers, $email->getHeaders());

		foreach ($headers as $name => $value) {
			// See #11018
			// Create a headerline as a concatenated string "name: value"
			// This is done to force correct class detection for each header type,
			// which influences the output of the header in the message
			$message->getHeaders()->addHeaderLine("{$name}: {$value}");
		}

		// allow others to modify the $message content
		// eg. add html body, add attachments
		$message = $this->hooks->trigger('zend:message', 'system:email', $hook_params, $message);

		try {
			$this->mailer->send($message);
		} catch (RuntimeException $e) {
			$this->logger->error($e->getMessage());

			return false;
		}

		return true;
	}

}
