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
	 * {@inheritDoc}
	 */
	public function __construct($content = '') {
		parent::__construct($content);
		
		$this->setType(Mime::TYPE_HTML);
		$this->setCharset('UTF-8');
		$this->setEncoding(Mime::ENCODING_BASE64);
		$this->setId('htmltext');
	}
}
