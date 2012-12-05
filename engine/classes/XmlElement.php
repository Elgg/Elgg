<?php
/**
 * A class representing an XML element for import.
 *
 * @package    Elgg.Core
 * @subpackage XML
 */
class XmlElement {
	/** The name of the element */
	public $name;

	/** The attributes */
	public $attributes;

	/** CData */
	public $content;

	/** Child elements */
	public $children;
};
