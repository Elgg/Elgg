<?php

	/***************************************************************************

	phpdomxml-0.8.5 - an XML Document Object Model implementation for PHP 4.10+.
	(c) copyright 2002-2003, Bas van Gaalen. All rights reserved.

	This library may be distributed under the terms of the LICENSE, which is
	included in this package.

	***************************************************************************/	


	// Define element type constants
	define('XML_TYPE_NODE', 1);
	define('XML_TYPE_TEXT', 3);
	define('XML_TYPE_CDATA', 4);


	// XMLNode sub-class: Text =================================================
	class XMLText {

		// Property (r); returns the node value of the XML object.
		var $nodeValue;

		// Property (r); retrieves the type of the requested node. 
		var $nodeType;

		function XMLText() {

			// Init property
			$this->nodeValue = null;
			$this->nodeType = XML_TYPE_TEXT;

		} // End XMLText-constructor

	} // End XMLText class


	// XML sub-class: Tags =====================================================
	class XMLNode extends XMLText {

		// Public properties ----------------------------------------------------

		// Collection (r/w); returns an associative array containing all
		// 	attributes of the specified XML object
		var $attributes;

		// Collection (r); returns an array of the specified XML object's children.
		var $childNodes;

		// Property (r); references the first child in the parent node's child list.
		var $firstChild;

		// Property (r); references the last child in the parent node's child list.
		var $lastChild;

		// Property (r); references the previous sibling in the parent node's child list.
		var $previousSibling;

		// Property (r); references the next sibling in the parent node's child list.
		var $nextSibling;

		// Property (r/w); takes or returns the node name of the XML object.
		var $nodeName;

		// Property (r); references the parent node of the specified XML object.
		var $parentNode;

		// Property (r); retrieves the type of the requested node. 
		var $nodeType;

		// XMLNode constructor --------------------------------------------------
		function XMLNode() {

			// Init properties
			$this->attributes = null;
			$this->childNodes = null;
			$this->firstChild = null;
			$this->lastChild = null;
			$this->previousSibling = null;
			$this->nextSibling = null;
			$this->nodeName = null;
			$this->parentNode = null;
			$this->nodeType = XML_TYPE_NODE;

		} // End XMLNode-constructor

		// Private methods ------------------------------------------------------
		function _xml_get_children($vals, &$i) {
			$children = array();

			// CData section before children
			if (isset($vals[$i]['value'])) {
				#print "iso: 1 - CDATA section found! [" . $vals[$i]['value'] . "]\n";
				$tmp = new XMLText();
				$tmp->nodeValue = $vals[$i]['value'];
				$tmp->nodeType = XML_TYPE_CDATA;
				$children[] = $tmp;
			}

			// Browse children
			$lastelm = '';
			$nChildren = count($vals);
			while (++$i < $nChildren) {
				switch ($vals[$i]['type']) {

					case 'cdata':
						if ($lastelm != 'cdata') {

							// New CData section
							$tmp = new XMLText();
							#print "iso: 2 - CDATA section found! [" . $vals[$i]['value'] . "]\n";
							$tmp->nodeValue = $vals[$i]['value'];
							$tmp->nodeType = XML_TYPE_CDATA;
							$children[] = $tmp;

						} else {

							// Continuing last CData section
							#print "iso: 3 - CDATA section found! [" . $vals[$i]['value'] . "]\n";
							$children[count($children)-1]->nodeValue .= $vals[$i]['value'];

						}
						break;

					case 'complete':
						$tmp = new XMLNode();
						$tmp->nodeName = $vals[$i]['tag'];
						$tmp->attributes = isset($vals[$i]['attributes'])?$vals[$i]['attributes']:null;
						// Iso: Make sure all the attributes are properly escaped
						if (!empty($tmp->attributes)) {
							foreach ($tmp->attributes as $attr => $value) {
								$tmp->attributes[$attr] = htmlspecialchars($value);
							}
						}

						if (isset($vals[$i]['value'])) {
							#print "iso: 4 - Text section found! [" . $vals[$i]['value'] . "]\n";
							// Iso: added htmlspecialchars filter
							$tmp->appendChild(XMLNode::createTextNode(htmlspecialchars($vals[$i]['value'])));
						}
						$tmp->parentNode = $this;
						$children[] = $tmp;
						break;

					case 'open':
						$tmp = new XMLNode();
						$tmp->nodeName = $vals[$i]['tag'];
						$tmp->attributes = isset($vals[$i]['attributes'])?$vals[$i]['attributes']:null;
						// Iso: Make sure all the attributes are properly escaped
						if (!empty($tmp->attributes)) {
							foreach ($tmp->attributes as $attr => $value) {
								$tmp->attributes[$attr] = htmlspecialchars($value);
							}
						}

						$tmp->parentNode = $this;
						$tmp->childNodes = $tmp->_xml_get_children($vals, $i);
						$children[] = $tmp;
						break;

					case 'close':
						$nThisChildren = count($children);
						if ($nThisChildren > 1) {
							for ($j = $nThisChildren-2; $j >= 0; $j--)
								$children[$j]->nextSibling =& $children[$j+1];
							for ($j = 1; $j < $nThisChildren; $j++)
								$children[$j]->previousSibling =& $children[$j-1];
						}
						$this->firstChild =& $children[0];
						$this->lastChild =& $children[($nThisChildren-1) % $nThisChildren];
						return $children;
						break;

				}

				$lastelm = $vals[$i]['type'];

			}

		} // End _xml_get_children

		// Public methods -------------------------------------------------------

		// Appends the specified child node to the XML object's child list.
		function appendChild(&$child) {

			// Set child's parentNode
			$child->parentNode =& $this;

			// Add child to list of childNodes
			$this->childNodes[] =& $child;

			// Is child of type node?
			if ($child->nodeType == XML_TYPE_NODE) {

				// Set child's previousSibling
				$child->previousSibling =& $this->lastChild;
			}

			// Is this node of type node?
			if ($this->nodeType == XML_TYPE_NODE) {

				// Set current lastChild's nextSibling to this child
				if (!is_null($this->lastChild)) {
					$this->lastChild->nextSibling =& $child;
				}

				// Now (re)set firstChild and lastChild
				$this->firstChild =& $this->childNodes[0];
				$this->lastChild =& $child;
			}

		} // End appendChild

		// Creates a new XML element with the name specified in the argument.
		function createElement($name) {
			$tmp = new XMLNode();
			$tmp->nodeName = $name;
			return $tmp;
		} // End createElement

		// Creates a new XML text node with the specified text.
		function createTextNode($value) {
			$tmp = new XMLText();
			$tmp->nodeValue = trim($value);
			return $tmp;
		} // End createTextNode

		// Returns true if there are child nodes; otherwise, returns false.
		function hasChildNodes() {
			return !is_null($this->childNodes);
		} // End hasChildNodes

		// Inserts a new child node into the XML object's child list, before the
		//		provided node
		function insertBefore(&$child, $refChild = null) {
			// Not implemented yet...
		} // End insertBefore

		// Removes the specified XML object from its parent.
		function removeChild() {
			// Not implemented yet...
		} // End removeNode

		// Evalutes the specified XML object, constructs a textual representation
		//		of the XML structure including the node, children and attributes, and
		//		returns the result as a string.
		function toString() {

			$tagOpen = "<";
			$tagClose = ">";
			$tagBreak = "";

			// Set xml-decleration and doc-type if this is the root-element
			$retVal = "";
			if (is_null($this->parentNode)) {
				$retVal .= $this->xmlDecl;
				$retVal .= $this->docTypeDecl;
			}

			// If this element has attributes, gather them
			$sAttr = "";
			if (isset($this->attributes)) {
				foreach ($this->attributes as $key=>$val)
					$sAttr .= " $key=\"$val\"";
			}

			if (isset($this->nodeName)) {
				if ($this->hasChildNodes()) {
					$retVal .= $tagOpen.$this->nodeName.$sAttr.$tagClose.$tagBreak;
				} elseif (isset($this->firstChild->nodeValue)) {
					$retVal .= $tagOpen.$this->nodeName.$sAttr.$tagClose.$this->firstChild->nodeValue.$tagOpen."/".$this->nodeName.$tagClose.$tagBreak;
				} else {
					$retVal .= $tagOpen.$this->nodeName.$sAttr." /".$tagClose.$tagBreak;
				}
			}

			if ($this->hasChildNodes()) {
				foreach ($this->childNodes as $child) {
					switch ($child->nodeType) {
						case XML_TYPE_NODE: // node
						default:
							$retVal .= $child->toString();
							break;
						case XML_TYPE_TEXT: // text
							$retVal .= $child->nodeValue;
							break;
						case XML_TYPE_CDATA: // CData
							$retVal .= "<![CDATA[".$child->nodeValue."]]>";
							break;
					}
				}
			}

			if ($this->hasChildNodes() && isset($this->nodeName)) {
				$retVal .= $tagOpen."/".$this->nodeName.$tagClose.$tagBreak;
			}

			return $retVal;

		} // End toString

	} // End XMLNode class


	// Main XML class ==========================================================
	class XML extends XMLNode {

		// Public properties ----------------------------------------------------

		// Some vars for error tracking and messages
		var $status;
		var $error;

		// XML version
		var $version;

		// XML character encoding
		var $encoding;

		// Indicates the MIME type transmitted to the server.
		var $contentType;

		// Property (r/w); information about the XML document DOCTYPE decleration.
		var $docTypeDecl;

		// Property (r/w); sets and returns information about a document's XML decleration.
		var $xmlDecl;

		// XML constructor ------------------------------------------------------
		function XML($url = '') {

			// Init external properties
			parent::XMLNode();
			$this->status = 0;
			$this->error = '';
			$this->version = '1.0';
			$this->encoding = 'iso-8859-1';		// Iso: lowercased the encoding
			$this->contentType = 'text/xml';
			$this->docTypeDecl = '';
			$this->xmlDecl = '';

			// Load the referenced XML document
			$this->load($url);

		} // End XML-constructor

		// Public methods -------------------------------------------------------

		// Loads an XML document from the specified URL.
		function load($url) {
			if (empty($url)) return false;
			$this->parseXML(implode('', @file($url)));
		} // End load

		// Parses the XML text specified in the source argument.
		function parseXML($source) {

			// Clear any content that this object might have
			// Call: $this->removeNode()

			// Get xml declration from document and set in object
			if (preg_match("/<?xml\ (.*?)\?>/i", $source, $matches)) {
				$this->xmlDecl = "<?xml ".$matches[1]."?>";

				// Get version
				if (preg_match("/version=\"(.*?)\"/i", $matches[1], $versionInfo)) {
					$this->version = $versionInfo[1];
				}

				// Get encoding
				if (preg_match("/encoding=\"(.*?)\"/i", $matches[1], $encodingInfo)) {
					$this->encoding = $encodingInfo[1];
				}

			}

			// Get document type decleration from document and set in object
			if (preg_match("/<!doctype\ (.*?)>/i", $source, $matches)) {
				$this->docTypeDecl = "<!DOCTYPE ".$matches[1].">";
			}

			// Strip white space between tags - not _in_ tags
			$source = preg_replace("/>\s+</i", "><", $source);

			// Parse the xml document to an array structure
			$parser = xml_parser_create($this->encoding);
			xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
			xml_parse_into_struct($parser, $source, $vals, $index);
			xml_parser_free($parser);

			// parse the structure and create this object...
			if (!empty($vals)) {
				$root = XMLNode::createElement($vals[0]['tag']);
				$root->attributes = isset($vals[0]['attributes'])?$vals[0]['attributes']:null;
				// Iso: Make sure all the attributes are properly escaped
				if (!empty($root->attributes)) {
					foreach ($root->attributes as $attr => $value) {
						$root->attributes[$attr] = htmlspecialchars($value);
					}
				}
				$root->childNodes = $root->_xml_get_children($vals, $i = 0);
				$this->appendChild($root);
			}

		} // End parseXML

		// Encodes the specified XML object into a XML document and sends
		//		it to the specified URL using the POST method.
		//		$url is of the form: (http://)www.domain.com:port/path/to/file
		function send($url) {

			// Get xml document
			$strXML = $this->toString();

			// Get url parts
			if (!preg_match("/http/", $url)) $url = "http://".$url;
			$urlParts = parse_url($url);
			$host = isset($urlParts['host'])?$urlParts['host']:'localhost';
			$port = isset($urlParts['port'])?$urlParts['port']:80;
			$path = isset($urlParts['path'])?$urlParts['path']:"/";

			// Open a connection with the required host
			$fp = fsockopen($host, $port, $errno, $errstr);
			if (!$fp) {
				$this->status = -11;
				$this->error = "Unable to connect to $host at port $port: ($errno) $errstr";
				return false;
			}

			// Send the xml document
			fputs($fp, "POST ".$path." HTTP/1.0\r\n".
				"Host: ".$host."\r\n".
				"Content-length: ".strlen($strXML)."\r\n".
				"Content-type: ".$this->contentType."\r\n".
				"Connection: close\r\n\r\n".
				$strXML."\r\n");

			return $fp;

		} // End send

		// Encodes the specified XML object into a XML document, sends
		//		it to the specified URL using the POST method, downloads the server's
		//		response and then loads it into the target.
		//		$url is of the form: (http://)www.domain.com:port/path/to/file
		function sendAndLoad($url, &$target) {

			// Check target type, fail on wrong type
			if (gettype($target) != 'object') {
				$this->status = -10;
				$this->error = "Target is of type '".gettype($target)."', but should be 'object'";
				return false;
			}

			// Send the xml document
			if (!$fp = $this->send($url)) return false;

			// Recieve response
			$buf = "";
			while (!feof($fp)) $buf .= fread($fp, 128);
			fclose($fp);

			// Filter xml out response (dump http headers)
			if (!preg_match("/(<.*>)/msi", $buf, $matches)) { // Greedy match
				$this->status = -12;
				$this->error = "Unidentified server response: no xml was sent";
				return false;
			}
			$xmlResponse = $matches[1];
			$target->parseXML($xmlResponse);

			return true;

		} // End sendAndLoad

	} // End XML class

?>
