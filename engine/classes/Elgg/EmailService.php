<?php

namespace Elgg;

use Elgg\Assets\ImageFetcherService;
use Elgg\Email\Attachment;
use Elgg\Exceptions\RuntimeException;
use Elgg\Traits\Loggable;
use Elgg\Views\HtmlFormatter;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email as SymfonyEmail;

/**
 * Email service
 *
 * @internal
 * @since 3.0
 */
class EmailService {

	use Loggable;

	/**
	 * Constructor
	 *
	 * @param Config              $config         Config
	 * @param EventsService       $events         Events service
	 * @param MailerInterface     $mailer         Mailer
	 * @param HtmlFormatter       $html_formatter Html formatter
	 * @param ViewsService        $views          Views service
	 * @param ImageFetcherService $image_fetcher  Image fetcher
	 */
	public function __construct(
		protected Config $config,
		protected EventsService $events,
		protected MailerInterface $mailer,
		protected HtmlFormatter $html_formatter,
		protected ViewsService $views,
		protected ImageFetcherService $image_fetcher
	) {
	}

	/**
	 * Sends an email
	 *
	 * @param Email $email Email
	 *
	 * @return bool
	 * @throws RuntimeException
	 */
	public function send(Email $email): bool {
		$email = $this->events->triggerResults('prepare', 'system:email', [], $email);
		if (!$email instanceof Email) {
			$msg = "'prepare','system:email' event handlers should return an instance of " . Email::class;
			throw new RuntimeException($msg);
		}

		$is_valid = $email->getFrom() && !empty($email->getTo());
		if (!$this->events->triggerResults('validate', 'system:email', ['email' => $email], $is_valid)) {
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
	 */
	public function transport(Email $email): bool {
		if ($this->events->triggerResults('transport', 'system:email', ['email' => $email], false)) {
			return true;
		}

		// create the e-mail message
		$message = new SymfonyEmail();
		$message->sender($email->getSender());
		$message->addFrom($email->getFrom());
		$message->addTo(...$email->getTo());
		$message->addCc(...$email->getCc());
		$message->addBcc(...$email->getBcc());
		
		// set headers
		$headers = [
			'MIME-Version' => '1.0',
			'Content-Transfer-Encoding' => '8bit',
		];
		$headers = array_merge($headers, $email->getHeaders());

		foreach ($headers as $name => $value) {
			$message->getHeaders()->addHeader($name, $value);
		}
		
		// add the body to the message
		$this->setMessageBody($message, $email);
		$message->subject($this->prepareSubject($email->getSubject()));
		
		// allow others to modify the $message content
		// eg. add HTML body, add attachments
		$message = $this->events->triggerResults('message', 'system:email', ['email' => $email], $message);
		
		try {
			$this->mailer->send($message);
		} catch (TransportExceptionInterface $e) {
			$this->getLogger()->error($e->getMessage());

			return false;
		}

		return true;
	}
	
	/**
	 * Prepare the subject string
	 *
	 * @param string $subject initial subject string
	 *
	 * @return string
	 */
	protected function prepareSubject(string $subject): string {
		$subject = elgg_strip_tags($subject);
		$subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');
		// Sanitize subject by stripping line endings
		$subject = preg_replace("/(\r\n|\r|\n)/", ' ', $subject);
		return trim($subject);
	}
	
	/**
	 * Build the body part of the e-mail message
	 *
	 * @param SymfonyEmail $message Current message
	 * @param Email        $email   Email
	 *
	 * @return void
	 */
	protected function setMessageBody(SymfonyEmail $message, Email $email): void {
		// add plaintext body part
		$plain_text = $email->getBody();
		$plain_text = elgg_strip_tags($plain_text);
		$plain_text = html_entity_decode($plain_text, ENT_QUOTES, 'UTF-8');
		$plain_text = wordwrap($plain_text);
		
		$message->text($plain_text);
		$this->addHtmlPart($message, $email);
		
		$attachments = $email->getAttachments();
		foreach ($attachments as $attachment) {
			$message->addPart($attachment);
		}
	}
	
	/**
	 * Add the HTML part to the e-mail message
	 *
	 * @param SymfonyEmail $message Current message
	 * @param \Elgg\Email  $email   the e-mail to get information from
	 *
	 * @return void
	 */
	protected function addHtmlPart(SymfonyEmail $message, \Elgg\Email $email): void {
		if (!$this->config->email_html_part) {
			return;
		}
		
		$mail_params = $email->getParams();
		
		$html_text = elgg_extract('html_message', $mail_params);
		if (is_string($html_text)) {
			// HTML text already provided
			if (elgg_extract('convert_css', $mail_params, true)) {
				// still needs to be converted to inline CSS
				$css = (string) elgg_extract('css', $mail_params);
				$html_text = $this->html_formatter->inlineCss($html_text, $css);
			}
		} else {
			$html_text = $this->makeHtmlBody([
				'subject' => $email->getSubject(),
				'body' => elgg_extract('html_body', $mail_params, $email->getBody()),
				'email' => $email,
			]);
		}
		
		// normalize urls in text
		$html_text = $this->html_formatter->normalizeUrls($html_text);
		if (empty($html_text)) {
			return;
		}
		
		$email_html_part_images = $this->config->email_html_part_images;
		if ($email_html_part_images !== 'base64' && $email_html_part_images !== 'attach') {
			$message->html($html_text);
			return;
		}
		
		$images = $this->findImages($html_text);
		if (empty($images)) {
			$message->html($html_text);
			return;
		}
		
		if ($email_html_part_images === 'base64') {
			foreach ($images as $url) {
				// remove wrapping quotes from the url
				$image_url = substr($url, 1, -1);
				
				// get the image contents
				$image = $this->image_fetcher->getImage($image_url);
				if (empty($image)) {
					continue;
				}
				
				// build a valid uri
				// https://en.wikipedia.org/wiki/Data_URI_scheme
				$base64image = $image['content-type'] . ';charset=UTF-8;base64,' . base64_encode($image['data']);
				
				// build inline image
				$replacement = str_replace($image_url, "data:{$base64image}", $url);
				
				// replace in text
				$html_text = str_replace($url, $replacement, $html_text);
			}
			
			$message->html($html_text);
			return;
		}
		
		// attach images
		$attachments = [];
		foreach ($images as $url) {
			// remove wrapping quotes from the url
			$image_url = substr($url, 1, -1);
			
			// get the image contents
			$image = $this->image_fetcher->getImage($image_url);
			if (empty($image)) {
				continue;
			}
			
			// Unique ID
			$uid = uniqid() . '@elgg-image';
			
			$attachments[$uid] = $image;
			
			// replace url in the text with uid
			$replacement = str_replace($image_url, "cid:{$uid}", $url);
			
			$html_text = str_replace($url, $replacement, $html_text);
		}
		
		// split HTML body and related images
		foreach ($attachments as $uid => $image_data) {
			$inline_image = Attachment::factory([
				'content' => $image_data['data'],
				'type' => $image_data['content-type'],
				'filename' => $image_data['name'],
				'id' => $uid,
			]);
			
			$message->addPart($inline_image->asInline());
		}
		
		$message->html($html_text);
	}
	
	/**
	 * Create the HTML content for use in a HTML email part
	 *
	 * @param array $options additional options to pass through to views
	 *
	 * @return string
	 */
	protected function makeHtmlBody(array $options = []): string {
		$defaults = [
			'subject' => '',
			'body' => '',
			'language' => elgg_get_current_language(),
		];
		
		$options = array_merge($defaults, $options);
		
		$options['body'] = $this->html_formatter->formatBlock($options['body']);
	
		// generate HTML mail body
		$options['body'] = $this->views->renderView('email/elements/body', $options);

		$css_views = $this->views->renderView('elements/variables.css', $options);
		$css_views .= $this->views->renderView('email/email.css', $options);

		$minifier = new \MatthiasMullie\Minify\CSS($css_views);
		$css = $minifier->minify();
		
		$options['css'] = $css;
		
		$html = $this->views->renderView('email/elements/html', $options);
		
		return $this->html_formatter->inlineCss($html, $css);
	}
	
	/**
	 * Find img src's in text. The results contain the original quotes surrounding the image src url.
	 *
	 * @param string $text the text to search though
	 *
	 * @return string[]
	 */
	protected function findImages(string $text): array {
		if (empty($text)) {
			return [];
		}
		
		// find all matches
		$matches = [];
		$pattern = '/\ssrc=([\'"]\S+[\'"])/i';
		
		preg_match_all($pattern, $text, $matches);
		
		if (empty($matches) || !isset($matches[1])) {
			return [];
		}
		
		// return all the found image urls
		return array_unique($matches[1]);
	}
}
