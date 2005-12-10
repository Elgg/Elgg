<?php

	global $folder;
	global $page_owner;
		
	$url = url;
	
	if (isset($_REQUEST['edit_file_id'])) {
		$file_id = (int) $_REQUEST['edit_file_id'];
	
		$file_details = db_query("select files.*, users.username from files left join users on users.ident = files.owner where files.ident = $file_id");
		if (sizeof($file_details) > 0 && (run("permissions:check", array("files:edit",$file_details[0]->owner)) || run("permissions:check", array("files:edit",$file_details[0]->files_owner)))) {
			$file = $file_details[0];
			
			$page_owner = $file->files_owner;
			
			$description = stripslashes($file->description);
			$title = htmlentities(stripslashes($file->title));
	              
            $fileLabel = gettext("File title:"); // gettext variable
			$body = <<< END
			<form action="{$url}_files/action_redirection.php" method="post">
			<table>
				<tr>
					<td>
						<label for="edit_file_title">
							<p>$fileLabel</p>
						</label>
					</td>
					<td>
END;
						$body .= run("display:input_field",array("edit_file_title",$title,"text"));
						$fileDesc = gettext("File description:"); // gettext variable
                                         $body .= <<< END
					</td>
				</tr>
				<tr>
					<td>
						<label for="edit_file_description">
							<p>$fileDesc</p>
						</label>
					</td>
					<td>
END;
						$body .= run("display:input_field",array("edit_file_description",$description,"longtext"));
						$fileAccess = gettext("Access restrictions:"); // gettext variable
                                         $body .= <<< END
					</td>
				</tr>
				<tr>
					<td>
						<label for="edit_file_access">
							<p>$fileAccess</p>
						</label>
					</td>
					<td>
END;
						$body .= run("display:access_level_select",array("edit_file_access",$file->access));
						$fileFolder = gettext("File folder:"); // gettext variable
                                         $body .= <<< END
					</td>
				</tr>
				<tr>
					<td>
						<label for="edit_file_folder">
							<p>$fileFolder</p>
						</label>
					</td>
					<td>
END;
						$body .= run("folder:select", array("edit_file_folder",$file->files_owner,$file->folder));
						$keywords = gettext("Keywords (comma separated):"); // gettext variable
                                         $body .= <<< END
					</td>
				</tr>
				<tr>
					<td>
						<label for="edit_file_keywords">
							<p>$keywords</p>
						</label>
					</td>
					<td>
END;
						$body .= run("display:input_field",array("edit_file_keywords","","keywords","file",$file_id));
						$body .= <<< END
					</td>
				</tr>
END;
	
			$body .= run("metadata:edit",$file_id);
                     
                     $saveChanges = gettext("Save changes"); // gettext variable
			$body .= <<< END
				
				<tr>
					<td colspan="2" align="center"><br />
						<input type="hidden" name="folder" value="{$folder}" />
						<input type="hidden" name="file_id" value="{$file_id}" />
						<input type="hidden" name="action" value="files:editfile" />
						<input type="submit" value=$saveChanges />
					</td>
				</tr>
	
			</table>
END;
	
			$run_result .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => gettext("Edit ") . $title,
								'contents' => $body
								)
								);
	
			$run_result .= <<< END
		</form>
END;
			} else {
				echo "?";
			}
		}

?>