<?php

	// Download script
	// Usage: http://URL/{username}/files/{folder_id}/{file_id}/{filename}
	
	// Run includes
		require("../includes.php");
		
	// Initialise functions for user details, icon management and profile management
		run("userdetails:init");
		run("profile:init");
		run("files:init");
		
	// If an ID number for the file has been specified ...
	
		if (isset($_REQUEST['id'])) {
			$id = (int) $_REQUEST['id'];
			
	// ... and the file exists ...
			
			$file = db_query("select * from files where ident = $id");
			if (sizeof($file) > 0) {
				
				$file = $file[0];
				
	// ... and the owner of the file in the URL line hasn't been spoofed ...
				
				if (run("users:name_to_id",$_REQUEST['files_name']) == $file->owner) {
	
	// ... and the current user is allowed to access it ...
				
					if (run("users:access_level_check",$file->access) == true) {
									
	// Then output some appropriate headers and send the file data!

						$mimetype = run("files:mimetype:inline",$file->location);
						if ($mimetype == false) {
							$mimetype = "application/data";
						}

						header("Content-type: $mimetype");
						if ($mimetype == "application/data") {
							header('Content-Disposition: attachment');
						}
						readfile($file->location);
						
					}
					
				}
			}
		}

?>