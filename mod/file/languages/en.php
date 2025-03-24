<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	'item:object:file' => 'File',
	'item:object:file:application' => 'Application',
	'item:object:file:archive' => 'Archive',
	'item:object:file:excel' => 'Excel',
	'item:object:file:image' => 'Image',
	'item:object:file:music' => 'Music',
	'item:object:file:openoffice' => 'OpenOffice',
	'item:object:file:pdf' => 'PDF',
	'item:object:file:ppt' => 'PowerPoint',
	'item:object:file:text' => 'Text',
	'item:object:file:vcard' => 'vCard',
	'item:object:file:video' => 'Video',
	'item:object:file:word' => 'Word',
	
	'file:upgrade:2022092801:title' => 'Move Files',
	'file:upgrade:2022092801:description' => 'Moves files uploaded using the file plugin to the file entity folder instead of the owner entity folder.',
	
	'collection:object:file' => 'Files',
	'collection:object:file:all' => "All site files",
	'collection:object:file:owner' => "%s's files",
	'collection:object:file:friends' => "Friends' files",
	'collection:object:file:group' => "Group files",
	'add:object:file' => "Upload a file",
	'edit:object:file' => "Edit file",
	'notification:object:file:create' => "Send a notification when a file is created",
	'notifications:mute:object:file' => "about the file '%s'",
	
	'entity:edit:object:file:success' => 'The file was saved successfully',
	
	'file:more' => "More files",
	'file:list' => "list view",

	'file:num_files' => "Number of files to display",
	'file:replace' => 'Replace file content (leave blank to not change file)',
	'file:list:title' => "%s's %s %s",

	'file:file' => "File",

	'file:list:list' => 'Switch to the list view',
	'file:list:gallery' => 'Switch to the gallery view',

	'file:type:' => 'Files',
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

	'widgets:filerepo:name' => "File widget",
	'widgets:filerepo:description' => "Showcase your latest files",

	'groups:tool:file' => 'Enable group files',
	'groups:tool:file:description' => 'Allow group members to share files in this group.',

	'river:object:file:create' => '%s uploaded the file %s',
	'river:object:file:comment' => '%s commented on the file %s',

	'file:notify:summary' => 'New file called %s',
	'file:notify:subject' => 'New file: %s',
	'file:notify:body' => '%s uploaded a new file: %s

%s

View and comment on the file:
%s',
	
	'notification:mentions:object:file:subject' => '%s mentioned you in a file',

	/**
	 * Status messages
	 */

	'file:saved' => "The file was successfully saved.",
	'entity:delete:object:file:success' => "The file was successfully deleted.",

	/**
	 * Error messages
	 */

	'file:none' => "No files.",
	'file:uploadfailed' => "Sorry; we could not save your file.",
	'file:noaccess' => "You do not have permissions to change this file",
	'file:cannotload' => "There was an error uploading the file",
);
