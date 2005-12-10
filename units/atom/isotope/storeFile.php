<?php

/**********************************************************************
 * isoTope: an Atom-powered web framework                             *
 *                                                                    *
 * storeFile.php - File storage of Atom entries.                      *
 *                Implements the storeAPI                             *
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

include_once("storeBaseClass.php");

class FileStorage extends BaseClassStorage {
	var $blogDir;
	var $blogIndex;
	var $blogDirUrl;


	function init($config) {
		global $req;
		
		if ($config["storeDir"]) {
			$this->blogDir    = $config["storeDir"];
			$this->blogIndex  = $this->blogDir . "blog.idx";
			$this->blogDirUrl = $req->urlPrefix . $req->serviceRoot;
		} else {
			$req->addMsg("storeFile: init: No storeDir configured for File Storage");
		
		}

	}


	function getAtomEntry($entryId) {
		global $req;

		$req->addMsg("storeFile: getAtomEntry(" . $entryId . ")");

		// Take the file contents and return Atom classes
		if (file_exists($this->blogDir . $entryId . ".xml")) {
			$entryDom = new XML($this->blogDir . $entryId . ".xml");	
			$entry    = createAtomEntry($entryDom);	 
			##$req->addMsg("storeFile: EntryDom:\n" . $entryDom->toString());
		} else {
			returnPageNotFound("Atom " . $req->service . " entry (" . $entryId . ") was not found at this URL.");
		}
		return $entry;
	}


	function storeAtomEntry(&$entry) {
		global $req, $atomDomain;

		if (empty($req->atomResource["blogEntry"])) {
			// No Blog entry present, so this is a new blog entry.
			$req->addMsg("storeFile: storeAtomEntry - create new entry");

			$fileId = $this->createNewFileId($entry);
			$atomEntryLink = $this->blogDirUrl . $fileId;
			$this->initialiseNewAtomFields($entry, $atomEntryLink, true);

			// Set the id attribute
			$entry->id = createIdTag($fileId);

			$xml = atomEntryToXml($entry);
			$this->writeXmlToFile($this->blogDir . $fileId . ".xml", $xml);

			$req->addMsg("storeFile: File stored.. Updating Blog index");
			$fileRec = $this->createFileRecord($entry, $fileId, $atomEntryLink);
			$this->addBlogIndex($fileRec);
			$req->addMsg("storeFile: Update Index complete");

		} else {
			// Blog entry exists, so this is an update to an existing blog entry
			$req->addMsg("storeFile: storeAtomEntry - update existing entry (" . $req->atomResource["blogEntry"] . ")");
			$fileId = $req->atomResource["blogEntry"];

			if (file_exists($this->blogDir . $fileId . ".xml")) {
				$xml = atomEntryToXml($entry);
				$this->writeXmlToFile($this->blogDir . $fileId . ".xml", $xml);

				$req->addMsg("storeFile: File stored.. update Blog index");
				$fileRec = $this->createFileRecord($entry, $fileId, "");
				$this->updateBlogIndex($fileRec);
				$req->addMsg("storeFile: Update Index complete");
			} else {
				returnPageNotFound("Cannot update Atom " . $req->service . " entry " . $fileId . " - it does not exist");
			}
		}
	}


	function deleteAtomEntry($entryId) {
		global $req;

		$req->addMsg("storeFile: deleteAtomEntry(" . $entryId . ")");
		if (file_exists($this->blogDir . $entryId . ".xml")) {
			$this->deleteFromIndex($this->blogIndex, $entryId);
			$this->deleteFile($this->blogDir . $entryId . ".xml");
			$this->deleteComments($entryId);
		} else {
			returnPageNotFound("Cannot delete Atom " . $req->service . " entry " . $entryId . " - it does not exist");
		}
	}


	function getAtomEntries($first=10) {
		global $req, $blogDirUrl;

		$req->addMsg("storeFile: getAtomEntries");
		$feed = $this->getAtomFeedFromIndex($this->blogIndex, $this->blogDirUrl, $first, "D");
		return $feed;	
	}


	function getAtomComment($blogId, $commentId) {
		global $req;

		$req->addMsg("storeFile: getAtomComment(" . $blogId . ":" . $commentId . ")");

		if (file_exists($this->blogDir . $blogId . "/") && file_exists($this->blogDir . $blogId . "/" . $commentId . ".xml")) {
			// Take the file contents and return Atom classes
			$commentDom = new XML($this->blogDir . $blogId . "/" . $commentId . ".xml");	
			$comment    = createAtomEntry($commentDom);	 
			##$req->addMsg("storeFile: CommentDom:\n" . $commentDom->toString());
		} else {
			returnPageNotFound("Atom " . $req->service . " comment (" . $commentId . ") for entry " . $blogId . " was not found at this URL.");
		}

		return $comment;
	}


	function storeAtomComment($blogId, &$comment) {
		global $req, $atomDomain;

		$req->addMsg("storeFile: storeAtomComment(" . $blogId . ")");

		if ($req->atomResource["blogComment"]=="comments") {
			// This is a new comment.
			$req->addMsg("storeFile: storeAtomEntry - create new comment");

			$fileId = $this->createNewCommentFileId($blogId, $comment);
			$atomCommentLink = $this->blogDirUrl . $blogId . "/" .$fileId;
			$this->initialiseNewAtomFields($comment, $atomCommentLink, false);

			// Set the id attribute
			$comment->id = createIdTag($req->atomResource["blogEntry"] . "." . $fileId);

			$xml = atomEntryToXml($comment);
			$this->writeXmlToFile($this->blogDir . $blogId . "/" . $fileId . ".xml", $xml);

			$req->addMsg("storeFile: Comment stored.. Updating Comment index");
			$fileRec = $this->createFileRecord($comment, $fileId, $atomCommentLink);
			$this->addBlogCommentIndex($blogId, $fileRec);
			$req->addMsg("storeFile: Update Comment Index complete");
		} else {
			// Blog entry exists, so this is an update to an existing blog entry
			$req->addMsg("storeFile: storeAtomComment - update existing entry(" . $blogId . ":" . $req->atomResource["blogComment"] . ")");
			$fileId = $req->atomResource["blogComment"];

			if (file_exists($this->blogDir . $blogId . "/")) {
			
				if (file_exists($this->blogDir . $blogId . "/" . $fileId . ".xml")) {
					$xml = atomEntryToXml($comment);
					$this->writeXmlToFile($this->blogDir . $blogId . "/" . $fileId . ".xml", $xml);

					$req->addMsg("storeFile: File stored.. update Blog index");
					$fileRec = $this->createFileRecord($comment, $fileId, "");
					$this->updateBlogCommentIndex($blogId, $fileRec);
					$req->addMsg("storeFile: Update Index complete");
				} else {
					returnPageNotFound("Cannot update Atom comment " . $fileId . " for Atom entry " . $blogId . " - the comment does not exist");
				}
			} else {
				returnPageNotFound("Cannot update Atom " . $req->service . " comment " . $fileId . " for Atom entry " . $blogId . " - the entry does not exist");
			}
		}
	}


	function deleteAtomComment($blogId, $commentId) {
		global $req;

		$req->addMsg("storeFile: deleteAtomComment(" . $blogId . ":" . $commentId . ")");
		if (file_exists($this->blogDir . $blogId . ".xml")) {
			if (file_exists($this->blogDir . $blogId . "/" . $commentId . ".xml")) {
				$this->deleteFromIndex($this->blogDir . $blogId. "/comments.idx" , $commentId);
				$this->deleteFile($this->blogDir . $blogId . "/" . $commentId . ".xml");
			} else {
				returnPageNotFound("Cannot delete Atom " . $req->service . " comment " . $commentId . " for Atom entry " . $blogId . " - the comment does not exist");
			}
		} else {
			returnPageNotFound("Cannot delete Atom comment " . $commentId . " for Atom entry " . $blogId . " - the entry does not exist");
		}
	}


	function getAtomComments($entryId) {
		global $req;

		$req->addMsg("storeFile: getAtomComments(" . $entryId . ")");
		$feed = $this->getAtomFeedFromIndex($this->blogDir . $entryId . "/comments.idx", $this->blogDirUrl . $entryId . "/comments", 0, "A");
		return $feed;
	}


	/**************************************************
	 *
	 * Local file Id functions
	 * 
	 *************************************************/

	function createNewFileId(&$entry) {
		global $req;

		$fileName = $this->blogDir . $entry->timestamp . ".xml";

		// If the filename exists, then add one to the timestamp and try again
		$idx=0;
		while(file_exists($fileName)) {
			$idx++;
			$fileName = $this->blogDir . $entry->timestamp. "-" . $idx . ".xml";
		}

		if ($idx) { return $entry->timestamp . "-" . $idx; }
		return $entry->timestamp;
	}


	function createNewCommentFileId($blogId, &$comment) {
		global $req;

		$dirName = $this->blogDir . $blogId;

		// If this directory doesn't exist, then create it.
		if (file_exists($dirName) && is_dir($dirName)) {
			$req->addMsg("storeFile: Directory: " . $dirName . " exists");
		} else {
			$req->addMsg("storeFile: Directory: " . $dirName . " doesn't exist");
			mkdir($dirName, 0777);
		}

		// If the filename exists, then add one to the timestamp and try again
		$idx=1;
		$fileName = $dirName . "/" . $idx . ".xml";
		while(file_exists($fileName)) {
			$idx++;
			$fileName = $dirName . "/" . $idx . ".xml";
		}

		return $idx;
	}


	/**************************************************
	 *
	 * Local Index functions
	 * 
	 *************************************************/

	function addBlogIndex($f) {
		global $req;
		$this->appendToIndex($this->blogIndex, $f);
	}


	function updateBlogIndex($f) {
		global $req;
		return $this->updateIndex($this->blogIndex, $f);
	}


	function addBlogCommentIndex($blogId, $f) {
		global $req;
		$this->appendToIndex($this->blogDir . $blogId . "/comments.idx", $f);
	}


	function updateBlogCommentIndex($blogId, $f) {
		global $req;
		return $this->updateIndex($this->blogDir . $blogId . "/comments.idx", $f);
	}


	/**************************************************
	 *
	 * Generic Atom related functions
	 * 
	 *************************************************/

	// Initialise fields for a new atom entry
	function initialiseNewAtomFields(&$entry, $atomEntryLink, $isBlog) {
		global $req, $mimeType;

		##$req->addMsg(var_export($entry));

		// Remove any link attributes of rel="alternate" type="text/html"
		// and the service.* attributes
		removeAtomLink($entry, "alternate",       "text/html");
		removeAtomLink($entry, "service.edit",    $mimeType["atom"]);
		removeAtomLink($entry, "service.comment", $mimeType["atom"]);

		// Set the link attribute for HTML representation
		$link = new AtomLink();
		$link->rel   = "alternate";
		$link->type  = $mimeType["html"];
		$link->href  = $atomEntryLink;
		$link->title = "HTML representation of this entry";
		array_push($entry->link, $link);

		// Set the link attribute for editUrl
		$link->rel   = "service.edit";
		$link->type  = $mimeType["atom"];
		$link->href  = $atomEntryLink;
		$link->title = "Atom EditUrl";
		array_push($entry->link, $link);

		if ($isBlog) {
			// Set the link attribute for blog comments
			$link->rel   = "service.comment";
			$link->type  = $mimeType["atom"];
			$link->href  = $atomEntryLink . "/comments";
			$link->title = "Atom Entry Comment Url";
			array_push($entry->link, $link);
		}
	}


	function createFileRecord(&$entry, $fileId, $atomLink) {
		global $req;

		$fileRec = new FileRecord();
		$fileRec->fileId     = $fileId;
		$fileRec->createTs   = $entry->timestamp;
		$fileRec->modifyTs   = 0;
		$fileRec->title      = $entry->title->text;
		$fileRec->atomId     = $entry->id;
		$fileRec->atomLink   = $atomLink;
		$fileRec->atomIssued = $entry->issued;
		$fileRec->authorName = $entry->author->name;
		return $fileRec;
	}


	/**************************************************
	 *
	 * Generic File writing functions
	 * 
	 *************************************************/

	function writeXmlToFile($fileName, &$xml) {
		global $req;

		$handle = fopen($fileName, "w");
		fwrite($handle, $xml->toString());
		fclose($handle);
	}

	function deleteFile($fileName) {
		global $req;

		$req->addMsg("storeFile: Deleting file: " . $fileName);
		unlink($fileName);

		$req->addMsg("storeFile: Entry $fileName deleted.");
	}

	function deleteComments($blogId) {
		global $req;

		// First we need to delete all the files in the directory,
		// then remove the (emptied) directory
		if (is_dir($this->blogDir . $blogId . "/") && $handle = opendir($this->blogDir . $blogId . "/")) {	
			while (false !== ($file = readdir($handle))) { 
				if ($file != "." && $file != "..") { 
					$req->addMsg("Deleting file: " . $this->blogDir . $blogId . "/" . $file);
					unlink($this->blogDir . $blogId . "/" . $file);
				} 
			}
			closedir($handle); 

			// Now the directory is empty, we can delete it.
			$req->addMsg("Deleting directory: " . $this->blogDir . $blogId . "/");
			rmdir($this->blogDir . $blogId . "/");
		} else {
			$req->addMsg("ERROR: " . $this->blogDir . $blogId . "/" . " is not a directory");
		}

	}


	/**************************************************
	 *
	 * Generic Index functions
	 * 
	 *************************************************/

	function appendToIndex($indexFile, &$fileRec) {
		global $req;

		$handle = fopen($indexFile, "a");
		fwrite($handle, $fileRec->getFileRecord());
		fclose($handle);
	}


	function updateIndex($indexFile, &$fileRec) {
		global $req;

		// Load the entire index file
		$entryList = file($indexFile);

		// Find the array value of the entry
		$idx = 0;
		$len = count($entryList);
		$find = $fileRec->fileId . $fileRec->sep;
		$res = strpos($entryList[$idx], $find);
		while($res===false && $idx<$len) {
			$idx++;
			$res = strpos($entryList[$idx],  $find);
		}

		if ($idx >=0 && $idx < $len) {
			$req->addMsg("storeFile: Array: [" . $find . "][" . $idx . "][" . $entryList[$idx] . "]");
			// get the current entry
			$oldRec = new FileRecord();
			$oldRec->parseRecord($entryList[$idx]);

			// Merge old entry with the new entry
			$fileRec->createTs = $oldRec->createTs;
			$fileRec->atomLink = $oldRec->atomLink;

			// Update the entry		
			$entryList[$idx] = $fileRec->getFileRecord();

			// Save the entire blog index into a temporary file
			$record = implode("", $entryList);
			$handle = fopen($indexFile . ".tmp", "w");
			fwrite($handle, $record);
			fclose($handle);

			// Replace old blog index with the new one.
			unlink($indexFile);
			rename($indexFile . ".tmp", $indexFile);

			return true;
		} else {
			$req->addMsg("storeFile: ERROR: Blog entry not found");
			return false;	
		}
	}


	function deleteFromIndex($indexFile, $key) {
		global $req;

		// Load the entire index file
		$entryList = file($indexFile);

		// Find the array value of the entry
		$idx = 0;
		$len = count($entryList);
		$find = $key . "|-|";
		$res = strpos($entryList[$idx], $find);
		while($res===false && $idx<$len) {
			$idx++;
			$res = strpos($entryList[$idx],  $find);
		}

		if ($idx >= 0 && $idx < $len) {
			$req->addMsg("storeFile: Array: [" . $find . "][" . $idx . "][" . $entryList[$idx] . "]");

			// Delete the entry		
			unset($entryList[$idx]);

			// Save the entire blog index into a temporary file
			$record = implode("", $entryList);
			$handle = fopen($indexFile . ".tmp", "w");
			fwrite($handle, $record);
			fclose($handle);

			// Replace old blog index with the new one.
			unlink($indexFile);
			rename($indexFile . ".tmp", $indexFile);

			$req->addMsg("storeFile: Index record removed");		
		} else {
			$req->addMsg("storeFile: ERROR: Blog entry not found");		
		}
	}


	function getAtomFeedFromIndex($fileName, $postUrl, $noRec=0, $order="A") {
		global $req, $mimeType;

		$feed = new AtomFeed();
		$feed->modified=0;

		$servicePost = new AtomLink();
		$servicePost->rel  = "service.post";
		$servicePost->type = $mimeType["atom"];
		$servicePost->href = $postUrl;
		array_push($feed->link, $servicePost);

		$servicePost2 = new AtomLink();
		$servicePost2->rel  = "alternate";
		$servicePost2->type = $mimeType["html"];
		$servicePost2->href = rtrim($postUrl,"/") . ".html";
		array_push($feed->link, $servicePost2);

		$req->addMsg("storeFile: getAtomFeedFromIndex:[" . $fileName . "][" . $postUrl . "][" . $noRec . "][" . $order . "]");
		if (file_exists($fileName)) {
			$entryList = file($fileName);

			$req->addMsg("storeFile: No. entries: " . count($entryList));
			if ($noRec!=0 && count($entryList)>$noRec) {
				if ($order=="D") {
					$entryList=array_slice($entryList, -$noRec, $noRec);
				} else {
					$entryList=array_slice($entryList, 0, $noRec);
				}
			}

			foreach ($entryList as $entryRec) {
				if (($entryRec) && (substr($entryRec, 0, 1)!="#")) {
					$req->addMsg("storeFile: Acceptable msg: " . trim($entryRec));
					$entry = new FileRecord();
					$entry->parseRecord($entryRec);
					##$req->addMsg("storeFile: Entry:  \n\t\t[" . $entry->fileId . "]\n\t\t[" . $entry->createTs . "]\n\t\t[" . $entry->modifyTs . "]\n\t\t[" . $entry->title . "]\n\t\t[" . $entry->atomId . "]\n\t\t[" . $entry->atomLink . "]\n\t\t[" . $entry->atomIssued . "]");

					$atomEntry = new AtomFeedEntry();
					$atomEntry->id       = $entry->atomId;
					$atomEntry->created  = timestampToW3Date($entry->createTs);
					if (!empty($entry->modifyTs)) {
						$atomEntry->modified = timestampToW3Date($entry->modifyTs);
					} else {
						$atomEntry->modified = $atomEntry->created;					
					}
					
					// Update the feed modification if this entry is more recent.
					$lastTouched = $entry->createTs;
					if ($entry->modifyTs) {
						$lastTouched = $entry->modifyTs;
					}
					if ($lastTouched > $feed->modified) {
						$feed->modified = $lastTouched;
					}
					
					$atomEntry->link     = $entry->atomLink;
					$atomEntry->title    = $entry->title;
					$atomEntry->issued   = $entry->atomIssued;
					
					//Atom author details
					if ($entry->authorName) {
						$atomAuthor = new AtomPerson();
						$atomAuthor->name = $entry->authorName;
						$atomEntry->author = $atomAuthor;
					}

					if ($order=="D") {
						// Put entries in reverse order
						array_unshift($feed->entries, $atomEntry);
					} else {
						// Put entries in receipt order
						array_push($feed->entries, $atomEntry);
					}
				}
			}
		} else {
			$req->addMsg("storeFile: ERROR: Can't find file " . $fileName);
		}
		return $feed;
	}


}


/**************************************************
 *
 * Local classes
 * 
 *************************************************/

class FileRecord {
	var $sep = "|-|";

	var $fileId;
	var $createTs;
	var $modifyTs;
	var $title;
	var $atomId;
	var $atomLink;
	var $atomIssued;
	var $authorName;

	function getFileRecord() {
		return $this->fileId . $this->sep . $this->createTs . $this->sep . $this->modifyTs . $this->sep . $this->title . $this->sep . $this->atomId . $this->sep . $this->atomLink . $this->sep . $this->atomIssued . $this->sep . $this->authorName . $this->sep . "\n";
	}

	function parseRecord($fileRec) {
		##global $req;

		$tempArr = explode($this->sep, $fileRec);

		##$req->addMsg("storeFile: Inside: [" . $tempArr[0] . "][" . $tempArr[1] . "][" . $tempArr[2] . "][" . $tempArr[3] . "][" . $tempArr[4] . "][" . $tempArr[5] . "][" . $tempArr[6] . "][" . $tempArr[7] . "]");
		$this->fileId     = $tempArr[0];
		$this->createTs   = $tempArr[1];
		$this->modifyTs   = $tempArr[2];
		$this->title      = $tempArr[3];
		$this->atomId     = $tempArr[4];
		$this->atomLink   = $tempArr[5];
		$this->atomIssued = $tempArr[6];
		$this->authorName = $tempArr[7];
	}
}

?>