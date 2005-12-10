<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * serviceAdmin.php - PHPAtomAPI administration service.              *
 *                Implements the serviceAPI                           *
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

 
include_once("serviceBaseClass.php");

class AdminService extends BaseClassService {

	// Generic service variables
	var $serviceName;
	var $store;


	/*
	 * Initialise the service
 	 */
	function init($serviceName) {
		global $req;
		
		$this->initUrl();
		$this->serviceName = $serviceName;
	}


	/*
	 * doGet - handle the HTTP GET methods
	 */
	function doGet() {
		global $req;
		
		if ($req->atomResource["service"]) {
			// A service or child of service requested
			$req->addMsg("adminService: Configuration details of service requested");
		
			$service = $this->getServiceAsAtomEntry($req->atomResource["service"]);
			
			$entryDom = atomEntryToXml($service);
		
			$req->addMsg("Dom of entry:\n" . $entryDom->toString());

			setStatusCode("200 Ok");
			setContentType("text/xml");

			print $entryDom->toString();

		} else {
			// Admin root requested - return list of configurable services as a feed
			$req->addMsg("adminService: List of configurable services requested");
			
			$feed = $this->getServicesAsAtomFeed();
			$feedDom = atomFeedAsXml($feed);

			$req->addMsg("Dom of entry:\n" . $feedDom->toString());

			setStatusCode("200 Ok");
			setContentType("text/xml");

			print $feedDom->toString();
			
		}
	}


	/*
	 * doPost - handle the HTTP POST methods
	 */
	function doPost() {
		global $req;
		$req->addMsg("AdminService: doPost not implemented yet");
	}


	/*
	 * doPut - handle the HTTP PUT methods
	 */
	function doPut() {
		global $req;
		$req->addMsg("AdminService: doPut not implemented yet");
	}


	/*
	 * doDelete - handle the HTTP DELETE methods
	 */
	function doDelete() {
		global $req;
		$req->addMsg("AdminService: doDelete not implemented yet");
	}


	/*
	 * doOptions - handle the HTTP OPTIONS methods
	 */
	function doOptions() {
		global $req;
		$req->addMsg("AdminService: doOptions not implemented yet");
	}

	/**************************************************
	 *
	 * Local Atom Blog service functions
	 *
	 *************************************************/


	/*
	 * Function to initialise the Url directory object 
	 * which is used to define the request. 
	 */
	function initUrl() {
		global $req, $rootUri;
		
		$pageUri = $req->serviceRoot;
		$atomResource = array();
		
		if ($req->breadcrumb) {
			foreach ($req->breadcrumb as $key) {
				if ($atomResource["service"]) {
					if ($atomResource["config"]) {
						// must be more than two levels deep
						// so lets ignore it
					} else {
						// The URL specifies a comment
						$atomResource["config"] = $key;
						$pageUri .= "/" . $key;
					}
				} else {
					// The URL specifies a blog entry
					$atomResource["service"] = $key;								
					$pageUri .= $key;
				}
			}
			
			$req->atomResource = $atomResource;
			$req->pageUri      = $pageUri;
		}

	}
	
	
	/*
	 * Return a list of services as an AtomFeed object
	 *
	 */
	function getServicesAsAtomFeed() {
		global $services, $atomDomain, $urlPrefix, $rootUri;
		
		$feed = new AtomFeed();
		foreach($services as $name => $config) {
			if (!($config["editable"] == "false")) {
				$atomEntry = new AtomFeedEntry();

				$atomEntry->id       = createIdTag($name);
				$atomEntry->created  = timestampToW3Date();
				$atomEntry->modified = $atomEntry->created;
				$atomEntry->link     = $urlPrefix . $rootUri . $this->serviceName . "/" . $name;
				$atomEntry->title    = $config["title"];
				$atomEntry->issued   = timestampToW3Date();

				array_push($feed->entries, $atomEntry);
			}
		}
		
		return $feed;
	}
	
	/*
	 * Return configuration of a service as an AtomEntry
	 *
	 */
	function getServiceAsAtomEntry($servName) {
		global $services, $req, $urlPrefix, $rootUri, $mimeType, $atomDomain;
		
		if ($services[$servName]) {
			$req->addMsg("getServicesAsAtomEntry: " . $services[$servName]);
			$service = $services[$servName];
			$req->addMsg("getServicesAsAtomEntry: " . $service);
			$entry = new AtomEntry();
			
			$titleObj = new AtomContent();
			$titleObj->type = "text/plain";
			$titleObj->mode = "xml";
			$titleObj->text = "Administration: " . $service["title"];

			$req->addMsg("getServicesAsAtomEntry: " . $service["title"]);
			
			$entry->title  = $titleObj;
			
			$link = new AtomLink();
			$link->rel  = "service.edit";
			$link->type = $mimeType["atom"];
			$link->href = $urlPrefix . $rootUri . $this->serviceName . "/" . $req->atomResource["service"];
			
			array_push($entry->link, $link);
			
			$auth = new AtomPerson();
			$auth->name="System Generated";
			$entry->author = $auth;
			
			$entry->id     = createIdTag($servName);
			//$entry->issued =
			
			$contObj = new AtomContent();
			$contObj->type      = "text/plain";
			$contObj->mode      = "xml";

			$content = "";
			foreach($services[$servName] as $name => $value) {
				$content .= $name . "=" . $value . "\n";
			}
			
			$container = new XML();
			$root = $container->createElement('content');
			$root->appendChild($container->createTextNode($content));
			$container->appendChild($root);
			
			
			$contObj->container = $container;
			
			array_push($entry->content, $contObj);
			
			return $entry;
		}
		
		$req->addMsg("getServiceAsAtomEntry: no service called " . $servName);
		returnPageNotFound("No service called " . $servName . " available to administration service.");
	}
	

}

?>