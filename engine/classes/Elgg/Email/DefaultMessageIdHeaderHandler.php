<?php

namespace Elgg\Email;

/**
 * Sets the message-id header for emails
 *
 * @since 4.0
 */
class DefaultMessageIdHeaderHandler {
	
	/**
	 * Adds default Message-ID header to all e-mails
	 *
	 * @param \Elgg\Hook $hook 'prepare', 'system:email'
	 *
	 * @see    https://tools.ietf.org/html/rfc5322#section-3.6.4
	 *
	 * @return void|\Elgg\Email
	 */
	public function __invoke(\Elgg\Hook $hook) {
		$email = $hook->getValue();
		if (!$email instanceof \Elgg\Email) {
			return;
		}
		
		$hostname = parse_url(elgg_get_site_url(), PHP_URL_HOST);
		$url_path = parse_url(elgg_get_site_url(), PHP_URL_PATH);
		
		$mt = microtime(true);
		
		$email->addHeader('Message-ID', "{$url_path}.default.{$mt}@{$hostname}");
		
		return $email;
	}
}
