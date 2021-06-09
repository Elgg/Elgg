<?php

namespace Elgg\Email;

use Laminas\Mime\Part;
use Laminas\Mime\Mime;

/**
 * Plaintext part for email
 *
 * @since 4.0
 *
 * @internal
 */
class PlainTextPart extends Part {
	
	/**
	 * {@inheritDoc}
	 */
	public function __construct($content = '') {
		
		$content = elgg_strip_tags($content);
		$content = html_entity_decode($content, ENT_QUOTES, 'UTF-8');
		$content = wordwrap($content);
		
		parent::__construct($content);
		
		$this->setType(Mime::TYPE_TEXT);
		$this->setCharset('UTF-8');
		$this->setId('plaintext');
	}
}
