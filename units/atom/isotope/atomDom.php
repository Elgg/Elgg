<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * atomDom.php - Atom / DOM related functions                         *
 *               atom classes -> DOM                                  *
 *               DOM -> atom classes                                  *
 *               atom feed -> DOM                                     *
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

 

/**************************************************
 *
 * DOM -> atom classes
 *
 *************************************************/
 
/*
 * XML-Dom to Atom classes related functions
 */
function createAtomEntry(&$dom) {
	global $req, $ns, $atomNS;
	$req->addMsg("createAtomEntry: Creating an Atom entry from the received XML");
	$root = $dom->firstChild;
	
	$atomNS = "";
	
	// Look at the root attributes and extract anything that looks like
	// a namespace reference or declaration.
	findNamespace($root);

	// Figure out whether atom entry is using a name-space by looking at the 
	// root element - which should be "entry" and deriving the namespace
	// from that element name. Not sure whether there has to be an xmlns:atom attribute
	// so we may be able to check for that -- but won't that mean the URL has to
	// be exactly right for the atom namespace?
	if (!(strpos($root->nodeName, "entry") === false)) {
		if($root->nodeName=="entry") {
			$req->addMsg("createAtomEntry: Found a non-namespaced entry element");
			$atomNS="";
		} else {
			$req->addMsg("createAtomEntry: Found a NAMESPACED entry element: (" . $root->nodeName . ")");
			$temp = explode(":", $root->nodeName);
			$atomNS=$temp[0] . ":";
		}
		$req->addMsg("createAtomEntry: possible atom namespace: " . $atomNS);
		
	} else {
		$req->addMsg("createAtomEntry: Didn't find an entry element");	
		returnDataError("No root element called entry could be found.");
	}
	
	if ($root->nodeName== $atomNS . "entry") {
		#$req->addMsg("createAtomEntry: Found node: entry");
		$atom = new AtomEntry();
		
		$item = $root->firstChild;
		while ($item) {
			#$req->addMsg("* Found entry element: " . $item->nodeName);
			
			switch($item->nodeName) {
				case $atomNS . "title":
					$title=createRichText($item);
					$atom->title=$title;
					break;
				case $atomNS . "summary":
					$summary=createRichText($item);
					#$req->addMsg("* * summary: " . $summary);
					$atom->summary=$summary;
					break;
				case $atomNS . "created":
					$created=$item->firstChild->nodeValue;
					#$req->addMsg("* * created: " . $created);
					$atom->created=$created;
					break;
				case $atomNS . "issued":
					$issued=$item->firstChild->nodeValue;
					#$req->addMsg("* * issued: " . $issued);
					$atom->issued=$issued;
					break;

				case $atomNS . "modified":
					$modified=$item->firstChild->nodeValue;
					#$req->addMsg("* * modified: " . $modified);
					$atom->modified=$modified;
					break;

				case $atomNS . "author":
					#$req->addMsg("* * author: ");
					$authObj = createAtomPerson($item);
					$atom->author=$authObj;
					break;
				
				case $atomNS . "contributor":
					#$req->addMsg("* * contributor: ");
					$contrib = createAtomPerson($item);
					array_push($atom->contributor, $contrib);
					break;
					
				case $atomNS . "content":
					#$req->addMsg("* * content: ");
					$cont = createAtomContent($item); 
					array_push($atom->content, $cont);
					break;
				
				case $atomNS . "generator":
					#$req->addMsg("* * generator: ");
					$generator = createAtomGenerator($item);
					$atom->generator = $generator;
					break;
					
				case $atomNS . "link":
					#$req->addMsg("* * link: ");
					$link = createAtomLink($item);
					array_push($atom->link, $link);
					break;
					
				case $atomNS . "id":
					$id = $item->firstChild->nodeValue;
					#$req->addMsg("* * id: " . $id);
					$atom->id = $id;
					break;
					
				default:
					$req->addMsg("createAtomEntry: WARN: Unsupported element: " . $item->nodeName);
					break;
			}
			
			$item = $item->nextSibling;
		}
		
	} else {
		$req->addMsg("createAtomEntry: ERROR: found root node called " . $root->nodeName . " was expecting entry");
		returnDataError("No root element called entry could be found.");
	}
	return $atom;
}

function createAtomPerson(&$pers) {
	global $req, $ns, $atomNS;
	
	$person = new AtomPerson();
	$persItem = $pers->firstChild;
	while ($persItem) {
		#$req->addMsg("* * Found person element: " . $persItem->nodeName);
		switch($persItem->nodeName) {
			case $atomNS . "name":
				$name = $persItem->firstChild->nodeValue;
				#$req->addMsg("* * * name: " . $name);
				$person->name = $name;
				break;
			case $atomNS . "email":
				$email = $persItem->firstChild->nodeValue;
				#$req->addMsg("* * * email: " . $email);
				$person->email = $email;
				break;
			case $atomNS . "url":
				$url = $persItem->firstChild->nodeValue;
				#$req->addMsg("* * * url: " . $url);
				$person->url = $url;
				break;
		}
		$persItem = $persItem->nextSibling;
	}
	return $person;
}


function createAtomGenerator(&$gen) {
	global $req, $ns, $atomNS;
	
	$generator = new AtomGenerator();

	$name = $gen->firstChild->nodeValue;
	#$req->addMsg("* * name: " . $name);
	$generator->generator = $name;

	$version = $gen->attributes["version"];
	#$req->addMsg("* * @version: " . $version);
	$generator->version = $version;	

	$url = $gen->attributes["url"];
	#$req->addMsg("* * @url: " . $url);
	$generator->url = $url;	

	return $generator;	
}

function createRichText(&$text) {
	global $req;
	
	$richText = new AtomContent();
	
	$type = $text->attributes["type"];
	$richText->type = $type;

	$mode = $text->attributes["mode"];
	$richText->mode = $mode;

	if (empty($richText->type)) { $richText->type="text/plain"; }
	if (empty($richText->mode)) { $richText->mode="xml"; }

		
	$textNode = $text->firstChild;
	$textBuffer = "";
	while ($textNode) {
		$rawText="";
		switch ($textNode->nodeType) {
			case 1:
				$rawText = $textNode->toString();
				break;
			case 3:
				$rawText = $textNode->nodeValue;
				break;
			case 4:
				$rawText = $textNode->nodeValue;
				break;
		}
		$textBuffer .= $rawText;
	
		$textNode = $textNode->nextSibling;
	}
	$richText->text = $textBuffer;

	return $richText;
}

// New DOM based method of storing content
function createAtomContent(&$node) {
	global $req, $atomNS;

	$content = new AtomContent();

	$type = $node->attributes["type"];
	$req->addMsg("createAtomContent: * @type: " . $type);
	$content->type = $type;

	$mode = $node->attributes["mode"];
	$req->addMsg("createAtomContent: * @mode: " . $mode);
	$content->mode = $mode;

	// Depending on whether the mode above is xml, escaped or base64 treat the
	// following nodes accordingly:

	$contDom = new XML();
	$root = $contDom->createElement('content');

	copyAllDescendants($node, $root);
	$contDom->appendChild($root);
	
	$content->container = $contDom;		//->toString();
	$content->containerType = "dom"; 	//String";
	return $content;
}

function createAtomLink(&$node) {
	global $req;
	
	$req->addMsg("createAtomLink: [" . $node->attributes["rel"] . "][" . $node->attributes["type"] . "][" . $node->attributes["href"] . "][" . $node->attributes["title"] . "]");
	$link = new AtomLink();
	
	$rel = $node->attributes["rel"];
	$link->rel = $rel;
	
	$type = $node->attributes["type"];
	$link->type = $type;
	
	$href = $node->attributes["href"];
	$link->href = $href;
	
	$title = $node->attributes["title"];
	$link->title = $title;
	
	return $link;
}



/**************************************************
 *
 * Atom classes -> DOM
 *
 *************************************************/
 
 
function atomEntryToXml(&$atomEntry) {
	global $req, $atomNamespace, $atomVersion;

	$atomDom = new XML();
	$atomDom->xmlDecl = '<?xml version="1.0" encoding="iso-8859-1" ?>';

	// Create entry element
	$entry = $atomDom->createElement('entry');
	$entry->attributes['version'] = $atomVersion;
	$entry->attributes['xmlns'] = $atomNamespace;

	// Create title element
	$title = atomRichTextToXml($atomDom, $atomEntry->title, 'title');
	$entry->appendChild($title);
	
	// Create link element	
	foreach($atomEntry->link as $link) {
		$entry->appendChild(atomLinkToXml($link, $atomDom));
	}
	
	// Create id element
	$id = $atomDom->createElement('id');
	$id->appendChild($atomDom->createTextNode($atomEntry->id));
	$entry->appendChild($id);

	// Create created element - if available
	if ($atomEntry->created) {
		$created = $atomDom->createElement('created');
		$created->appendChild($atomDom->createTextNode($atomEntry->created));
		$entry->appendChild($created);
	}

	// Create issued element
	$issued = $atomDom->createElement('issued');
	$issued->appendChild($atomDom->createTextNode($atomEntry->issued));
	$entry->appendChild($issued);

	// Create modified element - if available
	##$req->addMsg("atomEntryToXml: ModifiedDate: " . $atomEntry->modified);
	if ($atomEntry->modified) {
		$modified = $atomDom->createElement('modified');
		$modified->appendChild($atomDom->createTextNode($atomEntry->modified));
		$entry->appendChild($modified);
	}

	// Create author element - if available
	if (!empty($atomEntry->author)) {
		$author = $atomDom->createElement('author');
		atomPersonToXml($atomEntry->author, $author);
		$entry->appendChild($author);
	}
	
	// Create contributor element - if available
	if (!empty($atomEntry->contributor)) {
		foreach($atomEntry->contributor as $contrib) {
			$contributor = $atomDom->createElement('contributor');
			atomPersonToXml($contrib, $contributor);
			$entry->appendChild($contributor);
		}
	}

	// Create a summary if available
	##$req->addMsg("atomEntryToXml: Summary: ");
	if (!empty($atomEntry->summary)) {
		$summary = atomRichTextToXml($atomDom, $atomEntry->summary, 'summary');
		$entry->appendChild($summary);
	}
	
	// Create a content element if available
	##$req->addMsg("atomEntryToXml: Content: ");
	if (!empty($atomEntry->content)) {
		foreach($atomEntry->content as $cont) {
			$contentNode = atomContentToXml($cont, $atomDom);
			$entry->appendChild($contentNode);
		}
	}

	$atomDom->appendChild($entry);

	$req->addMsg("atomEntryToXml: Transforming into an XML string");
	$req->addMsg($atomDom->toString());
	
	return $atomDom;
}

function atomPersonToXml(&$atomPerson, &$atomDom) {
	global $req;
	
	#$req->addMsg("atomPersonToXml: "  . $atomPerson->name);
	$name = $atomDom->createElement('name');
	$name->appendChild($atomDom->createTextNode($atomPerson->name));
	$atomDom->appendChild($name);

	if ($atomPerson->url) {
		$url = $atomDom->createElement('url');
		$url->appendChild($atomDom->createTextNode($atomPerson->url));
		$atomDom->appendChild($url);
	}
	if ($atomPerson->email) {
		$email = $atomDom->createElement('email');
		$email->appendChild($atomDom->createTextNode($atomPerson->email));
		$atomDom->appendChild($email);
	}
}


function atomContentToXml(&$atomContent, &$atomDom) {
	global $req;
	
	$content = $atomDom->createElement('content');
	if ($atomContent->type) {
		$content->attributes['type'] = $atomContent->type;
	}
	if ($atomContent->mode) {
		$content->attributes['mode'] = $atomContent->mode;
	}

	copyAllDescendants($atomContent->container->firstChild, $content);

	return $content;
}


function atomRichTextToXml(&$atomDom, &$atomContainer, $elName) {
	global $req;

	$node = $atomDom->createElement($elName);
	if ($atomContainer->type) {
		$node->attributes['type'] = $atomContainer->type;
	}
	if ($atomContainer->mode) {
		$node->attributes['mode'] = $atomContainer->mode;
	}
	
	// Be a little smarter about this way of inserting content
	$node->appendChild($node->createTextNode($atomContainer->text));
	
	return $node;
}

function atomLinkToXml(&$atomLink, &$atomDom) {
	global $req;
	
	$link = $atomDom->createElement('link');
	$link->attributes['rel'] = $atomLink->rel;
	if ($atomLink->type) {
		$link->attributes['type'] = $atomLink->type;
	}
	$link->attributes['href'] = $atomLink->href;
	if ($atomLink->title) {
		$link->attributes['title'] = $atomLink->title;
	}
	
	return $link;
}


/**************************************************
 *
 * Atom feed -> DOM
 *
 *************************************************/

function atomFeedAsXml(&$atomFeed) {
	global $req, $atomVersion, $atomNamespace, $siteName, $services;
	
	$feedDom = new XML();
	$feedDom->xmlDecl = '<?xml version="1.0" encoding="iso-8859-1" ?>';
	
	$feed = $feedDom->createElement('feed');
	$feed->attributes['version'] = htmlspecialchars($atomVersion);
	$feed->attributes['xmlns'] = htmlspecialchars($atomNamespace);
	
	foreach($atomFeed->link as $link) {
		$feed->appendChild(atomLinkToXml($link, $feedDom));
	}
	
	$title = $feedDom->createElement('title');
	$title->appendChild($feedDom->createTextNode($siteName . " " . $services[$req->service]["title"]));
	$feed->appendChild($title);
	
	if ($atomFeed->modified) {
		$modified = $feedDom->createElement('modified');
		$modified->appendChild($feedDom->createTextNode(timestampToW3Date($atomFeed->modified)));
		$feed->appendChild($modified);
	}
	
	foreach($atomFeed->entries as $entry) {
		$feed->appendChild(atomFeedEntryAsXml($entry, $feedDom));
	}
	
	$feedDom->appendChild($feed);
	return $feedDom;
}

function atomFeedEntryAsXml(&$atomEntry, &$feedDom) {
	global $req, $mimeType;
	
	$entry = $feedDom->createElement('entry');
	
	$title = $feedDom->createElement('title');
	$title->appendChild($title->createTextNode($atomEntry->title));
	$entry->appendChild($title);
	
	$id = $feedDom->createElement('id');
	$id->appendChild($id->createTextNode($atomEntry->id));
	$entry->appendChild($id);
	
	$link = $feedDom->createElement('link');
	$link->attributes["rel"]  = "service.edit";
	$link->attributes["type"] = $mimeType["atom"];
	$link->attributes["href"] = $atomEntry->link;
	$entry->appendChild($link);

	$link2 = $feedDom->createElement('link');
	$link2->attributes["rel"]  = "alternate";
	$link2->attributes["type"] = $mimeType["html"];
	$link2->attributes["href"] = $atomEntry->link . ".html";
	$entry->appendChild($link2);

	if ($atomEntry->author) {
		$author = $feedDom->createElement('author');
		atomPersonToXml($atomEntry->author, $author);
		$entry->appendChild($author);
	}

	$issued = $feedDom->createElement('issued');
	$issued->appendChild($issued->createTextNode($atomEntry->issued));
	$entry->appendChild($issued);
	
	if ($atomEntry->created) {
		$created = $feedDom->createElement('created');
		$created->appendChild($created->createTextNode($atomEntry->created));
		$entry->appendChild($created);
	}

	if ($atomEntry->modified) {
		$modDate = $feedDom->createElement('modified');
		$modDate->appendChild($modDate->createTextNode($atomEntry->modified));
		$entry->appendChild($modDate);
	}
	
	return $entry;
}


?>