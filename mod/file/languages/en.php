<?php
	/**
	 * Elgg file plugin language pack
	 * 
	 * @package ElggFile
	 */

	$english = array(
	
		/**
		 * Menu items and titles
		 */
	
			'file' => "Files",
			'files' => "Files",
			'file:yours' => "Your files",
			'file:yours:friends' => "Your friends' files",
			'file:user' => "%s's files",
			'file:friends' => "%s's friends' files",
			'file:all' => "All site files",
			'file:edit' => "Edit file",
			'file:list' => "list view",
			'file:gallery' => "gallery view",
			'file:gallery_list' => "Gallery or list view",
			'file:num_files' => "Number of files to display",
			'file:user:gallery'=>'View %s gallery', 
	        'file:via' => 'via files',
			'file:upload' => "Upload a file",
			'file:replace' => 'Replace file content (leave blank to not change file)',
	
			'file:group' => "Group files",
			'groups:enablefiles' => 'Enable group files',
			'file:newupload' => 'New file upload',
			'file:more' => "More files",

			'file:nogroup' => 'This group does not have any files yet',
			
			'file:file' => "File",
			'file:title' => "Title",
			'file:desc' => "Description",
			'file:tags' => "Tags",
	
			'file:types' => "Uploaded file types",
	
			'file:type:all' => "All files",
			'file:type:video' => "Videos",
			'file:type:document' => "Documents",
			'file:type:audio' => "Audio",
			'file:type:image' => "Pictures",
			'file:type:general' => "General",
	
			'file:user:type:video' => "%s's videos",
			'file:user:type:document' => "%s's documents",
			'file:user:type:audio' => "%s's audio",
			'file:user:type:image' => "%s's pictures",
			'file:user:type:general' => "%s's general files",
	
			'file:friends:type:video' => "Your friends' videos",
			'file:friends:type:document' => "Your friends' documents",
			'file:friends:type:audio' => "Your friends' audio",
			'file:friends:type:image' => "Your friends' pictures",
			'file:friends:type:general' => "Your friends' general files",
	
			'file:widget' => "File widget",
			'file:widget:description' => "Showcase your latest files",
	
			'file:download' => "Download this",
	
			'file:delete:confirm' => "Are you sure you want to delete this file?",
			
			'file:tagcloud' => "Tag cloud",
	
			'file:display:number' => "Number of files to display",
	
			'file:river:created' => "%s uploaded",
			'file:river:item' => "a file",
			'file:river:annotate' => "a comment on this file",

			'item:object:file' => 'Files',
			
	    /**
		 * Embed media
		 **/
		 
		    'file:embed' => "Embed media",
		    'file:embedall' => "All",
	
		/**
		 * Status messages
		 */
	
			'file:saved' => "Your file was successfully saved.",
			'file:deleted' => "Your file was successfully deleted.",
	
		/**
		 * Error messages
		 */
	
			'file:none' => "No files uploaded.",
			'file:uploadfailed' => "Sorry; we could not save your file.",
			'file:downloadfailed' => "Sorry; this file is not available at this time.",
			'file:deletefailed' => "Your file could not be deleted at this time.",
			'file:noaccess' => "You do not have permissions to change this file",
			'file:cannotload' => "There was an error loading the file",
			'file:nofile' => "You must select a file",
	);
					
	add_translation("en",$english);
?>