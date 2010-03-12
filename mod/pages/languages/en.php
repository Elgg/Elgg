<?php
	/**
	 * Elgg pages plugin language pack
	 * 
	 * @package ElggPages
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
			
			'pages' => "Pages",
			'pages:yours' => "Your pages",
			'pages:user' => "Pages home",
			'pages:group' => "Group pages",
			'pages:all' => "All site pages",
			'pages:new' => "New page",
			'pages:groupprofile' => "Group pages",
			'pages:edit' => "Edit this page",
			'pages:delete' => "Delete this page",
			'pages:history' => "Page history",
			'pages:view' => "View page",
			'pages:welcome' => "Edit welcome message",
			'pages:welcomemessage' => "Welcome to the pages tool of %s. This tool allows you to create pages on any topic and select who can view them and edit them.",
			'pages:welcomeerror' => "There was a problem saving your welcome message",
			'pages:welcomeposted' => "Your welcome message has been posted",
			'pages:navigation' => "Page navigation",
	        'pages:via' => "via pages",
			'item:object:page_top' => 'Top-level pages',
			'item:object:page' => 'Pages',
			'item:object:pages_welcome' => 'Pages welcome blocks',
			'pages:nogroup' => 'This group does not have any pages yet',
			'pages:more' => 'More pages',
			
		/**
		* River
		**/
		
		    'pages:river:annotate' => "a comment on this page",
		    'pages:river:created' => "%s wrote",
	        'pages:river:updated' => "%s updated",
	        'pages:river:posted' => "%s posted",
			'pages:river:create' => "a new page titled",
	        'pages:river:update' => "a page titled",
	        'page:river:annotate' => "a comment on this page",
	        'page_top:river:annotate' => "a comment on this page",
	
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
		 * Widget
		 **/
		 
		    'pages:num' => 'Number of pages to display',
			'pages:widget:description' => "This is a list of your pages.",
	
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