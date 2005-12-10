<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * handlerHtml.php - HTML content handler.                            *
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

class HtmlHandler extends BaseClassHandler {

	var $html = "";
	var $trans;

	function parseContent(&$dom) {
		global $req;
		
		$dom = $dom->firstChild;
		$req->addMsg("HtmlHandler->parseContent: Parsing Content [" . $dom->toString() . "]");
		$req->addMsg("HtmlHandler->parseContent: Parsing Content [" . $dom->nodeName . "][" . $dom->nodeValue . "]");
		
		$childNode = $dom->firstChild;
		while ($childNode) {
			$req->addMsg("HtmlHandler->parseContent: Parsing Node [" . $childNode->nodeName . "][" . $childNode->nodeValue . "]");
			$this->html .= $childNode->nodeValue . "\n";
		
			$childNode = $childNode->nextSibling;
		}
		$req->addMsg("HtmlHandler->parseContent: Finished [" . $this->html . "]");
	
	}
	
	/* function insertContentInDom(&$dom) {
	
	}
	
	function getContent() {
	
	
		return $text;
	} */
	
	function getContentAsHtml() {
		global $req;
		
		if (empty($this->trans)) {
			$this->trans = array_flip(get_html_translation_table(HTML_SPECIALCHARS));
		}
	

		return strtr($this->html, $this->trans);
	}

}



?>