<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * handlerPlainText.php - Plain text content handler.                 *
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

class PlainTextHandler extends BaseClassHandler {

	var $containerType="text";

	function parseContent(&$dom) {
		global $req;
		
		$req->addMsg("PlainTextHandler->parseContent(): type=" . $dom->attributes["type"] . "; mode=" . $dom->attributes["mode"] . ";");
		$req->addMsg("PlainTextHandler->parseContent(): raw=" . $dom->toString());
		
		$text = "";
		
		$childNode = $dom->firstChild->firstChild;
		while ($childNode) {
			$text .= $childNode->nodeValue;
		
			$childNode = $childNode->nextSibling;
		}
		$req->addMsg("PlainTextHandler->parseContent(): parsed=" . $text);
		
		$this->container = $text;
		
	}
	
	function insertContentInDom(&$dom) {
		global $req;
		
		$req->addMsg("PlainTextHandler->insertContentInDom: ");
		$dom->appendChild($dom->createTextNode($container));
	}
	
/* 	function getContent() {
	
	
		return $text;
	} */
	
	function getContentAsHtml() {
		$textArr = explode("\n", $this->container);
		$html = "";
		
		foreach($textArr as $line) {
			if ($line) {
				$html .= "<p>" . $line . "</p>\n\n";	
			}
		}

		return $html;
	}

}



?>