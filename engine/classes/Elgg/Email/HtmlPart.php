<?php

namespace Elgg\Email;

use Laminas\Mime\Part;
use Laminas\Mime\Mime;

/**
 * Html part for email
 *
 * @since 4.0
 *
 * @internal
 */
class HtmlPart extends Part {
	
	/**
	 * Create a new HTML Part
	 *
	 * @param mixed $content String or Stream containing the content
	 *
	 * @throws \Laminas\Mime\Exception\InvalidArgumentException
	 *
	 * @see Part::__construct()
	 */
	public function __construct($content = '') {
		parent::__construct($content);
		
		$this->setType(Mime::TYPE_HTML);
		$this->setCharset('UTF-8');
		$this->setEncoding(Mime::ENCODING_BASE64);
		$this->setId('htmltext');
	}
}
