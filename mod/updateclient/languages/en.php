<?php

	/**
	 * Update client language pack.
	 * 
	 * @package ElggUpdateClient
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Marcus Povey
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */	

	$english = array(
	
		'updateclient:label:core' => 'Core',
		'updateclient:label:plugins' => 'Plugins',
	
		'updateclient:settings:days' => 'Check for updates every',
		'updateclient:days' => 'days',
	
		'updateclient:settings:server' => 'Update server',
	
		'updateclient:message:title' => 'New version of Elgg released!',
		'updateclient:message:body' => 'A new version of Elgg (%s %s) codenamed "%s" has been released!
		
Go here to download: %s

Or scroll down and read the release notes:

%s',
	);
					
	add_translation("en", $english);
?>