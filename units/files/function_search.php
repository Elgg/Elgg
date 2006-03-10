<?php

	global $search_exclusions;
	$url = url;

	if (isset($parameter) && $parameter[0] == "file") {
		
		$search_exclusions[] = "folder";
		$owner = (int) $_REQUEST['owner'];
		$accessline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ")";
		$accessline = str_replace("owner","tags.owner",$accessline);
		$searchline_files = "$accessline and tagtype = 'file' and owner = $owner and tag = '".addslashes($parameter[1])."'";
		$searchline_folders = "$accessline and tagtype = 'folder' and owner = $owner and tag = '".addslashes($parameter[1])."'";
		$file_refs = db_query("select ref from tags where $searchline_files");
		$folder_refs = db_query("select ref from tags where $searchline_folders");
		$searchline = "";
		$searchlist = array();
		if (sizeof($folder_refs) > 0) {
			foreach($folder_refs as $folder) {
				$searchlist[] = $folder->ref;
			}
			$searchline = " file_folders.ident IN (" . implode(", ", $searchlist) . ") ";
			$folders = db_query("select file_folders.name, users.name as userfullname, users.username, file_folders.ident from file_folders 
								join users on users.ident = file_folders.owner where ($searchline) 
								order by name asc");
			$run_result .= "<h2>" . sprintf(gettext("Folders owned by '%s' in category '%s'"),stripslashes($folders[0]->userfullname),$parameter[1])."</h2>\n";
			foreach($folders as $folder) {
				$run_result .= run("templates:draw", array(
									'context' => 'folder',
									'username' => stripslashes($folder->username),
									'url' => url.stripslashes($folder->username)."/files/".$folder->ident,
									'ident' => $folder->ident,
									'name' => stripslashes($folder->name),
									'menu' => "",
									'icon' => url . "_files/folder.png",
									'keywords' => ""
								)
								);
			}
		}
		$searchline = "";
		$searchlist = array();
		if (sizeof($file_refs) > 0) {
			foreach($file_refs as $file) {
				$searchlist[] = $file->ref;
			}
			$searchline = " files.ident IN (" . implode(", ", $searchlist) . ") ";
			$files = db_query("select files.*, users.username, users.name as userfullname from files
								join users on users.ident = files.owner where ($searchline) 
								order by title asc")
								or die(mysql_error());
			$run_result .= "<h2>" . sprintf(gettext("Files owned by %s in category '%s'"), stripslashes($files[0]->userfullname), $parameter[1])."</h2>\n";
			foreach($files as $file) {
				$run_result .= run("templates:draw", array(
									'context' => 'file',
									'username' => $file->username,
									'title' => stripslashes($file->title),
									'ident' => $file->ident,
									'folder' => $file->folder,
									'description' => stripslashes($file->description),
									'originalname' => stripslashes($file->originalname),
									'url' => url.$file->username."/files/".$file->folder."/".$file->ident."/".$file->originalname,
									'menu' => "",
									'icon' => url."_files/file.png",
									'keywords' => ""
								)
								);
			}
			$run_result .= "<p><small>[ <a href=\"".url.$files[0]->username . "/files/rss/" . $parameter[1] . "\">".sprintf(gettext("RSS feed for files owned by %s"), stripslashes($files[0]->userfullname)) . " in category '".$parameter[1]."'</a> ]</small></p>\n";
		}
		$searchline = " tagtype IN ('file','folder') and tag = '".addslashes($parameter[1])."'";
		$searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") and " . $searchline;
		$searchline = str_replace("owner","tags.owner",$searchline);
		$sql = "select distinct users.* from tags join users on users.ident = tags.owner where ($searchline)";
		if ($parameter[0] == "file") {
			$sql .= " and users.ident != " . $owner;
		}
		$users = db_query($sql);
		
		if (sizeof($users) > 0) {
			$run_result .= "<h2>". gettext("Users with files or folders in category") . " '". $parameter[1]."'</h2>\n";
			$body = "<table><tr>";
			$i = 1;
			$icon = "default.png";
			$defaulticonparams = @getimagesize(path . "_icons/data/default.png");
			
			foreach($users as $key => $info) {
				
				list($width, $height, $type, $attr) = $defaulticonparams;
				// $info = $info[0];
				// if ($info->icon != -1) {
					$icon = db_query("select filename from icons where ident = " . $info->icon);
					if (sizeof($icon) == 1) {
						$icon = $icon[0]->filename;
						if (!(list($width, $height, $type, $attr) = @getimagesize(path . "_icons/data/" . $icon))) {
							$icon = "default.png";
							list($width, $height, $type, $attr) = $defaulticonparams;
						}
					} else {
						$icon = "default.png";
					}
				// }
				
				if (sizeof($users) > 4) {
					$width = round($width / 2);
					$height = round($height / 2);
				}
				$friends_userid = $info->ident;
				$friends_name = htmlentities(stripslashes($info->name));
				$friends_menu = run("users:infobox:menu",array($info->ident));
				$link_keyword = urlencode($parameter[1]);
				$width = round($width / 2);
				$height = round($height / 2);
				$body .= <<< END
		<td align="center">
			<p>
			<a href="{$url}search/index.php?file={$link_keyword}&amp;owner={$friends_userid}">
			<img src="{$url}_icons/data/{$icon}" width="{$width}" height="{$height}" alt="{$friends_name}" border="0" /></a><br />
			<span class="userdetails">
				{$friends_name}
				{$friends_menu}
			</span>
			</p>
		</td>
END;
				if ($i % 5 == 0) {
					$body .= "\n</tr><tr>\n";
				}
				$i++;
			}
			$body .= "</tr></table>";
			$run_result .= $body;
		}
		
	}

?>