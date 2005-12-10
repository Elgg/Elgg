<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * atomHtml.php - Atom / HTML related functions                       *
 *                atom classes -> HTML                                *
 *                atom feed -> HTML                                   *
 *                generic atom data -> HTML                           *
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
 * atom classes -> HTML
 *
 *************************************************/
 
function atomEntryToHtml(&$entry) {
	global $req, $mimeType, $services;

	$tpl = initTemplate();
	
	$tpl->assign("rootUri", $req->rootUri);
	$tpl->assign("serviceLink", "<li><a href=\"" . $req->serviceRoot . "\">" . $services[$req->service]["title"] . "</a>:</li>\n");

	if ($req->atomResource["blogComment"]) {
		$cat  = "<li><a href=\"" . $req->serviceRoot . $req->atomResource["blogEntry"] . "\">" . $req->atomResource["blogEntry"] . "</a>:</li>\n";
		$cat .= "<li><a href=\"" . $req->serviceRoot . $req->atomResource["blogEntry"] . "/comments\">Comments</a>:</li>";
		$tpl->assign("categoryLink", $cat);
	}

	$tpl->assign("title", atomRichTextToHtml($entry->title));
	$tpl->assign("serviceName", $services[$req->service]["title"]);

	$feedLink = getAtomFeedLink();
	if ($feedLink) {
		$tpl->assign("atomFeedUrl", atomLinkToHtml($feedLink));
	}

	$editLink = getAtomLink($entry, "service.edit", $mimeType["atom"]);
	if ($editLink) {
		$tpl->assign("atomEditUrl", atomLinkToHtml($editLink));
	}
	
	$commentLink = getAtomLink($entry, "service.comment", $mimeType["atom"]);
	if ($commentLink) {
		$tpl->assign("atomCommentUrl", atomLinkToHtml($commentLink));
	}

	$pageContent = "";
	
	// Create a content element if available
	if (!empty($entry->content)) {
		foreach($entry->content as $cont) {
			$pageContent .= atomContentToHtml($cont);
		}
	}

	$tpl->assign("pageContent", $pageContent);
	
	if ($commentLink) {
		$tpl->assign("pageFooter", "<hr /><p class=\"action\"><a href=\"" . $commentLink->href . "\">Comments on this entry</a></p>");
	}
	
	$tpl->assign("authorName", "Author: " . atomAuthorToHtml($entry->author));
	$tpl->assign("createDate", "Created: " . dateW3DateToHtml($entry->created) . "<br />");
	if ($entry->modified) {
		$tpl->assign("modifiedDate", "Modified: " . dateW3DateToHtml($entry->modified));
	}
	$tpl->printToScreen();
}


/**************************************************
 *
 * atom feed -> HTML
 *
 *************************************************/

function atomFeedToHtml(&$feed) {
	global $req, $mimeType, $services;

	$tpl = initTemplate();
	
	$tpl->assign("serviceName", $services[$req->service]["title"]);
	$tpl->assign("rootUri", $req->rootUri);
	$tpl->assign("serviceLink", "<li><a href=\"" . $req->serviceRoot . "\">" . $services[$req->service]["title"] . "</a>:</li>\n");

	if ($req->atomResource["blogComment"]) {
		$tpl->assign("title", "Comments");
		$tpl->assign("entry", $req->atomResource["blogEntry"]);
		$tpl->assign("categoryLink", "<li><a href=\"" . $req->serviceRoot . $req->atomResource["blogEntry"] . "\">" . $req->atomResource["blogEntry"] . "</a>:</li>");
	} else {
		$tpl->assign("title", " Recent entries");
	}

	$postLink = getAtomLink($feed, "service.post", $mimeType["atom"]);
	if ($postLink) {
		$tpl->assign("atomFeedUrl", atomLinkToHtml($postLink));
	}

	$content = "";	
	if ($req->atomResource["blogComment"]) {
		$content .= "<p>These are the comments to this blog entry:</p>\n\n";
	} else {
		$content .= "<p>These are the most recent entries to this atom-enabled website:</p>\n\n";
	}
	
	$content .= "<ul>\n";
	foreach($feed->entries as $entry) {
		$content .= atomFeedEntryAsHtml($entry);		
	}
	$content .= "</ul>\n\n";
	
	$tpl->assign("pageContent", $content);

	$tpl->printToScreen();
}

function atomFeedEntryAsHtml(&$entry) {

	$html = "";
	
	$html .= "\t<li><a href=\"" . $entry->link . "\">" . $entry->title . "</a> <br />-- " . dateW3DateToHtml($entry->created) . "</li>\n";
	
	return $html;

}

/**************************************************
 *
 * generic atom classes -> HTML routines
 *
 *************************************************/

function atomRichTextToHtml(&$atomContainer) {
	return $atomContainer->text;
}

function atomContentToHtml(&$content) {
	global $req;
	
	$req->addMsg("atomContentToHtml: [" . $content->type . "][" . $content->mode . "]");
	$handler = getContentHandler($content);


	$buffer = "<div class=\"content\">\n";

	$buffer .= $handler->getContentAsHtml();

	$buffer .= "</div>\n\n";
	return $buffer;
}

function atomAuthorToHtml(&$author) {
	$buffer = "";	
	if ($author) {
		if ($author->url) {
			$buffer .= "<a href=\"" . $author->url . "\">" . $author->name . "</a>";
		} else {
			$buffer .= $author->name;
		}		
	}
	return $buffer;
}

function atomLinkToHtml(&$link) {
	$buffer = "<link rel=\"" . $link->rel . "\"";
	if ($link->type) {
		$buffer .= " type=\"" . $link->type . "\"";
	}
	$buffer .= " href=\"" . $link->href . "\"";
	if ($link->title) {
		$buffer .= " title=\"" . $link->title . "\"";
	}
	$buffer .= " />";
	return $buffer;
}

/**************************************************
 *
 * Template functions
 *
 *************************************************/
 
function initTemplate() {
	global $siteName, $isoTope, $isoTopeUrl, $isoTopeVersion;

	// Initialise template
	include_once("lib/class.TemplatePower.inc.php");
	$tpl = new TemplatePower(path . "units/atom/isotope/html/atom.html");
	$tpl->prepare();
	
	
	$tpl->assign("siteName", $siteName);
	$tpl->assign("poweredBy", "<br />Powered by <a href=\"" . $isoTopeUrl . "\">" . $isoTope . "</a> " . $isoTopeVersion);
	
	return $tpl;
}

?>
