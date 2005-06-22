<?php

	global $search_exclusions;

	if (isset($parameter) && $parameter[0] == "file") {
		
		$search_exclusions[] = "folder";
		$search_exclusions[] = "file";
		
		$owner = (int) $_REQUEST['owner'];
		$accessline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ")";
		$searchline_files = "$accessline and tagtype = 'file' and tag = '".addslashes($parameter[1])."'";
		$searchline_folders = "$accessline and tagtype = 'folder' and tag = '".addslashes($parameter[1])."'";
		$searchline_files = str_replace("access", "files.access", $searchline_files);
		$searchline_files = str_replace("owner", "files.owner", $searchline_files);
		$searchline_folders = str_replace("access", "file_folders.access", $searchline_folders);
		$searchline_folders = str_replace("owner", "file_folders.owner", $searchline_folders);
		$file_refs = db_query("select files.*, users.username, users.name as fullname, ref from tags left join files on files.ident = tags.ref left join users on users.ident = tags.owner where $searchline_files limit 50");
		$folder_refs = db_query("select file_folders.*, users.username, users.name as fullname, ref from tags left join file_folders on file_folders.ident = tags.ref left join users on users.ident = tags.owner where $searchline_folders limit 50");
		$searchline = "";
		if (sizeof($folder_refs) > 0) {
			foreach($folder_refs as $folder) {
				$run_result .= "\t<item>\n";
				$run_result .= "\t\t<title>File folder :: " . htmlentities(stripslashes($folder->fullname)) . " :: " . htmlentities(stripslashes($folder->name)) . "</title>\n";
				$run_result .= "\t\t<link>" . url  . htmlentities(stripslashes($folder->username)) . "/files/" . $folder->ident . "</link>\n";
				$run_result .= "\t</item>\n";
			}
		}
		if (sizeof($file_refs) > 0) {
			foreach($file_refs as $file) {
					$mimetype = run("files:mimetype:determine",$file->location);
					if ($mimetype == false) {
						$mimetype = "application/octet-stream";
					}
					$run_result .= "\t<item>\n";
					$run_result .= "\t\t<title>File :: " . htmlentities(stripslashes($file->fullname)) . " :: " . htmlentities(stripslashes($file->title)) . "</title>\n";
					$run_result .= "\t\t<link>" . url  . htmlentities(stripslashes($file->username)) . "/files/" . $file->folder . "/" . $file->ident . "/" . htmlentities(stripslashes($file->originalname)) . "</link>\n";
					$run_result .= "\t\t<enclosure url=\"" . url  . htmlentities(stripslashes($file->username)) . "/files/" . $file->folder . "/" . $file->ident . "/" . htmlentities(stripslashes($file->originalname)) . "\" length=\"". $file->size ."\" mimetype=\"$mimetype\" />\n";
					$run_result .= "\t</item>\n";
			}
		}
		
	}

?>