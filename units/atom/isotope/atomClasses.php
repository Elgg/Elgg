<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * atomClasses.php - Atom related classes (value objects)             *
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
 **********************************************************************
 *                                                                    *
 * Changes:                                                           *
 *                                                                    *
 *	Initial:                                                          *
 *	Defined as per Mark Nottingham's specification at                 *
 *	http://www.mnot.net/drafts/draft-nottingham-atom-format-00.html   *
 *	and Joe Gregorio's AtomAPI spec - draft 08.                       *
 *	http://www.bitworking.org/draft-gregorio-08.html (check URL)      *
 *                                                                    *
 *	15/12/2003 - updated for Atom 0.3 as specified by                 *
 *	Mark Nottingham's specification at                                *
 *	http://www.mnot.net/drafts/draft-nottingham-atom-format-02.html   *
 *	and Joe Gregorio's AtomAPI spec - draft 09.                       *
 *	http://bitworking.org/projects/atom/draft-gregorio-09.html        *
 *                                                                    *
 *********************************************************************/


// class AtomEntry holds the details of an entry
class AtomEntry {
	
	var $title;					// Reference to AtomContent - in case title is HTML.
	
	var $link        = array();	// array of AtomLink references (v0.3), 1 entry required
	
	var $modified    = "";		// Modified date - optional 1  - W3C Date Time String UTC
	var $author;				// AtomPerson object - required 1
	var $contributor = array();	// 1 or more AtomPerson objects
	var $id          = "";		// Permanently global unique identifier - 1 required
	var $issued      = "";		// Issued Date - 1 required - no TimeZone required
	var $created     = "";		// Created Date - optional 1 - W3C Date Time String UTC
	
	var $summary;				// Reference to AtomContent - in case title is HTML
	
	var $content     = array();	// content - 1 or more content objects
	var $generator;				// Entry generator - 1 required (from Joe's AtomAPI spec)
								

	// Temporary fields
	var $timestamp   = "";		// Used to create id and link URI and identifiers
}

class AtomContent {
	var $type        = "";		// required type attribute of the content default "text/plain"
	var $mode        = "";		// optional mode attribute (xml, escaped, base64) - default "xml"
	var $text        = "";		// DEPRECATED: content itself as text string. (even if it is XML)

	var $container;
	var $containerType;			// either "text", "dom" or "obj";
}

class AtomPerson {
	var $name        = "";		// Name - 1 required string
	var $url         = "";		// website url - 1 optional URI
	var $email       = "";		// email address - 1 optional email

}

class AtomGenerator {
	// From Joe Gregorio's AtomAPI specification (draft 8).
	// From Mark Nottingham's Atom spec (draft 03)

	var $generator   = "";		// Name of the generator - 1 required - human readable string
	
	// attributes
	var $version     = "";		// version of the generator - optional 1 string
	var $url         = "";		// URL of generator - optional 1. Must be URI
								// Joe Gregorio's spec makes this attribute mandatory
}


class AtomLink {
	// Attributes on the link element
	// From Mark Nottingham's Atom spec (draft 03)
	// http://www.mnot.net/drafts/draft-nottingham-atom-format-00.html
	
	/*
	 * link @rel list of values for application/x.atom+xml URIs
	 *		alternate, start, next, prev, service.edit, service.post, service.feed
	 */
	var $rel         = "";		// required 1
	var $type        = "";		// optional
	var $href        = "";		// required 1 URI
	var $title       = "";		// optional, but must be a string
}

class AtomFeed {
	//
	
	var $title		= "";
	
	var $entries 	= array();		// Array of AtomFeedEntry
	var $link    	= array();		// Array of link elements
	
	var $modified	= "";			// Date feed last modified
}

class AtomFeedEntry {
	// minimal version of the Atom Entry for feeds or search results

	var $id       = "";			// required
	var $link     = array();	// required
	var $title    = "";			// required
	var $issued   = "";			// required
	var $modified = "";			// may be present
	var $created  = "";			// may be present
	
}

?>