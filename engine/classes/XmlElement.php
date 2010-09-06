<?php
/**
 * @class XmlElement
 * A class representing an XML element for import.
 */
class XmlElement 
{
	/** The name of the element */
	public $name;
	
	/** The attributes */
	public $attributes;
	
	/** CData */
	public $content;
	
	/** Child elements */
	public $children;
};