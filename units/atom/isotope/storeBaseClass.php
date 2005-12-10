<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * storeBaseClass.php - Abstract storage class for Atom entries. All  *
 *     storage classes should extend this class and reimplement its   *
 *     methods.                                                       *
 *     Defines the storeAPI                                           *
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

class BaseClassStorage {

	function init($config) {
		global $req;
		$req->addMsg("BaseClassStorage: init not implemented yet");
		
	}


	function getAtomEntry($entryId) {
		global $req;
		$req->addMsg("BaseClassStorage: getAtomEntry not implemented yet");
		return $entry;
	}


	function storeAtomEntry(&$entry) {
		global $req;
		$req->addMsg("BaseClassStorage: storeAtomEntry not implemented yet");
	}


	function deleteAtomEntry($entryId) {
			global $req;
			$req->addMsg("BaseClassStorage: deleteAtomEntry not implemented yet");
	}


	function getAtomEntries($first=10) {
		global $req;
		$req->addMsg("BaseClassStorage: getAtomEntries not implemented yet");
		return $feed;
	}


	function getAtomComment($blogId, $commentId) {
		global $req;
		$req->addMsg("BaseClassStorage: getAtomComment not implemented yet");
		return $comment;
	}


	function storeAtomComment($blogId, &$comment) {
		global $req;
		$req->addMsg("BaseClassStorage: storeAtomComment not implemented yet");
	}


	function deleteAtomComment($blogId, $commentId) {
		global $req;
		$req->addMsg("BaseClassStorage: deleteAtomComment not implemented yet");
	}


	function getAtomComments($entryId) {
		global $req;
		$req->addMsg("BaseClassStorage: getAtomComments not implemented yet");
		return $feed;
	}

}

?>