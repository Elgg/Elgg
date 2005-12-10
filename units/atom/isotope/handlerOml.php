<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * handlerOml.php - OML content handler.                              *
 *                Implements the mimeTypeHandlerAPI                   *
 * Copyright (c) 2004  Michael Davies (Isofarro).                     *
 *                                                                    *
 **********************************************************************
 *                                                                    *
 * This program is free software; you can redistribute it and/or      *
 * modify it under the terms of the GNU General Public License as     *
 * published by the Free Software Foundation; either version 2 of the *
 * License, or (at your option) any later version.                    *
 *                                                                    *
 * This program is distributed in the hope that it will be useful,    *
 * but WITHOUT ANY WARRANTY; without even the implied warranty of     *
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the      *
 * GNU General Public License for more details.                       *
 *                                                                    *
 * You should have received a copy of the GNU General Public License  *
 * along with this program; if not, write to the                      *
 *     Free Software Foundation, Inc.                                 *
 *     59 Temple Place, Suite 330                                     *
 *     Boston, MA 02111-1307 USA                                      *
 *                                                                    *
 *********************************************************************/


include_once("handlerBaseClass.php");

/*
 * OML - Outline Markup Language
 *
 * As defined by the OML 1.0 specification located at
 * http://oml.sourceforge.net/cgi-bin/blosxom.cgi/specification
 *
 *
 */
 
 
class OmlHandler extends BaseClassHandler {

	var $root;			// Reference to OmlEntry
	var $line = 0;
	var $trans;

	function parseContent(&$dom) {
		global $req;

		$req->addMsg("OmlHandler->parseContent():");
		$omlNode = $dom->firstChild->firstChild;
		$req->addMsg("OmlHandler: 1." . $omlNode->nodeName);
		if ($omlNode->nodeName=="oml") {
			$this->root = new OmlEntry();
			$this->root->version = $omlNode->attributes["version"];

			$childNode = $omlNode->firstChild;
			
			while ($childNode) {
				$req->addMsg("OmlHandler: 2. " . $childNode->nodeName);
				
				if ($childNode->nodeName=="head") {
					$headNode = $childNode->firstChild;
					while($headNode) {
						$req->addMsg("OmlHandler: 3. " . $headNode->nodeName);
						
						if ($headNode->nodeName=="metadata") {
							if ($headNode->attributes["name"]) {
								$this->root->metadata[$headNode->attributes["name"]] = $headNode->firstChild->nodeValue;
							}
						}
						$headNode = $headNode->nextSibling;
					}
				} elseif ($childNode->nodeName=="body") {
					$this->root->outlines[0] = new OmlOutline();
					$this->recurseOutline($childNode->childNodes);
				} else {
					$req->addMsg("OmlHandler: Element not supported (" . $childNode->nodeName . ")");
				}
				
				$childNode = $childNode->nextSibling;


			}
			
		}

		##var_dump($this->root);
		
	}

	function recurseOutline($childArray, $depth=1, $parent=0) {
		global $req;

		static $outArr = array();

		$len = count($childArray);
		for ($i=0; $i < $len; $i++) {
			$this->line++;
			$node = $childArray[$i];
			$req->addMsg("OmlHandler: " . $this->line . "(" . $parent . ") " . $depth . "." . $i . ". " . $node->nodeName . "=[" . $node->attributes["text"] . "][" . count($node->childNodes) . "]");
			
			if ($node->nodeName=="outline") {
				#$req->addMsg("OmlHandler: processing outline node");
			
				$tempOut = new OmlOutline();

				$tempOut->text      = $node->attributes["text"];
				$tempOut->created   = $node->attributes["created"];
				$tempOut->modified  = $node->attributes["modified"];
				$tempOut->type      = $node->attributes["type"];
				$tempOut->url       = $node->attributes["url"];



				// Some placeholding info 
				// (since creating a nested array on the fly using recursion is too difficult)
				$tempOut->lineNo   = $this->line;
				$tempOut->parentNo = $parent;
				$tempOut->depth    = $depth;
				$this->root->outlines[$this->line] = $tempOut;

				// Populate parent child-array with this index number
				array_push($this->root->outlines[$parent]->childNodes, $this->line);


				if ($node->childNodes > 0) {
					$this->recurseOutline($node->childNodes, $depth+1, $this->line);
				}
			} else if ($node->nodeName=="data") {
				#$req->addMsg("OmlHandler: processing data node");
				
				$tempData = new OmlData();
				$tempData->text = $node->firstChild->nodeValue;
				$this->root->outlines[$this->line] = $tempData;
				
				// Populate parent child-array with this index number
				array_push($this->root->outlines[$parent]->childNodes, $this->line);
			
			} else if ($node->nodeName=="item") {
				#$req->addMsg("OmlHandler: processing item node");
			
				$tempItem = new OmlItem();
				$tempItem->name  = $node->attributes["name"];
				$tempItem->value = $node->firstChild->nodeValue;
				$this->root->outlines[$this->line] = $tempItem;
				
				// Populate parent child-array with this index number
				array_push($this->root->outlines[$parent]->childNodes, $this->line);
			}
		}
	}
	
/*	function insertContentInDom(&$dom) {
		global $req;
		$req->addMsg("BaseClassHandler: insertContentInDom not implemented yet");
	}
	
	function getContent() {
		global $req;
		$req->addMsg("BaseClassHandler: getContent not implemented yet");
		return $text;
	}
	
*/
	function getContentAsHtml() {
		global $req;
		
		$req->addMsg("OmlHandler->getContentAsHtml():");

		$headLevel=1;
		$html = "";
		
		if (empty($this->trans)) {
			$this->trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
		}

		#$html .= "<hr />\n\n";
		#$html .= "<h1>" . $this->root->metadata["title"] . "</h1>\n\n";
		

		
		//Parse the array backwards and use a temporary stack. Then we don't need recursion!
		$revout = array_reverse($this->root->outlines, TRUE);
		$tempOut = array();
		
		foreach($revout as $i => $node) {
			$tmp = "";
			// Each node in the document needs to be HTMLised then dumped 
			// on the temporary stack. Outlines are the only things
			// that can remove items from the stack.
			if (is_a($node, "OmlOutline")) {
				$req->addMsg($i . ": Outline");
				if ($node->text) {
					$tmp = $node->text;
				} else {
					#$tmp = "untitled";
				}
				if ($node->url) {
					$tmp = "<a href=\"" . $node->url . "\">" . $tmp . "</a>";
				}
				if ($node->depth && $node->text) {
					$tag = "h" . ($headLevel + $node->depth);
					$tmp = "<" . $tag . ">" . $tmp . "</" . $tag . ">";
				}
				$tmp .= "\n";
				
				foreach ($node->childNodes as $child) {
					$req->addMsg("\t" . $child . "[" .$tempOut[$child] . "]");
					if ($tempOut[$child]) {
						$tmp .= $tempOut[$child];
						unset($tempOut[$child]);
					}
				}
				
				if (!$node->text && $i!=0) {
					$tmp = "<div>" . $tmp . "</div>";
				}
				#$req->addMsg($i . " heaped: [" .$tmp. "]");
				$tempOut[$i] = "\n" . $tmp;
			
			} else if (is_a($node, "OmlData")) {
				$req->addMsg($i . ": Data");
				$tempOut[$i] = "<p>" . $node->text . "</p>\n";			
			} else if (is_a($node, "OmlItem")) {
				$req->addMsg($i . ": Item");
				$tempOut[$i] = "<p><strong>" . $node->name . "</strong>: " . $node->value . "</p>\n";			
			}
			
		}
		##var_dump($tempOut);
		$html .= $tempOut[0];
		$html .= "\n\n\n\n";

		return $html;
	}
	
}

class OmlEntry {
	var $omlVersion    = "";
	var $metadata      = array();
	
	var $outlines      = array();

}


class OmlOutline {

	// typical Oml attributes (all optional)
	var $text       = "";
	var $created    = "";
	var $modified   = "";
	var $type       = "";
	var $url        = "";
		
	// Child elements (Index number of their positions)
	var $childNodes = array();
	
	// working vars
	var $lineNo    = 0;
	var $parentNo  = 0;
	var $depth     = 0;
}

class OmlData {
	var $text = "";
}

class OmlItem {
	var $name  = "";
	var $value = "";
}

?>