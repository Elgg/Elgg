<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * handlerBaseClass.php - Abstract content handler. All mime-type     *
 *     handlers extend this class and reimplement its methods.        *
 *     Implements the mimeTypeHandlerAPI                              *
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

 
class BaseClassHandler {

	var $container;		// Reference to a generic content container
	var $containerType;	// Type of container. text, obj

	function parseContent(&$dom) {
		global $req;
		$req->addMsg("BaseClassHandler: parseContent not implemented yet");
	}
	
	function insertContentInDom(&$dom) {
		global $req;
		$req->addMsg("BaseClassHandler: insertContentInDom not implemented yet");
	}
	
	function getContent() {
		global $req;
		$req->addMsg("BaseClassHandler: getContent not implemented yet");
		return $text;
	}
	
	function getContentAsHtml() {
		global $req;
		$req->addMsg("BaseClassHandler: getContentAsHtml not implemented yet");
		return $html;
	}

}



?>