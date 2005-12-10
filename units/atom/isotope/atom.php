<?php 

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * atom.php - the main entry point for Atom service handler           *
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


	include_once("atomConfig.php");
	include_once("atomServices.php");
	include_once("atomServicesOverlay.php"); // Virtual overlay updated by the admin service
	include_once("userConfig.php");
	
	include_once("atomRequest.php");
	include_once("atomFunc.php");
	include_once("atomClasses.php");
	include_once("atomDom.php");
	include_once("atomHtml.php");
	
	/* Elgg overrides */
	include_once("atomElggConfig.php");
	
	/* Third party classes */
	include_once('lib/lib.xml.inc.php');	

	// Initialise the request object
	$req = new Request();
	$req->addMsg("atom: Request object created");



	// From the service info, instantiate the requested Atom service
	// Each service initialises its own Storage object.
	if ($req->service) {
		if ($services[$req->service]) {	
			$service = getAtomServiceHandler($req->service);
			$service->init($req->service);
		} else {
			$req->addMsg("atom: ERROR: Service " . $req->service . " not defined.");
			returnPageNotFound("No Atom service named " . $req->service . " is configured");
		}
	} else {
		$req->addMsg("atom: ERROR: No service specified.");
		returnServerError("No Atom service has been specified.");
	}

	// Based on the request method call the right function:
	switch($req->method) {
		case "GET":
			$req->addMsg("atom: Get method");
			$service->doGet();
			break;
		case "POST":
			$req->addMsg("atom: Post method");
			$service->doPost();
			break;
		case "PUT":
			$req->addMsg("atom: Put method");
			$service->doPut();
			break;
		case "DELETE":
			$req->addMsg("atom: Delete method");
			$service->doDelete();
			break;
		case "OPTIONS":
			$req->addMsg("atom: Options method");
			$service->doOptions();
			break;
	}

	
	$req->addMsg("atom: Request completed");
//	atomLog();
	
?>
