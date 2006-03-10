<?php

	// Upgrade script

	// Display errors
		error_reporting(E_ERROR | E_WARNING | E_PARSE);
	
	// Set time limit to 20 minutes
		@set_time_limit (1200);
	
	// Require main includes
		@require("includes.php");
		
	// Display message
		echo "<h1>" . gettext("Upgrading ...") . "</h1>";
		
	// Alter file repository to remove path information
		echo "<h2>" . gettext("Adjusting file repository information") . "</h2>";
		
	// Get all files with path in the location
		$files = db_query("select ident, location from files where location like \"".path."%\"");
		
	// If we have any...
		if ($files != false && sizeof($files) > 0) {
			foreach ($files as $file) {
				
				$ident = $file->ident;
				$filename = str_replace(path, "", $file->location);
				
				db_query("update files set location = '$filename' where ident = $ident");
				echo "<p>" . sprintf(gettext("Updating file %d: %s"),$ident,$filename) . "</p>";
				
			}
		}
		
	// Publish RSS
		echo "<h2>" . gettext("Publishing RSS") . "</h2>";
		
	// Get all users
		
		$users = db_query("select ident from users");
		
	// Iterate through them, publishing their RSS files
		
		foreach($users as $user) {
	
			$ident = $user->ident;
			
			echo "<p>" . sprintf(gettext("Publishing RSS for user %d"),$ident) . "</p>";
			
			$rssresult = run("weblogs:rss:publish", array($ident, false));
			$rssresult = run("files:rss:publish", array($ident, false));
			$rssresult = run("profile:rss:publish", array($ident, false));
			
		}
		
	// Display finishing message

		echo "<hr />";
		echo "<p>" . gettext("Done. You must now delete upgrade.php from your root Elgg folder.") . "</p>";
	
?>