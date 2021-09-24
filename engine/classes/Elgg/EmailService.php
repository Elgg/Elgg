<?php

namespace Elgg;

use Elgg\Assets\CssCompiler;
use Elgg\Assets\ImageFetcherService;
use Elgg\Email\Attachment;
use Elgg\Email\HtmlPart;
use Elgg\Email\PlainTextPart;
use Elgg\Traits\Loggable;
use Elgg\Views\HtmlFormatter;
use Laminas\Mail\Header\ContentType;
use Laminas\Mail\Message as MailMessage;
use Laminas\Mail\Transport\TransportInterface;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Exception\InvalidArgumentException;
use Laminas\Mime\Part;
use Laminas\Mime\Mime;
use RuntimeException;

/**
 * WARNING: API IN FLUX. DO NOT USE DIRECTLY.
 *
 * Use the elgg_* versions instead.
 *
 * @internal
 * @since 3.0
 */
class EmailService {

	use Loggable;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * @var PluginHooksService
	 */
	protected $hooks;

	/**
	 * @var TransportInterface
	 */
	protected $mailer;

	/**
	 * @var HtmlFormatter
	 */
	protected $html_formatter;

	/**
	 * @var ImageFetcherService
	 */
	protected $image_fetcher;

	/**
	 * @var ViewsService
	 */
	protected $views;

	/**
	 * @var CssCompiler
	 */
	protected $css_compiler;

	/**
	 * Constructor
	 *
	 * @param Config              $config         Config
	 * @param PluginHooksService  $hooks          Hook registration service
	 * @param TransportInterface  $mailer         Mailer
	 * @param HtmlFormatter       $html_formatter Html formatter
	 * @param ViewsService        $views          Views service
	 * @param ImageFetcherService $image_fetcher  Image fetcher
	 * @param CssCompiler         $css_compiler   Css compiler
	 */
	public function __construct(
			Config $config,
			PluginHooksService $hooks,
			TransportInterface $mailer,
			HtmlFormatter $html_formatter,
			ViewsService $views,
			ImageFetcherService $image_fetcher,
			CssCompiler $css_compiler
		) {
		$this->config = $config;
		$this->hooks = $hooks;
		$this->mailer = $mailer;
		$this->html_formatter = $html_formatter;
		$this->views = $views;
		$this->image_fetcher = $image_fetcher;
		$this->css_compiler = $css_compiler;
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

		$is_valid = $email->getFrom() && !empty($email->getTo());
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

		if ($this->hooks->trigger('transport', 'system:email', ['email' => $email], false)) {
			return true;
		}

		// create the e-mail message
		$message = new MailMessage();
		$message->setEncoding('UTF-8');
		$message->setSender($email->getFrom());
		$message->addFrom($email->getFrom());
		$message->addTo($email->getTo());
		$message->addCc($email->getCc());
		$message->addBcc($email->getBcc());
		
		// set headers
		$headers = [
			'MIME-Version' => '1.0',
			'Content-Transfer-Encoding' => '8bit',
		];
		$headers = array_merge($headers, $email->getHeaders());

		foreach ($headers as $name => $value) {
			// See #11018
			// Create a headerline as a concatenated string "name: value"
			// This is done to force correct class detection for each header type,
			// which influences the output of the header in the message
			$message->getHeaders()->addHeaderLine("{$name}: {$value}");
		}
		
		// add the body to the message
		try {
			$message = $this->setMessageBody($message, $email);
		} catch (InvalidArgumentException $e) {
			$this->getLogger()->error($e->getMessage());
			
			return false;
		}
		
		$message->setSubject($this->prepareSubject($email->getSubject()));
		
		// allow others to modify the $message content
		// eg. add html body, add attachments
		$message = $this->hooks->trigger('zend:message', 'system:email', ['email' => $email], $message);

		// fix content type header
		// @see https://github.com/Elgg/Elgg/issues/12555
		$ct = $message->getHeaders()->get('Content-Type');
		if ($ct instanceof ContentType) {
			$ct->addParameter('format', 'flowed');
		}
		
		try {
			$this->mailer->send($message);
		} catch (RuntimeException $e) {
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
		// Sanitise subject by stripping line endings
		$subject = preg_replace("/(\r\n|\r|\n)/", " ", $subject);
		return trim($subject);
	}
	
	/**
	 * Build the body part of the e-mail message
	 *
	 * @param MailMessage $message Current message
	 * @param Email       $email   Email
	 *
	 * @return \Laminas\Mail\Message
	 */
	protected function setMessageBody(MailMessage $message, Email $email): MailMessage {
		// create body
		$multipart = new MimeMessage();
		$raw_body = $email->getBody();
		$message_content_type = '';
		
		// add plain text part
		$plain_text_part = new PlainTextPart($raw_body);
		$multipart->addPart($plain_text_part);
		
		$make_html = (bool) elgg_get_config('email_html_part');
		
		if ($make_html) {
			$multipart->addPart($this->makeHtmlPart($email));
			$message_content_type = Mime::MULTIPART_ALTERNATIVE;
		}
		
		$body = $multipart;
		
		// process attachments
		$attachments = $email->getAttachments();
		if (!empty($attachments)) {
			if ($make_html) {
				$multipart_content = new Part($multipart->generateMessage());
				$multipart_content->setType(Mime::MULTIPART_ALTERNATIVE);
				$multipart_content->setBoundary($multipart->getMime()->boundary());
			
				$body = new MimeMessage();
				$body->addPart($multipart_content);
			}
			
			foreach ($attachments as $attachement) {
				$body->addPart($attachement);
			}
			
			$message_content_type = Mime::MULTIPART_MIXED;
		}
		
		$message->setBody($body);
		
		if (!empty($message_content_type)) {
			// set correct message content type
			
			$headers = $message->getHeaders();
			foreach ($headers as $header) {
				if (!$header instanceof ContentType) {
					continue;
				}
				
				$header->setType($message_content_type);
				$header->addParameter('boundary', $body->getMime()->boundary());
				break;
			}
		}
		
		return $message;
	}
	
	/**
	 * Make the html part of the e-mail message
	 *
	 * @param \Elgg\Email $email the e-mail to get information from
	 *
	 * @return \Laminas\Mime\Part
	 */
	protected function makeHtmlPart(\Elgg\Email $email): Part {
		$mail_params = $email->getParams();
		$html_text = elgg_extract('html_message', $mail_params);
		if ($html_text instanceof Part) {
			return $html_text;
		}
	
		if (is_string($html_text)) {
			// html text already provided
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
			return new HtmlPart($html_text);
		}
		
		$email_html_part_images = elgg_get_config('email_html_part_images');
		if ($email_html_part_images !== 'base64' && $email_html_part_images !== 'attach') {
			return new HtmlPart($html_text);
		}
		
		$images = $this->findImages($html_text);
		if (empty($images)) {
			return new HtmlPart($html_text);
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
			
			return new HtmlPart($html_text);
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
			$uid = uniqid();
			
			$attachments[$uid] = $image;
			
			// replace url in the text with uid
			$replacement = str_replace($image_url, "cid:{$uid}", $url);
			
			$html_text = str_replace($url, $replacement, $html_text);
		}
		
		// split html body and related images
		$message = new MimeMessage();
		$message->addPart(new HtmlPart($html_text));
		
		foreach ($attachments as $uid => $image_data) {
			$attachment = Attachment::factory([
				'id' => $uid,
				'content' => $image_data['data'],
				'type' => $image_data['content-type'],
				'filename' => $image_data['name'],
				'encoding' => Mime::ENCODING_BASE64,
				'disposition' => Mime::DISPOSITION_INLINE,
				'charset' => 'UTF-8',
			]);
			
			$message->addPart($attachment);
		}
		
		$part = new Part($message->generateMessage());
		$part->setType(Mime::MULTIPART_RELATED);
		$part->setBoundary($message->getMime()->boundary());
		
		return $part;
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
			'language' => get_current_language(),
		];
		
		$options = array_merge($defaults, $options);
		
		$options['body'] = $this->html_formatter->formatBlock($options['body']);
	
		// generate HTML mail body
		$options['body'] = $this->views->renderView('email/elements/body', $options);
		
		$cssmin = new \CSSmin(false);
		$css = $cssmin->run($this->css_compiler->compile($this->views->renderView('email/email.css', $options)));
		
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
