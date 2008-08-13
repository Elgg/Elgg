<?php
	/**
	 * Elgg pages plugin language pack
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
			
			'pages' => "Pages",
			'pages:yours' => "Your pages",
			'pages:user' => "Pages home",
			'pages:group' => "%s's pages",
			'pages:all' => "All site pages",
			'pages:new' => "New page",
			'pages:groupprofile' => "Group pages",
			'pages:edit' => "Edit this page",
			'pages:delete' => "Delete this page",
			'pages:history' => "Page history",
			'pages:view' => "View page",
			'pages:welcome' => "Edit welcome message",
			'pages:welcomeerror' => "There was a problem saving your welcome message",
			'pages:welcomeposted' => "Your welcome message has been posted",
			'pages:navigation' => "Page navigation",
	
			'item:object:page_top' => 'Top-level pages',
			'item:object:page' => 'Pages',
			'item:object:pages_welcome' => 'Pages welcome blocks',
	
	
		/**
		 * Form fields
		 */
	
			'pages:title' => 'Pages Title',
			'pages:description' => 'Your page entry',
			'pages:tags' => 'Tags',	
			'pages:access_id' => 'Access',
			'pages:write_access_id' => 'Write access',
		
		/**
		 * Status and error messages
		 */
			'pages:noaccess' => 'No access to page',
			'pages:cantedit' => 'You can not edit this page',
			'pages:saved' => 'Pages saved',
			'pages:notsaved' => 'Page could not be saved',
			'pages:notitle' => 'You must specify a title for your page.',
			'pages:delete:success' => 'Your page was successfully deleted.',
			'pages:delete:failure' => 'The page could not be deleted.',
	
		/**
		 * Page
		 */
			'pages:strapline' => 'Last updated %s by %s',
	
		/**
		 * History
		 */
			'pages:revision' => 'Revision created %s by %s',
	
		/**
		 * Submenu items
		 */
			'pages:label:view' => "View page",
			'pages:label:edit' => "Edit page",
			'pages:label:history' => "Page history",
	
		/**
		 * Sidebar items
		 */
			'pages:sidebar:this' => "This page",
			'pages:sidebar:children' => "Sub-pages",
			'pages:sidebar:parent' => "Parent",
	
			'pages:newchild' => "Create a sub-page",
			'pages:backtoparent' => "Back to '%s'",
	);
					
	add_translation("en",$english);
?>