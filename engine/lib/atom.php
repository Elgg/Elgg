<?php
	/**
	 * OpenDD over Atom PHP Library.
	 * Provides Atom wrappers for 
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @version 0.1
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	include_once("opendd.php");
	
	/**
	 * A Wrapper Factory which Constructs a wrapper appropriate for drawing
	 * ODD elements as Atom.
	 */
	class ODDAtomWrapperFactory extends ODDWrapperFactory
	{
		function getElementWrapper($element)
		{
			if ($element instanceof ODDDocument)
				return new ODDAtomDocumentWrapper();
				
			if ($element instanceof ODDEntity)
				return new ODDAtomEntityWrapper();
				
			if ($element instanceof ODDMetaData)
				return new ODDAtomMetaDataWrapper();
				
			if ($element instanceof ODDRelationship)
				return new ODDAtomRelationshipWrapper();
			
			throw new DataFormatException("Element could not be wrapped.");
		}
	}
	
	/**
	 * Atom document wrapper
	 */
	class ODDAtomDocumentWrapper extends ODDDocumentWrapper 
	{
		function wrap($element)
		{
			global $CONFIG;
			
			$wrapped = "";
			
			// Sanity check
			if (!($element instanceof ODDDocument))
				throw new DataFormatException("Element being wrapped is not an ODDDocument");
			
			// Create a factory
			$factory = new ODDAtomWrapperFactory();
			
			// Head
			$wrapped .= "<feed>\n";
			$wrapped .= "<id>".urlencode(current_page_url())."</id>\n";
			$wrapped .= "<updated>".date(DATE_ATOM)."</updated>\n";
			$wrapped .= "<author><name>".$_SESSION['user']->name."</name></author>\n";
			$wrapped .= "<title>OpenDD-over-Atom feed</title>\n";

			// Itterate
			foreach ($element as $e)
			{
				$wrapper = $factory->getElementWrapper($e);
				$wrapped .= $wrapper->wrap($e);
			}

			// Tail
			$wrapped .= "</feed>\n";
			
			return $wrapped;
		}
	}
	
	/**
	 * Atom entity wrapper
	 */
	class ODDAtomEntityWrapper extends ODDEntityWrapper
	{
		function wrap($element)
		{
			$wrapped = "";
			
			// Sanity check
			if (!($element instanceof ODDEntity))
				throw new DataFormatException("Element being wrapped is not an ODDEntity");
				
			$wrapped .= "<entry>\n";
			
			$wrapped .= "<id>".$element->getAttribute('uuid')."?view=atom"."</id>\n";
			$wrapped .= "<published>".date(DATE_ATOM)."</published>\n";
			$wrapped .= "<title>Entity</title>\n";
			$wrapped .= "<author><name>".$_SESSION['user']->name."</name></author>\n";
			
			$wrapped .= "<content type=\"text/xml\">\n";
			$wrapped .= "$element\n";
			$wrapped .= "</content>\n";

			$wrapped .= "</entry>\n";
			
			return $wrapped;
		}
		
	}
	
	/**
	 * Atom metadata wrapper
	 */
	class ODDAtomMetaDataWrapper extends ODDMetaDataWrapper 
	{
		function wrap($element)
		{
			$wrapped = "";
			
			// Sanity check
			if (!($element instanceof ODDMetaData))
				throw new DataFormatException("Element being wrapped is not an ODDMetaData");
				
			$wrapped .= "<entry>\n";
			
			$wrapped .= "<id>".$element->getAttribute('uuid')."?view=atom"."</id>\n";
			$wrapped .= "<published>".date(DATE_ATOM)."</published>\n";
			$wrapped .= "<title>Entity</title>\n";
			$wrapped .= "<author><name>".$_SESSION['user']->name."</name></author>\n";
			
			$wrapped .= "<content type=\"text/xml\">\n";
			$wrapped .= "$element\n";
			$wrapped .= "</content>\n";

			$wrapped .= "</entry>\n";
			
			return $wrapped;
		}
	}
	
	/**
	 * Atom Relationship wrapper.
	 */
	class ODDAtomRelationshipWrapper extends ODDRelationshipWrapper 
	{
		function wrap($element)
		{
			$wrapped = "";
			
			// Sanity check
			if (!($element instanceof ODDRelationship))
				throw new DataFormatException("Element being wrapped is not an ODDRelationship");
				
			$wrapped .= "<entry>\n";
			
			$wrapped .= "<id></id>\n";
			$wrapped .= "<published>".date(DATE_ATOM)."</published>\n";
			$wrapped .= "<title>Entity</title>\n";
			$wrapped .= "<author><name>".$_SESSION['user']->name."</name></author>\n";
			
			$wrapped .= "<content type=\"text/xml\">\n";
			$wrapped .= "$element\n";
			$wrapped .= "</content>\n";

			$wrapped .= "</entry>\n";
			
			return $wrapped;
		}
	}
?>