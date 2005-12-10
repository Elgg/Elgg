<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * rest.php - Atom Rest Request Handler.                              *
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



	// Extract what's needed from the HTTP request.
	class Request {

		// DOM representation of the Entity Body
		var $dom;
		var $query;

		// Url Paths
		var $urlPrefix;
		var $rootUri;
			

		// HTTP info
		var $method;
		var $contentType;

		// Requested / derived return content type
		var $extension;
		var $responseType;


		// Array to hold accepted Mime-types
		// Might be better as an intelligent object
		var $accept = array();


		// Service URLs
		var $service;
		var $breadcrumb = array();
		var $atomResource;
		var $serviceRoot = "/";
		var $pageUri;		// Precalced by linkObj
		var $blogEntry;		// DEPRECATED
		var $blogComment;	// DEPRECATED
		
		
		// debugging tools
		var $msg = array();


		
		function Request() {
			global $_SERVER, $urlPrefix, $rootUri;

			// Generic Rest request init
			$this->initialiseRestRequest();
			
			// Atom service initialisation
			$this->rootUri   = $rootUri;
			$this->urlPrefix = $urlPrefix;

			// Break the request URL into a breadcrumb array			
			$this->splitRequestUri(strip_tags($_SERVER["REQUEST_URI"]));


			$this->parseContentType($_SERVER["CONTENT_TYPE"]);
			$this->getReturnType();
		}

		function parseContentType($contType) {
			#$this->addMsg("Request.parseContentType: Content-Type received: " . $contType);
			$this->contentType = $contType;
		}
		
		function getReturnType() {
			global $_SERVER, $outputTypeList, $mimeType;
			
			if ($this->extension) {
				$this->responseType = $mimeType[$this->extension];
				$this->addMsg("Request.getReturnType: Return type enforced by URL: " . $this->responseType);
			} elseif($_SERVER["HTTP_ACCEPT"]) {
				$this->addMsg("Request.getReturnType: Examining Accept header.");
				$this->responseType = $this->parseAccept($_SERVER["HTTP_ACCEPT"]);
				$this->addMsg("Request.getReturnType: Best match: [" . $this->responseType . "]");
			} elseif ($outputTypeList[$this->contentType]) {
				$this->responseType = $this->contentType;
			}
		}
	
		function parseAccept($acceptString) {
			global $outputTypeList;
			
			
			$this->addMsg("Request.parseAccept: " . $acceptString);
			
			if (!empty($acceptString)) {
				$bestMatch = "";
				$bestScore = 0;

				// Accepted mime-types are comma delimited, so split there.
				$accept2 = explode(",", $acceptString);
				foreach($accept2 as $acc) {
					
					#$this->addMsg("Request.parseAccept: Given : " + $acc);

					$q = 0;
					$accept3 = explode(";q=", $acc);
					$acc3len = count($accept3);
					if($acc3len==2) {
						// There is a weighting (q) on the accepted mime type
						$q = $accept3[1];		
					} elseif ($acc3len==1) {
						// There is no weighting on the accepted mime type - default to 1
						$q = 1;						
					}

					##$this->addMsg("Request.parseAccept: Parsed: [" . $accept3[0] . "]\t" . $q);
					
					if ($outputTypeList[$accept3[0]]) {
						$this->addMsg("Request.parseAccept: Acceptable mime: [" . $accept3[0] . "][" . $q . "]");
						$this->accept[$accept3[0]] = $q;
						
						if ($q > $bestScore) {
							$bestMatch = $accept3[0];
							$bestScore = $q;
						}
					}
				}
				return $bestMatch;
			}
			
		}

		/*
		 * Doing a generic rest initialisation. On POST and PUT methods
		 * there will be an XML payload that needs to be captured.
		 */
		function initialiseRestRequest() {
			global $_SERVER, $_GET;
			
			$this->query   = $_GET;
			$this->method  = $_SERVER["REQUEST_METHOD"];
			if ($this->method == "POST" || $this->method == "PUT") {
				$xmlstring = file_get_contents("php://input");
				if (!empty($xmlstring)) {
					// Create XML DOM from input
					$this->dom = new XML();
					$this->dom->parseXML($xmlstring);
				}
			}
		}

		/*
		 * This routine takes the requestURL and breaks it down
		 * to identify the requested service, and saving proceeding info
		 * as a series of breadcrumb directory / resources
		 */
		function splitRequestUri($reqUrl) {
			global $rootUri, $atomRootUrl, $outputType, $mimeType;
			
			// Ignore the query string
			$newVar = explode("?", $reqUrl);
			$reqUrl = $newVar[0];

			// See if the $reqUrl contains an extension, and store that
			$this->addMsg("Request.splitRequestUri: " . $reqUrl);
			$pos = strrpos($reqUrl, ".");
			if (!($pos === false)) {
				##$this->addMsg("Request.splitRequestUri: Extension found in: " . $reqUrl);
				$this->extension = substr($reqUrl, $pos+1);
				$reqUrl = substr($reqUrl, 0, $pos);
				$this->addMsg("Request.splitRequestUri: Extension found: [" . $reqUrl . "][" . $this->extension . "]");
			}

			if ($atomRootUrl) {
				if ($reqUrl == "/" . $atomRootUrl) { $reqUrl .= "/"; }
				if ($reqUrl == "") { $reqUrl = "/"; }
			}

			// Split Request into array elements for processing

			$requestArray = array();
			$tmpArray =  explode("/", $reqUrl);
			array_shift($tmpArray);
			
			// If there is an extension specified, then make sure the last
			// element in the URL isn't index.
			if ($this->extension) {
				$page = array_pop($tmpArray);
				if ($page!="index") {
					array_push($tmpArray, $page);
				} else {
					$this->addMsg("Request.splitRequestUri: found index.{extension} - removed index from URL array");
				}
			}
			
			if ($atomRootUrl && $tmpArray[0]==$atomRootUrl) {
				array_shift($tmpArray);
				$this->serviceRoot .= $atomRootUrl . "/";
			}

            /* Begin modification: Save the Elgg user and adjust the service path */

            define("ELGG_USER", array_shift($tmpArray));
            array_shift($tmpArray);

            /* End modification */

			$this->service = array_shift($tmpArray);
			if($this->service) {
				$this->serviceRoot .= $this->service . "/";
			}
			
			$this->breadcrumb = array();
			
			foreach($tmpArray as $res) {
				if ($res) {
					array_push($this->breadcrumb, $res);
				}
			}
		}
		
		function addMsg($mess) {
			array_push($this->msg, $mess);
			##echo " * " . $mess . "\n";
		}
		
	}


?>
