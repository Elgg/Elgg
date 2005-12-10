<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * serviceBaseClass.php - Abstract Atom service class. All            *
 *     service classes should extend this class and reimplement its   *
 *     methods.                                                       *
 *     Defines the serviceAPI                                         *
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

class BaseClassService {

	// Generic service variables
	var $serviceName;
	var $store;

	/*
	 * Initialise the service
 	 */
	function init($serviceName) {
		global $req;
		$req->addMsg("BaseClassService: init not implemented yet");
	}


	/*
	 * doGet - handle the HTTP GET methods
	 */
	function doGet() {
		global $req;
		$req->addMsg("BaseClassService: doGet not implemented yet");
	}


	/*
	 * doPost - handle the HTTP POST methods
	 */
	function doPost() {
		global $req;
		$req->addMsg("BaseClassService: doPost not implemented yet");
	}


	/*
	 * doPut - handle the HTTP PUT methods
	 */
	function doPut() {
		global $req;
		$req->addMsg("BaseClassService: doPut not implemented yet");
	}


	/*
	 * doDelete - handle the HTTP DELETE methods
	 */
	function doDelete() {
		global $req;
		$req->addMsg("BaseClassService: doDelete not implemented yet");
	}


	/*
	 * doOptions - handle the HTTP OPTIONS methods
	 */
	function doOptions() {
		global $req;
		$req->addMsg("BaseClassService: doOptions not implemented yet");
	}
	
	
}

?>