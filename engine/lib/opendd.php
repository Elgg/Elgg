<?php
/**
 * OpenDD PHP Library.
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @version 0.4
 * @link http://elgg.org/
 */

include_once("xml.php");

require_once dirname(dirname(__FILE__)).'/classes/ODDDocument.php';
require_once dirname(dirname(__FILE__)).'/classes/ODD.php';
require_once dirname(dirname(__FILE__)).'/classes/ODDEntity.php';

/**
 * Attempt to construct an ODD object out of a XmlElement or sub-elements.
 *
 * @param XmlElement $element The element(s)
 * @return mixed An ODD object if the element can be handled, or false.
 */
function ODD_factory(XmlElement $element) {
	$name = $element->name;
	$odd = false;

	switch ($name) {
		case 'entity' :
			$odd = new ODDEntity("","","");
			break;
		case 'metadata' :
			$odd = new ODDMetaData("","","","");
			break;
		case 'relationship' :
			$odd = new ODDRelationship("","","");
			break;
	}

	// Now populate values
	if ($odd) {
		// Attributes
		foreach ($element->attributes as $k => $v) {
			$odd->setAttribute($k,$v);
		}

		// Body
		$body = $element->content;
		$a = stripos($body, "<![CDATA");
		$b = strripos($body, "]]>");
		if (($body) && ($a!==false) && ($b!==false)) {
			$body = substr($body, $a+8, $b-($a+8));
		}

		$odd->setBody($body);
	}

	return $odd;
}

/**
 * Import an ODD document.
 *
 * @param string $xml The XML ODD.
 * @return ODDDocument
 */
function ODD_Import($xml) {
	// Parse XML to an array
	$elements = xml_to_object($xml);

	// Sanity check 1, was this actually XML?
	if ((!$elements) || (!$elements->children)) {
		return false;
	}

	// Create ODDDocument
	$document = new ODDDocument();

	// Itterate through array of elements and construct ODD document
	$cnt = 0;

	foreach ($elements->children as $child) {
		$odd = ODD_factory($child);

		if ($odd) {
			$document->addElement($odd);
			$cnt++;
		}
	}

	// Check that we actually found something
	if ($cnt == 0) {
		return false;
	}

	return $document;
}

/**
 * Export an ODD Document.
 *
 * @param ODDDocument $document The Document.
 * @param ODDWrapperFactory $wrapper Optional wrapper permitting the export process to embed ODD in other document formats.
 */
function ODD_Export(ODDDocument $document) {
	return "$document";
}