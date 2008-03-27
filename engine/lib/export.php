<?php
	/**
	 * Elgg Data import export functionality.
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 */

	/**
	 * Export a GUID.
	 * 
	 * This function exports a GUID and all information related to it in an XML format.
	 * 
	 * @param int $guid The GUID.
	 * @return xml 
	 */
	function export($guid)
	{
		// trigger serialise event

		// serialise resultant object

		/*
		  	XML will look something like this:


			<elgg>
				<elgguser uuid="skdfjslklkjsldkfsdfjs:556">
					<guid>556</guid>
					<name>Marcus Povey</name>

					...
				
				</elgguser>
				<annotation>
					<name>Foo</name>
					<value>baaaa</value>
				</annotation>
				<annotation>
					<name>Monkey</name>
					<value>bibble</value>
				</annotation>

				...

				<metadata>
					<name>Foo</name>
					<value>baaaa</value>
				</metadata>

				...

				<my_plugin>

					...

				</my_plugin>

			</elgg> 
		 
		 */
		
	}
	
	/**
	 * Import an XML serialisation of an object.
	 * This will make a best attempt at importing a given xml doc.
	 *
	 * @param string $xml
	 * @return int The new GUID of the object.
	 */
	function import($xml)
	{
		// import via object ? 

		// import via tag : so you pass a tag "<foo>" and all its contents out and something answers by handling it.
		// THis is recursive but bredth first.

		
	}

	/**
	 * Generate a UUID from a given GUID.
	 * 
	 * @param int $guid The GUID of an object.
	 */
	function guid_to_uuid($guid)
	{
		global $CONFIG;
		
		return md5($CONFIG->wwwroot . ":$guid");
	}
?>