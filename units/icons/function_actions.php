<?php

	// Action parser for icons

	// Edit existing icons ...
		if (isset($_POST['action']) && $_POST['action'] == "icons:edit" && logged_on) {

	// Set a new default!
	
			if (isset($_POST['defaulticon'])) {
				$icondefault = (int) $_POST['defaulticon'];
				if ($icondefault == -1) {
					db_query("update users set icon = -1 where ident = " . $_SESSION['userid']);
					$_SESSION['icon'] = "default.png";
				} else {
					$iconfilename = db_query("select filename from icons where ident = $icondefault and owner = " . $_SESSION['userid']);
					if (sizeof($iconfilename) == 1) {
						$iconfilename = $iconfilename[0]->filename;
						$_SESSION['icon'] = $iconfilename;
						db_query("update users set icon = $icondefault where ident = " . $_SESSION['userid']);
					}
				}
			}
			
	// Change their descriptions!
	
			if (isset($_POST['description']) && sizeof($_POST['description'] > 0)) {
				foreach($_POST['description'] as $iconid => $newdescription) {
					$iconid = (int) $iconid;
					$newdescription = addslashes($newdescription);
					$result = db_query("select description from icons where ident = $iconid and owner = " . $_SESSION['userid']);
					if (sizeof($result) > 0) {
						if ($result[0]->description != $newdescription) {
							db_query("update icons set description = '$newdescription' where ident = $iconid");
						}
					}
				}
			}
			
	// Delete them!
			
			if (isset($_POST['icons_delete'])) {
				if (sizeof($_POST['icons_delete']) > 0) {
					foreach($_POST['icons_delete'] as $delete_icon) {
						$delete_icon = (int) $delete_icon;
						$result = db_query("select filename from icons where ident = $delete_icon and owner = " . $_SESSION['userid']);
						if (sizeof($result) == 1) {
							db_query("delete from icons where ident = $delete_icon");
							@unlink(path . "_icons/data/" . $result[0]->filename);
						}
						if ($result[0]->filename = $_SESSION['icon']) {
							db_query("update users set icon = -1 where ident = " . $_SESSION['userid']);
							$_SESSION['icon'] = "default.png";
						}
					}
					$messages[] = "Your selected icons were deleted.";
				}
			}
			
		}
	
	// Upload a new icon ...
		if (isset($_POST['action']) && $_POST['action'] == "icons:add" && logged_on) {
		
			if (isset($_POST['icondescription']) && isset($_POST['icondefault'])
				&& isset($_FILES['iconfile']['name'])) {
				
				$messages[] = "Attempting to upload icon file ...";
				
				$ok = true;
				$templocation = $_FILES['iconfile']['tmp_name'];
				
				if ($_FILES['iconfile']['size'] >= 30000 || $_FILES['iconfile']['size'] == 0) {
					$messages[] = "The uploaded icon file was too large. The limit is 30k.";
					$ok = false;
				}
				if ($ok == true) {
					$numicons = db_query("select count(ident) as numicons from icons where owner = " . $_SESSION['userid']);
					$numicons = (int) $numicons[0]->numicons;
					if ($numicons >= $_SESSION['icon_quota']) {
						$ok = false;
						$messages[] = "You have already met your icon quota. You must delete some icons before you can upload any new ones.";
					}
				}
				if ($ok == true) {
					$imageattr = @getimagesize($templocation);
					if ($imageattr == false) {
						$ok = false;
						$messages[] = "The uploaded icon file was invalid. Please ensure you are using JPEG, GIF or PNG files.";
					}
				}
				if ($ok == true) {
					if ($imageattr[0] > 100 || $imageattr[1] > 100) {
						$ok = false;
						$messages[] = "The uploaded icon file was too large. Files must have maximum dimensions of 100x100.";
					}
				}
				if ($ok == true && ($imageattr[2] > 3 || $imageattr[2] < 1)) {
					$message[] = "The uploaded icon file was in an image format other than JPEG, GIF or PNG. These are unsupported at present.";
				} else if ($ok == true) {
					switch($imageattr[2]) {
						case "1":	$file_extension = ".gif";
									break;
						case "2":	$file_extension = ".jpg";
									break;
						case "3":	$file_extension = ".png";
									break;
					}
					$save_file = $_SESSION['userid'] . "_" . time() . $file_extension;
					$save_location = path . "_icons/data/" . $save_file;
					if (move_uploaded_file($_FILES['iconfile']['tmp_name'], $save_location)) {
						
						$filedescription = addslashes($_POST['icondescription']);
						db_query("insert into icons set filename = '$save_file', owner = " . $_SESSION['userid'] . ", description = '$filedescription'");
						if ($_POST['icondefault'] == "yes") {
							$ident = (int) db_id();
							db_query("update users set icon = $ident where ident = " . $_SESSION['userid']);
							$_SESSION['icon'] = $save_file;
						}
						$messages[] = "Your icon was uploaded successfully.";
												
					} else {
						$messages[] = "An unknown error occurred when saving your icon. If this problem persists, please let us know and we'll do all we can to fix it quickly.";
					}

				}
				
			}
		
		}

?>