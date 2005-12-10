<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * serviceBlog.php - Atom blog service class.                         *
 *                   Implements the serviceAPI                        *
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

class BlogService extends BaseClassService {

	// Generic service variables
	var $serviceName;
	var $store;

	/*
	 * Initialise the service
	 */
	function init($serviceName) {
		global $req;

		$this->initUrl();
		$this->initStorage($serviceName);
	}


	/*
	 * doGet - handle the HTTP GET methods
	 */
	function doGet() {
		global $req, $mimeType;
		if ($req->atomResource["blogComment"]) {
			if ($req->atomResource["blogComment"]=="comments") {
				/*
				 * Atom Comment feed for a blog entry requested
				 */

				$req->addMsg("blogService: Get list of comments");
				$comments = $this->store->getAtomComments($req->atomResource["blogEntry"]);


				if ($req->responseType==$mimeType["atom"]) {
					$req->addMsg("blogService: Returning an Atom comment feed");

					$feedDom = atomFeedAsXml($comments);
					##$req->addMsg("var_dump:\n" . var_export($comments, TRUE));
					$req->addMsg("Dom of entry:\n" . $feedDom->toString());

					setStatusCode("200 Ok");
					setContentType("text/xml");

					print $feedDom->toString();
				} else {
					$req->addMsg("blogService: Returning a non-Atom comment feed");

					##$req->addMsg("var_dump:\n" . var_export($comments, TRUE));

					setStatusCode("200 Ok");
					setContentType("text/html");

					atomFeedToHtml($comments);
				}
			} else {
				/*
				 * Atom Comment is requested
				 */

				$req->addMsg("blogService: Get specific comment");

				$entry = $this->store->getAtomComment($req->atomResource["blogEntry"], $req->atomResource["blogComment"]);

				if ($req->responseType==$mimeType["atom"]) {
					$req->addMsg("blogService: Returning an Atom comment");

					$xml = atomEntryToXml($entry);

					setStatusCode("200 Ok");
					setContentType("text/xml");

					print $xml->toString();
				} else {
					$req->addMsg("blogService: Returning a non-Atom comment");

					setStatusCode("200 Ok");
					setContentType("text/html");
					atomEntryToHtml($entry);
				}
			}
		} elseif ($req->atomResource["blogEntry"]) {
			/*
			 * Atom Blog Entry is requested
			 */

			$req->addMsg("blogService: Get specific blog entry");

			$entry = $this->store->getAtomEntry($req->atomResource["blogEntry"]);

			if ($req->responseType==$mimeType["atom"]) {
				$req->addMsg("blogService: Returning an Atom entry");
				$xml = atomEntryToXml($entry);

				setStatusCode("200 Ok");
				setContentType("text/xml");

				print $xml->toString();
			} else {
				$req->addMsg("blogService: Returning a non-Atom entry");

				setStatusCode("200 Ok");
				setContentType("text/html");
				atomEntryToHtml($entry);
			}

		} else {
			/*
			 * Atom Feed is requested
			 */

			$req->addMsg("blogService: Get list of entries");

			if ($req->responseType==$mimeType["atom"]) {
				$req->addMsg("blogService: Returning an Atom feed");
				$entries = $this->store->getAtomEntries(4);
				$feedDom = atomFeedAsXml($entries);

				##$req->addMsg("var_dump:\n" . var_export($entries, TRUE));
				$req->addMsg("Dom of entry:\n" . $feedDom->toString());

				setStatusCode("200 Ok");
				setContentType("text/xml");

				print $feedDom->toString();
			} else {
				$req->addMsg("blogService: Returning a non-Atom feed");

				$entries = $this->store->getAtomEntries();

				##$req->addMsg("var_dump:\n" . var_export($entries, TRUE));

				setStatusCode("200 Ok");
				setContentType("text/html");

				atomFeedToHtml($entries);
			}
		}
	}


	/*
	 * doPost - handle the HTTP POST methods
	 */
	function doPost() {
		global $req, $mimeType, $urlPrefix;
		if ($req->atomResource["blogComment"]) {
			if ($req->atomResource["blogComment"]=="comments") {
				/*
				 * Post a new comment to a blog entry
				 */

				$req->addMsg("atomBlog: Add a new comment");

				if ($req->responseType==$mimeType["atom"]) {
					$comment = createAtomEntry($req->dom);
					$this->initialiseNewBlogEntry($comment);

					// To be initialised / populated by storage module before storing:
					// * Create an id. -- done by storage
					// * Set / overwrite link -- done by storage
					$this->store->storeAtomComment($req->atomResource["blogEntry"], $comment);
					redirectNewAtomEntry($comment);

				} else {
					$req->addMsg("atomBlog: Comment not of type " . $mimeType["atom"]);
					returnDataError("Atom " . $this->serviceName . " comment needs to be of type " . $mimeType["atom"]);
				}
			} else {
				/*
				 * Invalid URL to post to
				 */

				$req->addMsg("atomBlog: Invalid Method");
				returnMethodNotAllowed("Posting to an Atom " . $this->serviceName . " comment (" . $req->atomResource["blogComment"] . ") is not allowed. New Atom " . $this->serviceName . " comments should be posted to " . $urlPrefix . "/" . $this->serviceName . "/" . $req->atomResource["blogEntry"] . "/comments");
			}
		} elseif ($req->atomResource["blogEntry"]) {
			/*
			 * Invalid URL to post to
			 */

			$req->addMsg("atomBlog: Invalid Method");
			returnMethodNotAllowed("Posting to an Atom " . $this->serviceName . " entry (" . $req->atomResource["blogEntry"] . ") is not allowed. New Atom " . $this->serviceName . " entries should be posted to " . $urlPrefix . "/" . $this->serviceName);
		} else {
			/*
			 * New Blog entry Posted
			 */

			$req->addMsg("atomBlog: Add new blog entry");

			if ($req->responseType==$mimeType["atom"]) {
				$entry = createAtomEntry($req->dom);
				$this->initialiseNewBlogEntry($entry);

				// To be initialised / populated by storage module before storing:
				// * Create an id. -- done by storage
				// * Set / overwrite link -- done by storage
				$this->store->storeAtomEntry($entry);
				redirectNewAtomEntry($entry);

			} else {
				$req->addMsg("atomBlog: Entry not of type " . $mimeType["atom"]);
				returnDataError("Atom " . $this->serviceName . " entry needs to be of type " . $mimeType["atom"]);
			}
		}
	}


	/*
	 * doPut - handle the HTTP PUT methods
	 */
	function doPut() {
		global $req, $mimeType;
		if ($req->atomResource["blogComment"] && $req->atomResource["blogComment"] != "comments") {
			/*
			 * Atom Comment updated
			 */

			$req->addMsg("atomBlog: Updating a comment");

			if ($req->responseType==$mimeType["atom"]) {
				$req->addMsg("atomBlog: Getting old comment from storage");
				$oldComment = $this->store->getAtomComment($req->atomResource["blogEntry"], $req->atomResource["blogComment"]);

				$req->addMsg("atomBlog: Getting new comment from request");
				$newComment = createAtomEntry($req->dom);

				$req->addMsg("atomBlog: Merging Atom comments");
				$comment = mergeAtomEntry($oldComment, $newComment);

				$req->addMsg("atomBlog: Storing Atom comment");
				$this->store->storeAtomComment($req->atomResource["blogEntry"], $comment);
			} else {
				$req->addMsg("atomBlog: Comment not of type " . $mimeType["atom"]);
				returnDataError("Atom " . $this->serviceName . " comment needs to be of type " . $mimeType["atom"]);
			}
		} elseif ($req->atomResource["blogEntry"]) {
			/*
			 * Updating a blog entry
			 */

			$req->addMsg("atomBlog: Updating blog entry");

			if ($req->responseType==$mimeType["atom"]) {
				$req->addMsg("atomBlog: Getting old blog entry from storage");
				$oldEntry = $this->store->getAtomEntry($req->atomResource["blogEntry"]);

				$req->addMsg("atomBlog: Getting new blog entry from request");
				$newEntry = createAtomEntry($req->dom);

				$req->addMsg("atomBlog: Merging Atom entries");
				$entry = mergeAtomEntry($oldEntry, $newEntry);

				$req->addMsg("atomBlog: Storing Atom entry");
				$this->store->storeAtomEntry($entry);
			} else {
				$req->addMsg("atomBlog: Entry not of type " . $mimeType["atom"]);
				returnDataError("Atom " . $this->serviceName . " entry needs to be of type " . $mimeType["atom"]);
			}
		} else {
			/*
			 * Invalid URL to do a PUT on
			 */

			$req->addMsg("atomBlog: Invalid Method");
			returnMethodNotAllowed("Cannot put an updated Atom " . $this->serviceName . " entry on the feed Url. Use the URL of the Atom " . $this->serviceName . " entry instead.");
		}
	}


	/*
	 * doDelete - handle the HTTP DELETE methods
	 */
	function doDelete() {
		global $req;
		if ($req->atomResource["blogComment"]) {
			/*
			 * Delete a particular blog comment
			 */

			$req->addMsg("atomBlog: Deleting a specific comment");
			$this->store->deleteAtomComment($req->atomResource["blogEntry"], $req->atomResource["blogComment"]);
			returnStatusCode("200 Ok");

		} elseif ($req->atomResource["blogEntry"]) {
			/*
			 * Delete a particular blog entry
			 */

			$req->addMsg("atomBlog: Deleting a specific blog entry");
			$this->store->deleteAtomEntry($req->atomResource["blogEntry"]);
			returnStatusCode("200 Ok");

		} else {
			/*
			 * Invalid URL to do a DELETE on
			 */

			$req->addMsg("atomBlog: Invalid Method");
			returnMethodNotAllowed("Cannot delete an Atom " . $this->serviceName . " feed.");
		}
	}


	/*
	 * doOptions - handle the HTTP OPTIONS methods
	 */
	function doOptions() {
		global $req;
		$req->addMsg("BlogService: doOptions not implemented yet");
	}
	

	/**************************************************
	 *
	 * Local Atom Blog service functions
	 *
	 *************************************************/


	/*
	 * Function to initialise the Url directory object 
	 * which is used to define the request. For example,
	 * using the blog service which receives the url
	 *   /blog/HelloWorld/2 maps to
	 *   /{serviceName}/{blogEntry}/{blogComment}
	 */
	function initUrl() {
		global $req, $rootUri;
		
		$pageUri = $req->serviceRoot;
		$atomResource = array();
		
		if ($req->breadcrumb) {
			foreach ($req->breadcrumb as $key) {
				if ($atomResource["blogEntry"]) {
					if ($atomResource["blogComment"]) {
						// Now what?
						// must be more than two levels deep
						// so lets ignore it
					} else {
						// The URL specifies a comment
						$atomResource["blogComment"] = $key;
						$pageUri .= "/" . $key;
					}
				} else {
					// The URL specifies a blog entry
					$atomResource["blogEntry"] = $key;								
					$pageUri .= $key;
				}
			}
			
			$req->atomResource = $atomResource;
			$req->pageUri      = $pageUri;
		}

	}
	
	/* 
	 * Function to initialise the storage for this particular
	 * blog service. The configuration items can be retrieved
	 * from the $services[serviceName] global array
	 */
	function initStorage($serviceName) {
		global $req, $services;
		
		$this->serviceName = $serviceName;
		if ($services[$serviceName]["store"]) {
			$store = getAtomStorageHandler($services[$serviceName]["store"]);
			$store->init($services[$serviceName]);
			$this->store = $store;
		} else {
			$req->addMsg("Blog.initStorage: no storage options configured");
		}
	}



	// Initialise the date fields in a new blog or comment
	function initialiseNewBlogEntry(&$entry) {
		global $req;

		// Get a timestamp for this entry - its a temporary field that can be 
		// used by the storage module to create link and id values.
		$entry->timestamp = time();
		$currentDate = timestampToW3Date($entry->timestamp);

		// Issued element has to be present, but it may be
		// empty. So if issued is blank populate it with the 
		// current timestamp.
		if (empty($entry->issued)) {
			$entry->issued = $currentDate;
			$req->addMsg("Issued element empty. Setting it to current timestamp: " . $entry->issued);
		}

		// Set created if non-existant
		// If the created element doesn't exist, then create it
		if (empty($entry->created)) {
			$entry->created = $currentDate;
			$req->addMsg("No created date set. Setting it to current timestamp: " . $entry->created);			
		}

		// Since the entry is now being created, there won't be a modification date
		$entry->modified = "";
		$req->addMsg("Setting the modified date to the created date");
	}





}

?>