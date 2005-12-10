<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * atomFunc.php - Common functions.                                   *
 *                HTTP Functions                                      *
 *                W3C Date functions                                  *
 *                Logging - atomLog()                                 *
 *                DOM Functions                                       *
 *                Common Atom functions                               *
 *                Common Atom Object Factories                        *
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

 

/**************************************
 *
 * Common HTTP functions
 *
 **************************************/

// Create and send back the location redirect to the edit URL of the new entry
function redirectNewAtomEntry(&$entry) {
	global $req, $mimeType;

	// Get the edit link for the Location return
	$link = getAtomLink($entry, "service.edit", $mimeType["atom"]);
	$location = $link->href;
	$req->addMsg("Location redirect: [" . $link . "][" . $link->href . "]");

	setStatusCode("303");
	setLocation($location);
}


function returnStatusCode($statuscode, $msg="") {
	global $req;
	if ($statuscode) {
		header("HTTP/1.0 " . $statuscode, true);
	} else {
		header("HTTP/1.0 520 Status Code Undefined", true);
	}

	if ($msg) {
		setContentType("text/plain");
		$req->addMsg("returnStatusCode: Halting processing, returning " . $statuscode . " with message: " . $msg);	
		
		print $msg;
	} else {
		$req->addMsg("returnStatusCode: Halting processing, returning only an HTTP header");	
	}
	
	atomLog();
	// Short-circuit any further processing
	exit(0);
}

function setStatusCode($statuscode) {
	global $req;
	if ($statuscode) {
		$req->addMsg("setStatusCode: Setting " . $statuscode);	
		header("HTTP/1.0 " . $statuscode, true);
	}
}

function returnMethodNotAllowed($msg="") {
	global $req;
	$req->addMsg("returnMethodNotAllowed: 405 Method Not Allowed");
	$err = "The " . $req->method . " method is not permitted for this URL.\n\n";
	
	if ($msg) {
		$err .= "Further information about this error:\n";
		$err .= "\t" . $msg . "\n\n";
	}
	returnStatusCode("405 Method Not Allowed", $err);
}

function returnPageNotFound($msg="") {
	global $req;
	$req->addMsg("returnPageNotFound: 404 Not Found [" . $msg . "]");
	$err = "The page requested could not be found.\n\n";
	
	if ($msg) {
		$err .= "Further information about this error:\n";
		$err .= "\t" . $msg . "\n\n";
	}
	returnStatusCode("404 Not Found", $err);
}

function returnDataError($msg) {
	global $req;
	$req->addMsg("returnDataError: 400 Invalid request");
	$err = "There was a data error in the received Atom request that could not be handled.\n\n";
	
	if ($msg) {
		$err .= "Further information about this error:\n";
		$err .= "\t" . $msg . "\n\n";
	}
	returnStatusCode("400 Invalid request", $err);
}

function returnServerError($msg) {
	global $req;
	$req->addMsg("returnServerError: 500 Internal Server Error");
	$err = "An Atom server or configuration error occurred.\n\n";
	
	if ($msg) {
		$err .= "Further information about this error:\n";
		$err .= "\t" . $msg . "\n\n";
	}
	returnStatusCode("500 Internal Server Error", $err);
}

function setContentType($contType) {
	global $req;
	$req->addMsg("setContentType: " . $contType);

	header ("Content-Type: " . $contType, true);
}

function setLocation($url) {
	global $req;
	$req->addMsg("setLocation: " . $url);

	header ("Location: " . $url, true);
}


/**************************************
 *
 * Date conversion routines
 *
 **************************************/
 
// W3Date:        2003-07-22T07:11-05:00
// SQL Timestamp: 20030722071100
 
function dateW3DateToSql($w3Date) {
	if ($w3Date) {
		preg_match("/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})-(\d{2}):(\d{2})$/", $w3Date, $bits);
		#print "["; foreach($bits as $bit) { print $bit . "|"; };
		return $bits[1] . $bits[2] . $bits[3] . $bits[4] . $bits[5] . "00";
	}
}

function dateW3DateToHtml($w3Date) {
	if ($w3Date) {
		preg_match("/^(\d{4})-(\d{2})-(\d{2})T(\d{2}):(\d{2})/", $w3Date, $bits);
		#print "["; foreach($bits as $bit) { print $bit . "|"; };
		return $bits[3] . "/" . $bits[2] . "/" . $bits[1] . " " . $bits[4] . ":" . $bits[5];
	}
}

function dateSqlToW3Date($sqlDate) {
	if($sqlDate) {
		preg_match("/^(\d{4})(\d{2})(\d{2})(\d{2})(\d{2})(\d{2})$/", $sqlDate, $bits);
		#print "["; foreach($bits as $bit) { print $bit . "|"; };
		return $bits[1] ."-". $bits[2] ."-". $bits[3] ."T". $bits[4] .":". $bits[5] ."+01:00";		
	}
}

function timestampToW3Date($timestamp=0) {
	global $timezone;
	if (empty($timestamp)) {
		$timestamp = time();
	}
	
	$date = date("Y-m-d\TH:i:s" ,$timestamp);

	return $date . $timezone;
}

function toTagDate($timestamp=0) {
	if (empty($timestamp)) {
		$timestamp = time();
	}
	
	$date = date("Y-m-d", $timestamp);
	return $date;
}

/**************************************
 *
 * Logging - atomLog
 *
 **************************************/

function atomLog() {
	global $req, $logging, $logFile, $logDelim;
	
	$buffer = "";


	$buffer .= "\n\nRequest Details:\n";
	$buffer .= " * service       : " . $req->service . "\n";
	$buffer .= " * method        : " . $req->method . "\n";
	$buffer .= " * Content-Type  : " . $req->contentType . "\n";
	$buffer .= " * Extension     : " . $req->extension . "\n";
	$buffer .= " * Response-Type : " . $req->responseType . "\n";
	$buffer .= " * urlPrefix     : " . $req->urlPrefix . "\n";
	$buffer .= " * rootUri       : " . $req->rootUri . "\n";

	$buffer .= " * serviceRoot : " . $req->serviceRoot . "\n";
	
	$buffer .= " * breadcrumb  : ";
	foreach ($req->breadcrumb as $res) {
		$buffer .= "[" . $res . "] ";
	}
	$buffer .= "\n";

	$buffer .= " * atomResource: \n";
	if ($req->atomResource) {
		foreach ($req->atomResource as $key => $value) {
			$buffer .= "\t" . $key . "\t=> " . $value . "\n";
		}
	}
	$buffer .= " * pageUri     : " . $req->pageUri . "\n";
	$buffer .= "\n";


	$buffer .= "Acceptable mime-types:\n";
	foreach ($req->accept as $res => $val) {
		$buffer .= "[" . $res . " - " . $val . "] ";
	}
	$buffer .= "\n\n";

	if (!empty($req->dom)) {
		$buffer .= "Dom representation of received XML file:\n";
		$buffer .= $req->dom->toString();
		$buffer .= "\n";
	}

	#$buffer .= dateW3DateToSql("2003-07-22T07:11-05:00");

	$buffer .= "\nMessages:\n";

	foreach($req->msg as $msg) {
		$buffer .= " * " . $msg . "\n";
	}
	
	// Check whether file logging is enabled
	if ($logging=="ON") {
		$handle = fopen($logFile, "a");
		fwrite($handle, $buffer . $logDelim);
		fclose($handle);
	} else {
		echo $buffer;
	}
}



/**************************************
 *
 * Common DOM functions
 *
 **************************************/


/*
 * Search through the received node looking for namespace declarations
 * and add them to our list of found namespace declarations
 */
function findNamespace(&$node) {
	global $req, $ns;

	foreach(array_keys($node->attributes) as $attr) {
		$req->addMsg("findNamespace: Attr: " . $attr);
		$pos = strpos("xmlns", $attr);
		if ($pos === false) {} elseif($pos==0) {
			$temp = explode(":", $attr);
			if (count($temp)==1) {
				// its a default namespace
				$temp[1] = "DEFAULT";
			}
			$req->addMsg("findNamespace: Namespace defined: " . $attr . " -- xmlns:" . $temp[1] . "=\"" . $node->attributes[$attr] . "\"");
			$ns[$temp[1]] = $node->attributes[$attr];
		}
	}
}

/*
 * Take a node and copy all of its descendants into a new starting node
 */
function copyAllDescendants($fromNode, &$toNode) {
	global $req;
	
	#$req->addMsg("Old Node: " . $fromNode->toString());
	$len = count($fromNode->childNodes);
	for($i=0; $i<$len; $i++) {
		$toNode->appendChild($fromNode->childNodes[$i]);
	}
	#$req->addMsg("New Node: " . $toNode->toString());
}


/**************************************
 *
 * Common Atom functions
 *
 **************************************/

/*
 * Remove a link with a specific rel and type
 */
function removeAtomLink(&$atomEntry, $rel, $type) {
	global $req;
	
	$linkArr = $atomEntry->link;
	
	$linkPos=-1;
	$req->addMsg("removeAtomLink: Link: --[" . $rel . "][" . $type . "]");
	##$req->addMsg("removeAtomLink: \n" . var_export($linkArr, TRUE));
	foreach ($linkArr as $idx => $link) {
		#$req->addMsg("Link: " . $idx . "-[" . $link->rel . "][" . $link->type . "]");
		if (($link->rel==$rel) && ($link->type==$type)) {
			$linkPos=$idx;
			#$req->addMsg("removeAtomLink: Link entry found at: $idx");
		}
	}
	if ($linkPos!=-1) {
		unset($atomEntry->link[$linkPos]);
	}
}

/*
 * Get a particular link from an Atom Entry given its rel and type
 */
function getAtomLink(&$atomEntry, $rel, $type) {
	global $req;
	
	$linkArr = $atomEntry->link;
	$linkPos=-1;
	$req->addMsg("getAtomLink: Link: --[" . $rel . "][" . $type . "]");
	foreach ($linkArr as $idx => $link) {
		$req->addMsg("Link: " . $idx . "-[" . $link->rel . "][" . $link->type . "]");
		if (($link->rel==$rel) && ($link->type==$type)) {
			$linkPos=$idx;
			$req->addMsg("getAtomLink: Link entry found at: $idx [" . $atomEntry->link[$linkPos]->href . "]");
		}
	}
	if ($linkPos!=-1) {
		return $atomEntry->link[$linkPos];
	}
}

/*
 * Get the main atom feed for this website
 */
function getAtomFeedLink() {
	global $req, $mimeType, $urlPrefix, $rootUri;

	$feedLink = new AtomLink();
	$feedLink->rel   = "service.feed";
	$feedLink->type  = $mimeType["atom"];
	$feedLink->href  = $urlPrefix . $rootUri;
	$feedLink->title = "Atom Feed for this website";
	
	return $feedLink;
}

/*
 * 
 */
function mergeAtomEntry($oldEntry, &$newEntry) {
	global $req;
	
	// Update title, summary and content items:
	$req->addMsg("mergeAtomEntry: Updating title");
	$oldEntry->title   = $newEntry->title;
	
	if (!empty($newEntry->summary)) {
		$req->addMsg("mergeAtomEntry: Updating summary");
		$oldEntry->summary = $newEntry->summary;
	}
	
	if (!empty($newEntry->content)) {
		$req->addMsg("mergeAtomEntry: Updating content");
		$oldEntry->content = $newEntry->content;
	}
	
	if (!empty($newEntry->author)) {
		$req->addMsg("mergeAtomEntry: Updating author");
		$oldEntry->author = $newEntry->author;
	}
	
	if (!empty($newEntry->contributor)) {
		$req->addMsg("mergeAtomEntry: Updating contributor");
		$oldEntry->contributor = $newEntry->contributor;
	}
	
	$timestamp = time();
	$oldEntry->timestamp = $timestamp;
	$req->addMsg("mergeAtomEntry: Entry Timestamp: " . $oldEntry->timestamp);
	$currentDate = timestampToW3Date($timestamp);

	if (!empty($newEntry->modified)) {
		$req->addMsg("mergeAtomEntry: Updating modified date");
		$oldEntry->modified = $newEntry->modified;
	} else {
		$req->addMsg("mergeAtomEntry: Creating new modified date");
		$oldEntry->modified = $currentDate;
	}
	
	if (!empty($newEntry->issued)) {
		$req->addMsg("mergeAtomEntry: Updating issued date");
		$oldEntry->issued = $newEntry->issued;
	}

	return $oldEntry;
}

/*
 * Creates a unique id using the tag: uri.
 */
function createIdTag($identifier) {
	global $atomDomain, $req;
	
	$service = $req->service;
	if ($service) {
		$service .= ".";
	}
	
	return "tag:" . $atomDomain . "," . toTagDate() . ":" . $service . $identifier;
}


/**************************************
 *
 * Common Atom Object Factories
 *
 **************************************/


function getContentHandler(&$content) {
	global $req, $mimeTypeR, $mimeTypeHandler;

	// Populate the type and mode with default values if they are empty
	if (empty($content->type)) { $content->type = "text/plain"; }
	if (empty($content->mode)) { $content->mode = "xml"; }

	// Look at the content @type.
	
	// If its text/xml then
	//  * do an element check to identify the vocabulary
	// else
	// Do a mime-type lookup.

	$mimeId         = "";
	$contentHandler = "";

	if ($content->type=="text/xml") {
		// Use the element structure to identify XML vocab
		$req->addMsg("getContentHandler: text/xml content");
		$mimeId         = getXmlHandler($content->container);
		$contentHandler = $mimeTypeHandler[$mimeId];
	} else {
		// Use the type to determine the mime-type handler:
		$req->addMsg("getContentHandler: non xml content");
		$mimeId         = $mimeTypeR[$content->type];
		$contentHandler = $mimeTypeHandler[$mimeId];
	}


	if ($contentHandler) {
		$req->addMsg("getContentHandler: [" . $mimeId . "][" . $content->type . "][" . $contentHandler . "]");

		include_once("handler" . $contentHandler . ".php");
		$req->addMsg("getContentHandler: included mimeHandler: handler" . $contentHandler . ".php" );

		// Creating the class name
		$mimeHandlerClass = $contentHandler . "Handler";

		// Instantiating an object using a dynamic class name	
		$handler = new $mimeHandlerClass();

		if (is_subclass_of($handler, "BaseClassHandler")) {
			$req->addMsg("getContentHandler: instantiated class is a subclass of BaseClassHandler");

			// Call the generic parseContent function
			$handler->parseContent($content->container);
			return $handler;

		} else {
			$req->addMsg("getContentHandler ERROR: Instantiated class not a subclass of BaseClassHandler");
			returnServerError("Atom content handler " . $contentHandler . " is not a valid content handler.");
		}
	} else {
		$req->addMsg("getContentHandler ERROR: no content handler specified for XML vocab: " . $mimeId);
		$msg = "No Atom content handler specified for ";
		if ($content->type=="text/xml") {
			$msg .= "xml root element " . $content->container->firstChild->firstChild->nodeName;
		} else {
			$msg .= $content->type;
		}
		returnServerError($msg);
	}
	
	return null;
}

/*
 * Given a content-enveloped node identify the XML vocab being used
 */
function getXmlHandler(&$cont) {
	global $req, $hierXml;

	$node = $cont->firstChild->firstChild;
	$req->addMsg("getXmlHandler: " . $node->toString());
	$req->addMsg("getXmlHandler: [" . $node->nodeName . "]");

	$nodeName = $node->nodeName;
	$nodeNamespace = "";
	if (strstr($nodeName, ':') === false) {
		// Element isn't namespaced
	} else {
		$tmpArray      = explode(':', $nodeName);
		$nodeNamespace = $tmpArray[0];
		$nodeName      = $tmpArray[1];
	}
	
	if($hierXml[$nodeName]) {
		// If the value is not an array then return the value
		// If it is an array, then we need to go down to the child of this element
		if (is_array($hierXml[$nodeName])) {
			$req->addMsg("getXmlHandler: Element [" . $nodeName . "] requires child eval.");
			atomLog();
			exit(0);
		} else {
			$req->addMsg("getXmlHandler: Handler found [" . $nodeName . "][" . $hierXml[$nodeName] . "]");
			atomLog();
			return $hierXml[$nodeName];
		}
	}

	returnServerError("No XML handler found for " . $node->nodeName);	
}

function getAtomStorageHandler($storage) {
	global $req;

	$req->addMsg("getAtomStorageHandler: Storage mechanism: store" . $storage . ".php");
	include_once("store" . $storage . ".php");

	// Creating the class name
	$storageHandlerClass = $storage . "Storage";

	// Instantiating an object using a dynamic class name	
	$handler = new $storageHandlerClass();

	if (is_subclass_of($handler, "BaseClassStorage")) {
		$req->addMsg("getAtomStorageHandler: instantiated class is a subclass of BaseClassStorage");

		return $handler;
		
	} else {
		$req->addMsg("ERROR getAtomStorageHandler: Instantiated class not a subclass of BaseClassStorage");
	}
	
	returnServerError("No storage handler called " . $storage . " defined");

}

function getAtomServiceHandler($service) {
	global $req, $services;
	
	$req->addMsg("getAtomServiceHandler: Service: service" . $services[$service]["class"] . ".php");

	include_once("service" . $services[$service]["class"] . ".php");

	// Creating the class name
	$serviceHandlerClass = $services[$service]["class"] . "Service";

	// Instantiating an object using a dynamic class name	
	$handler = new $serviceHandlerClass();

	if (is_subclass_of($handler, "BaseClassService")) {
		$req->addMsg("getAtomServiceHandler: Instantiated class is a subclass of BaseClassService");
		return $handler;
	} else {
		$req->addMsg("getAtomServiceHandler: ERROR: Instantiated class not a subclass of BaseClassService");
	}

	returnServerError("No service handler called " . $service . " defined");
}

?>