<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * atomConfig.php - Atom specific global variables.                   *
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

	// Isotope version number
	$isoTopeVersion = "0.1.2";
	$isoTope        = "isoTope";
	$isoTopeUrl     = "http://www.isolani.co.uk/projects/atom/isoTope/";

	$isoTopeUserAgent = $isoTope . " " . $isoTopeVersion . " - " . $isoTopeUrl;

	// Toggle between DEV and LIVE configuration settings
	$env = "DEV";		// local development environment
	#$env = "LIVE";		// public webserver details
	
	// Toggle logging ON or OFF
	$logging         = "ON";
	##$logging         = "OFF";



	// Initialise global settings
	$atomDomain      = "";
	$atomPort        = "";
	$atomRootUrl     = "";
	$timezone        = "";
	$siteName        = "";

	if ($env=="DEV") {
		// Development environment settings
		//$atomDomain      = "localhost";
		//$atomPort        = "8005";
		//$atomRootUrl     = "atom";
		//$timezone        = "+01:00";
		//$siteName        = "isoTope";

		// Development environment settings
		$atomDomain      = "localhost";
		$atomPort        = "8005";
		$atomRootUrl     = "atom";
		$timezone        = "+01:00";
		$siteName        = "isoTope";

		// Where to log all the messages
		$logDir   = "C:/www/phpAtomApi/store/log/";
	} elseif ($env=="LIVE") {
		// Live website settings
		$atomDomain      = "atom.isolani.co.uk";
		$atomRootUrl     = "";
		$timezone        = "+05:00";
		$siteName        = "isoTope";

		// Where to log all the messages
		$logDir   = "/home/isofarro/isoTope/logs/";
	}
	
	
	//
	// DO NOT CHANGE ANYTHING BELOW THIS LINE
	// unless you know what you are doing!
	//


	/*
	 * Common File logging options:
	 *
	 */
	 
	if ($logging=="ON") {
		$logFile  = $logDir . "atomApi.log";
		$logDelim = "\n------------------------------------------------------------------------\n";
	}



	/*
	 * Initial list of defined services
	 * {serviceName} => {array of settings}
	 * Other services are added via atomServices.php
	 */
	$services = array();

	##
	## No editable admin services at the moment
	##
	$services["admin"]    = array(
		"class"		=> "Admin",
		"title"		=> "Administration",
		"editable"	=> "false",
	);


	// List of acceptable content mime-types supported by this AtomAPI implementation
	$mimeType = array(
		"atom"    => "application/x.atom+xml",
		"html"    => "text/html",
		"xhtml"   => "application/xhtml+xml",
		"xml"     => "text/xml",
		"plain"   => "text/plain",
	);

	// Hashtable containing "mimeType"=>"includeFile" for each
	// extension to the Atom format
	// Content elements can use these includes to store / manipulate content
	$mimeTypeHandler = array(
		'opml'   => 'Opml',
		'oml'    => 'Oml',
		'xml'    => 'GenericXml',
		'xhtml'  => 'Xhtml',
		'html'   => 'Html',
		'plain'  => 'PlainText'
	);
	
	// Mime-types that this Atom implementation can return as output
	$outputType = array(
		'atom'  => 'atom', 
		'html'  => 'html',
		'xhtml' => 'xhtml'
	);

	// Also set up a lookup table for quicker reverse searches
	$outputTypeList = array();
	foreach($outputType as $type) {
		if ($mimeType[$type]) {
			$outputTypeList[$mimeType[$type]] = $type;
		}
	}


	// List of supported namespaces
	$namespace = array(
		'http://purl.org/atom/ns#'  => 'Atom'
	);

	// Scheduled to be deprecated by $namespace
	//$atomNamespace = "http://purl.org/atom/ns#";
	$atomNamespace = "http://purl.org/atom/ns#";
	$atomVersion   = "0.3";

	// Scheduled to be deprecated - move to atomRequest?
	$atomNS = "";
	
	
	// top element name (no namespace) => xml type identifier
	$hierXml = array(
		'opml'       => 'opml',
		'oml'        => 'oml',
		'html'       => 'xhtml',
	    'set'        => 'docbook',
	    'book'       => 'docbook',
	    'chapter'    => 'docbook',
	    'article'    => 'docbook',
		'topElement' => array(
							'firstChild'  => 'firstChild',
							'secondChild' => 'secondChild',
							'thirdChild'  => 'thirdChild'
						),
	);



	/*
	 * Do not alter anything below this line
	 *
	 */


	// $urlPrefix must be the domain of the Atom service
	$urlPrefix       = "http://" . $atomDomain;
	
	if ($atomPort) {
		$urlPrefix .= ":" . $atomPort;
	}
	
	
	// $rootUri is the path to the Atom service
	$rootUri         = "/";
	if ($atomRootUrl) {
		$rootUri .= $atomRootUrl . "/";
	}


	// Keep a list of namespaces found in the current XML document
	// in the format "namespaceId"=>"namespaceURL"
	$ns = array();

?>