<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * handlerOpml.php - OPML content handler.                            *
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
 * OPML - Outline Processor Markup Language
 *
 * As defined by the OPML 1.0 specification located at
 * http://opml.scripting.com/spec
 *
 * Plus typical extensions as "documented" in the Yahoo opml-dev group
 *
 */
 
 
class OpmlHandler extends BaseClassHandler {

	// OPML specific variables
	var $root;			// Reference to OpmlEntry


	var $line = 0;
	var $trans;

	function parseContent(&$dom) {
		global $req;
		
		$req->addMsg("OpmlHandler->parseContent():");
		#$req->addMsg("OpmlHandler->parseContent(): raw=" . $dom->toString());

		$opmlNode = $dom->firstChild->firstChild;
		$req->addMsg("OpmlHandler: 1. " . $opmlNode->nodeName);
		if ($opmlNode->nodeName=="opml") {
			$this->root = new OpmlEntry();
			$this->root->version = $opmlNode->attributes["version"];
		
			$childNode = $opmlNode->firstChild;
			
			while ($childNode) {
				$req->addMsg("OpmlHandler: 2. " . $childNode->nodeName);
				
				if ($childNode->nodeName=="head") {
					$headNode = $childNode->firstChild;
					while($headNode) {
						$req->addMsg("OpmlHandler: 3. " . $headNode->nodeName);
						
						switch ($headNode->nodeName) {
							case "title":
								$this->root->title = $headNode->firstChild->nodeValue;
								break;
							case "dateCreated":
								$this->root->created = $headNode->firstChild->nodeValue;
								break;
							case "dateModified":
								$this->root->modified = $headNode->firstChild->nodeValue;
								break;
						}
					
						$headNode = $headNode->nextSibling;
					}
				
				} elseif ($childNode->nodeName=="body") {
					$this->root->outlines[0] = new OpmlOutline();
					$this->recurseOutline($childNode->childNodes);
				} else {
					$req->addMsg("OpmlHandler: Element not supported (" . $childNode->nodeName . ")");
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
			##$req->addMsg("OpmlHandler: " . $this->line . "(" . $parent . ") " . $depth . "." . $i . ". " . $node->nodeName . "=[" . $node->attributes["text"] . "][" . count($node->childNodes) . "]");
			
			$tempOut = new OpmlOutline();
			
			$tempOut->text      = $node->attributes["text"];
			$tempOut->type      = $node->attributes["type"];
			
			if ($node->attributes["isComment"]=="true") {
				$tempOut->isComment = true;
			}
			if ($node->attributes["isBreakpoint"]=="true") {
				$tempOut->isBreakpoint = true;
			}
			
			// Keep hold of any other attributes - @type is the extension mechanism
			$tempOut->attributes = $node->attributes;


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
		}
	}
	

	/* function insertContentInDom(&$dom) {
	
	}
	
	function getContent() {
	
	
		return $text;
	} */
	
	function getContentAsHtml() {
		global $req;
		
		$req->addMsg("OpmlHandler->getContentAsHtml():");

		$headLevel=1;
		$html = "";
		$list = false;		// list flag
		
		if (empty($this->trans)) {
			$this->trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
		}

		##$html .= "<hr />\n\n";
		##$html .= "<h1>" . $this->root->title . "</h1>\n\n";
		
		$sum = count($this->root->outlines);
		for ($i=1; $i < $sum; $i++) {
			$node = $this->root->outlines[$i];
			
			$tag="p";
			if ($node->isComment) {
				$tag="p";
				if ($list) {
					$html .= "</ul>\n";
					$list = false;
				}
			} elseif ($this->root->outlines[$node->parentNo]->isComment) {
				$tag = "li";
				if (!$list) {
					$html .= "<ul>\n";
					$list = true;
				}
			} else {
				$tag="h" . ($headLevel + $node->depth);
				if ($list) {
					$html .= "</ul>\n";
					$list = false;
				}
			}

			$html .= "<" . $tag . ">" . strtr($node->text, $this->trans) . "</" . $tag . ">\n";
		
		}
		

		return $html;
	}
	
}

class OpmlEntry {
	var $opmlVersion = "";
	var $title       = "";
	var $created     = "";
	var $modified    = "";
	
	var $ownerName   = "";
	var $ownerEmail  = "";
	
	var $outlines    = array();		// complete list of outline elements
}

class OpmlOutline {

	// outline element attributes typical for a non-extended OPML document
	var $text         = "";
	var $type         = "";
	var $isComment    = false;
	var $isBreakpoint = false;

	// @type is the extension mechanism - keep a copy of the DOM attributes
	var $attributes;

	var $lineNo       = 0;			// outline line number
	var $parentNo     = 0;			// outline line number of parent
	var $childNodes   = array();	// array of children line numbers
	var $depth        = 0;			// current depth of current line
}

?>