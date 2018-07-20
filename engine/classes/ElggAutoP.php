<?php

/**
 * Create wrapper P and BR elements in HTML depending on newlines. Useful when
 * users use newlines to signal line and paragraph breaks. In all cases output
 * should be well-formed markup.
 *
 * In DIV elements, Ps are only added when there would be at
 * least two of them.
 *
 * @package    Elgg.Core
 * @subpackage Output
 */
class ElggAutoP {

	public $encoding = 'UTF-8';

	/**
	 * @var DOMDocument
	 */
	protected $_doc = null;

	/**
	 * @var DOMXPath
	 */
	protected $_xpath = null;

	protected $_blocks = 'address article area aside blockquote caption col colgroup dd
		details div dl dt fieldset figure figcaption footer form h1 h2 h3 h4 h5 h6 header
		hr hgroup legend map math menu nav noscript p pre section select style summary
		table tbody td tfoot th thead tr ul ol option li';

	/**
	 * @var array
	 */
	protected $_inlines = 'a abbr audio b button canvas caption cite code command datalist
		del dfn em embed i iframe img input ins kbd keygen label map mark meter object
		output progress q rp rt ruby s samp script select small source span strong style
		sub sup textarea time var video wbr';

	/**
	 * Descend into these elements to add Ps
	 *
	 * @var array
	 */
	protected $_descendList = 'article aside blockquote body details div footer form
		header section';

	/**
	 * Add Ps inside these elements
	 *
	 * @var array
	 */
	protected $_alterList = 'article aside blockquote body details div footer header
		section';

	/** @var string */
	protected $_unique = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->_blocks = preg_split('@\\s+@', $this->_blocks);
		$this->_descendList = preg_split('@\\s+@', $this->_descendList);
		$this->_alterList = preg_split('@\\s+@', $this->_alterList);
		$this->_inlines = preg_split('@\\s+@', $this->_inlines);
		$this->_unique = md5(__FILE__);
	}

	/**
	 * Create wrapper P and BR elements in HTML depending on newlines. Useful when
	 * users use newlines to signal line and paragraph breaks. In all cases output
	 * should be well-formed markup.
	 *
	 * In DIV, LI, TD, and TH elements, Ps are only added when their would be at
	 * least two of them.
	 *
	 * @param string $html snippet
	 * @return string|false output or false if parse error occurred
	 */
	public function process($html) {
		// normalize whitespace
		$html = str_replace(["\r\n", "\r"], "\n", $html);

		// allows preserving entities untouched
		$html = str_replace('&', $this->_unique . 'AMP', $html);

		$this->_doc = new DOMDocument();

		// parse to DOM, suppressing loadHTML warnings
		// http://www.php.net/manual/en/domdocument.loadhtml.php#95463
		$use_internal_errors = libxml_use_internal_errors(true);

		// Do not load entities. May be unnecessary, better safe than sorry
		$disable_load_entities = libxml_disable_entity_loader(true);

		if (!$this->_doc->loadHTML("<html><meta http-equiv='content-type' "
				. "content='text/html; charset={$this->encoding}'><body>{$html}</body>"
				. "</html>")) {
			libxml_use_internal_errors($use_internal_errors);
			libxml_disable_entity_loader($disable_load_entities);
			return false;
		}

		libxml_use_internal_errors($use_internal_errors);
		libxml_disable_entity_loader($disable_load_entities);

		$this->_xpath = new DOMXPath($this->_doc);

		// start processing recursively at the BODY element
		$nodeList = $this->_xpath->query('//body[1]');
		if ($nodeList->item(0) instanceof DOMText) {
			// May be https://github.com/facebook/hhvm/issues/7745
			// Um... try again?
			$this->_xpath = new DOMXPath($this->_doc);
			$nodeList = $this->_xpath->query('//body[1]');

			if ($nodeList->item(0) instanceof DOMText) {
				// not going to work
				throw new \RuntimeException('DOMXPath::query for BODY element returned a text node');
			}
		}
		$this->addParagraphs($nodeList->item(0));

		// serialize back to HTML
		$html = $this->_doc->saveHTML();

		// Note: we create <autop> elements, which will later be converted to paragraphs

		// split AUTOPs into multiples at /\n\n+/
		$html = preg_replace('/(' . $this->_unique . 'NL){2,}/', '</autop><autop>', $html);
		$html = str_replace([$this->_unique . 'BR', $this->_unique . 'NL', '<br>'],
				'<br />',
				$html);
		$html = str_replace('<br /></autop>', '</autop>', $html);

		// re-parse so we can handle new AUTOP elements

		// parse to DOM, suppressing loadHTML warnings
		// http://www.php.net/manual/en/domdocument.loadhtml.php#95463
		$use_internal_errors = libxml_use_internal_errors(true);

		// Do not load entities. May be unnecessary, better safe than sorry
		$disable_load_entities = libxml_disable_entity_loader(true);

		if (!$this->_doc->loadHTML($html)) {
			libxml_use_internal_errors($use_internal_errors);
			libxml_disable_entity_loader($disable_load_entities);
			return false;
		}

		libxml_use_internal_errors($use_internal_errors);
		libxml_disable_entity_loader($disable_load_entities);

		// must re-create XPath object after DOM load
		$this->_xpath = new DOMXPath($this->_doc);

		// strip AUTOPs that only have comments/whitespace
		foreach ($this->_xpath->query('//autop') as $autop) {
			/* @var DOMElement $autop */
			$hasContent = false;
			if (trim($autop->textContent) !== '') {
				$hasContent = true;
			} else {
				foreach ($autop->childNodes as $node) {
					if ($node->nodeType === XML_ELEMENT_NODE) {
						$hasContent = true;
						break;
					}
				}
			}
			if (!$hasContent) {
				// mark to be later replaced w/ preg_replace (faster than moving nodes out)
				$autop->setAttribute("r", "1");
			}
		}

		// If a DIV contains a single AUTOP, remove it
		foreach ($this->_xpath->query('//div') as $el) {
			/* @var DOMElement $el */
			$autops = $this->_xpath->query('./autop', $el);
			if ($autops->length === 1) {
				$firstAutop = $autops->item(0);
				/* @var DOMElement $firstAutop */
				$firstAutop->setAttribute("r", "1");
			}
		}

		$html = $this->_doc->saveHTML();

		// trim to the contents of BODY
		$bodyStart = strpos($html, '<body>');
		$bodyEnd = strpos($html, '</body>', $bodyStart + 6);
		$html = substr($html, $bodyStart + 6, $bodyEnd - $bodyStart - 6);
		
		// strip AUTOPs that should be removed
		$html = preg_replace('@<autop r="1">(.*?)</autop>@', '\\1', $html);

		// commit to converting AUTOPs to Ps
		$html = str_replace('<autop>', "\n<p>", $html);
		$html = str_replace('</autop>', "</p>\n", $html);
		
		$html = str_replace('<br>', '<br />', $html);
		$html = str_replace($this->_unique . 'AMP', '&', $html);
		return $html;
	}

	/**
	 * Add P and BR elements as necessary
	 *
	 * @param DOMElement $el DOM element
	 * @return void
	 */
	protected function addParagraphs(DOMElement $el) {
		// no need to call recursively, just queue up
		$elsToProcess = [$el];
		$inlinesToProcess = [];
		while ($el = array_shift($elsToProcess)) {
			// if true, we can alter all child nodes, if not, we'll just call
			// addParagraphs on each element in the descendInto list
			$alterInline = in_array($el->nodeName, $this->_alterList);

			// inside affected elements, we want to trim leading whitespace from
			// the first text node
			$ltrimFirstTextNode = true;

			// should we open a new AUTOP element to move inline elements into?
			$openP = true;
			$autop = null;

			// after BR, ignore a newline
			$isFollowingBr = false;

			$node = $el->firstChild;
			while (null !== $node) {
				if ($alterInline) {
					if ($openP) {
						$openP = false;
						// create a P to move inline content into (this may be removed later)
						$autop = $el->insertBefore($this->_doc->createElement('autop'), $node);
					}
				}

				$isElement = ($node->nodeType === XML_ELEMENT_NODE);
				if ($isElement) {
					$isBlock = in_array($node->nodeName, $this->_blocks);
					if (!$isBlock) {
						// if we start with an inline element we don't need to do this
						$ltrimFirstTextNode = false;
					}
				} else {
					$isBlock = false;
				}

				if ($alterInline) {
					$isText = ($node->nodeType === XML_TEXT_NODE);
					$isLastInline = (! $node->nextSibling
							|| ($node->nextSibling->nodeType === XML_ELEMENT_NODE
								&& in_array($node->nextSibling->nodeName, $this->_blocks)));
					if ($isElement) {
						$isFollowingBr = ($node->nodeName === 'br');
					}

					if ($isText) {
						$nodeText = $node->nodeValue;

						if ($ltrimFirstTextNode) {
							// we're at the beginning of a sequence of text/inline elements
							$nodeText = ltrim($nodeText);
							$ltrimFirstTextNode = false;
						}
						if ($isFollowingBr && preg_match('@^[ \\t]*\\n[ \\t]*@', $nodeText, $m)) {
							// if a user ends a line with <br>, don't add a second BR
							$nodeText = substr($nodeText, strlen($m[0]));
						}
						if ($isLastInline) {
							// we're at the end of a sequence of text/inline elements
							$nodeText = rtrim($nodeText);
						}
						$nodeText = str_replace("\n", $this->_unique . 'NL', $nodeText);
						$tmpNode = $node;
						$node = $node->nextSibling; // move loop to next node

						// alter node in place, then move into AUTOP
						$tmpNode->nodeValue = $nodeText;
						$autop->appendChild($tmpNode);

						continue;
					}
				}
				if ($isBlock || ! $node->nextSibling) {
					if ($isBlock) {
						if (in_array($node->nodeName, $this->_descendList)) {
							$elsToProcess[] = $node;
							//$this->addParagraphs($node);
						}
					}
					$openP = true;
					$ltrimFirstTextNode = true;
				}
				if ($alterInline) {
					if (! $isBlock) {
						$tmpNode = $node;
						if ($isElement && false !== strpos($tmpNode->textContent, "\n")) {
							$inlinesToProcess[] = $tmpNode;
						}
						$node = $node->nextSibling;
						$autop->appendChild($tmpNode);
						continue;
					}
				}

				$node = $node->nextSibling;
			}
		}

		// handle inline nodes
		// no need to recurse, just queue up
		while ($el = array_shift($inlinesToProcess)) {
			$ignoreLeadingNewline = false;
			foreach ($el->childNodes as $node) {
				if ($node->nodeType === XML_ELEMENT_NODE) {
					if ($node->nodeValue === 'BR') {
						$ignoreLeadingNewline = true;
					} else {
						$ignoreLeadingNewline = false;
						if (false !== strpos($node->textContent, "\n")) {
							$inlinesToProcess[] = $node;
						}
					}
					continue;
				} elseif ($node->nodeType === XML_TEXT_NODE) {
					$text = $node->nodeValue;
					if ($text[0] === "\n" && $ignoreLeadingNewline) {
						$text = substr($text, 1);
						$ignoreLeadingNewline = false;
					}
					$node->nodeValue = str_replace("\n", $this->_unique . 'BR', $text);
				}
			}
		}
	}
}
