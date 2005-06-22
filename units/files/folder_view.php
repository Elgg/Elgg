<?php

	/*
	*	View a specific folder
	*	(Access rights are presumed)
	*/

		$url = url;
	
	// If a folder has been specified, convert it to integer;
	// otherwise assume we're in the root
	
		if (isset($parameter) && $parameter != "") {
			$folder = (int) $parameter;
		} else {
			$folder = -1;
		}
		
	// Find out who's the owner
	
		global $page_owner;
		$owner_username = run("users:id_to_name",$page_owner);
	
	// If we're not in the parent folder, provide a link to return to the parent
	
		global $this_folder;
		global $folder_name;
	
		if ($folder != -1) {
			
			$this_folder = db_query("select * from file_folders where ident = $folder and files_owner = $page_owner");
			$this_folder = $this_folder[0];
		
			$folder_name = stripslashes($this_folder->name);
				
		} else {
			
			$folder_name = "Root Folder";
			
		}
		
		$body = "<h2>" . $folder_name . "</h2>";
		
		if ($folder != -1) {
			
			$parent = (int) $this_folder->parent;
			
			if ($parent != -1) {
				$parent_details = db_query("select * from file_folders where ident = $parent and files_owner = $page_owner");
				$parent_details = $parent_details[0];
				$display_parent = $parent;
			} else {
				$parent_details->name = "root folder";
				$parent_details->ident = -1;
				$display_parent = "";
			}
			
			
			$body .= "<p><a href=\"".url."$owner_username/files/$display_parent\">";
			$body .= "Return to " . stripslashes($parent_details->name);
			$body .= "</a></p>";
		}
		
	// Firstly, get a list of folders
	
		$folders = db_query("select * from file_folders where parent = $folder and (" . run("users:access_level_sql_where") . ") and files_owner = $page_owner");
	
	// Display folders we actually have access to
	
		if (sizeof($folders) > 0) {
			
			$body .= <<< END

					<h3>
						Subfolders
					</h3>

END;
			
			foreach($folders as $folder_details) {
				
				if (run("users:access_level_check",$folder_details->access) == true) {
					$username = $owner_username;
					$ident = (int) $folder_details->ident;
					$name = stripslashes($folder_details->name);
					if ($folder_details->owner == $_SESSION['userid'] || $folder_details->files_owner == $_SESSION['userid']) {
						$foldermenu = <<< END
	[<a href="{$url}_files/action_redirection.php?action=delete_folder&delete_folder_id={$folder_details->ident}" onClick="return confirm('Are you sure you want to permanently delete this folder?')">Delete</a>]
END;
					} else {
						$foldermenu = "";
					}
					$keywords = run("display:output_field", array("","keywords","folder","folder",$ident,$folder_details->owner));
					if ($keywords) {
						$keywords = "Keywords: " . $keywords;
					}
					$body .= run("templates:draw", array(
									'context' => 'folder',
									'username' => $username,
									'url' => $url . "$username/files/$ident",
									'ident' => $ident,
									'name' => $name,
									'menu' => $foldermenu,
									'icon' => $url . "_files/folder.png",
									'keywords' => $keywords
								)
								);
				}
				
			}
		}
	
	// Then get a list of files
	
		$files = db_query("select * from files where folder = $folder and files_owner = $page_owner");	

	// View files we actually have access to

		if (sizeof($files) > 0) {
			
			foreach($files as $file) {
				
				if (run("users:access_level_check",$file->access) == true) {
					$username = $owner_username;
					$ident = (int) $file->ident;
					$folder = $file->folder;
					$title = stripslashes($file->title);
					$description = nl2br(stripslashes($file->description));
					$originalname = stripslashes($file->originalname);
					$filemenu = round(($file->size / 1000000),4) . "Mb ";
					$icon = $url . "_files/icon.php?id=" . $file->ident;
					/*
					$mimetype = run("files:mimetype:determine",$file->originalname);
					if ($mimetype == "image/jpeg" || $mimetype == "image/png") {
						$icon = $url . "units/phpthumb/phpThumb.php?src=" . $file->location;
					} else {
						$icon = $url . "_files/file.png";
					} */
					if ($file->owner == $_SESSION['userid'] || $file->files_owner == $_SESSION['userid']) {
						$filemenu .= <<< END
	[<a href="{$url}_files/edit_file.php?edit_file_id={$file->ident}">Edit</a>]
	[<a href="{$url}_files/action_redirection.php?action=delete_file&delete_file_id={$file->ident}" onClick="return confirm('Are you sure you want to permanently delete this file?')">Delete</a>]
END;
					} else {
						$filemenu = "";
					}
					$keywords = run("display:output_field", array("","keywords","file","file",$ident,$file->owner));
					if ($keywords) {
						$keywords = "Keywords: " . $keywords;
					}
					$body .= run("templates:draw", array(
									'context' => 'file',
									'username' => $username,
									'title' => $title,
									'ident' => $ident,
									'folder' => $folder,
									'description' => $description,
									'originalname' => $originalname,
									'url' => $url . "$username/files/$folder/$ident/$originalname",
									'menu' => $filemenu,
									'icon' => $icon,
									'keywords' => $keywords
								)
								);
				}
				
			}
			
		}
		
	// Deliver an apologetic message if there aren't any files or folders
	
		if (sizeof($files) ==0 && sizeof($folders) == 0) {
			
			$body .= "<p>This folder is currently empty.</p>";
			
		}
		
		$run_result .= $body;
		
?>