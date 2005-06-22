<?php

	global $folder;
	global $page_owner;
		
	$url = url;
	
	if (isset($_REQUEST['edit_file_id'])) {
		$file_id = (int) $_REQUEST['edit_file_id'];
	
		$file_details = db_query("select files.*, users.username from files left join users on users.ident = files.owner where files.ident = $file_id and files.owner = " . $_SESSION['userid']);
		if (sizeof($file_details) > 0) {
			$file = $file_details[0];
			
			$page_owner = $file->files_owner;
			
			$description = stripslashes($file->description);
			$title = htmlentities(stripslashes($file->title));
	
			$body = <<< END
			<form action="{$url}_files/action_redirection.php" method="post">
			<table>
				<tr>
					<td>
						<label for="edit_file_title">
							File title:
						</label>
					</td>
					<td>
END;
						$body .= run("display:input_field",array("edit_file_title",$title,"text"));
						$body .= <<< END
					</td>
				</tr>
				<tr>
					<td>
						<label for="edit_file_description">
							File description:
						</label>
					</td>
					<td>
END;
						$body .= run("display:input_field",array("edit_file_description",$description,"longtext"));
						$body .= <<< END
					</td>
				</tr>
				<tr>
					<td>
						<label for="edit_file_access">
							Access restrictions:
						</label>
					</td>
					<td>
END;
						$body .= run("display:access_level_select",array("edit_file_access",$file->access));
						$body .= <<< END
					</td>
				</tr>
				<tr>
					<td>
						<label for="edit_file_folder">
							File folder
						</label>
					</td>
					<td>
END;
						$body .= run("folder:select", array("edit_file_folder",$file->files_owner,$file->folder));
						$body .= <<< END
					</td>
				</tr>
				<tr>
					<td>
						<label for="edit_file_keywords">
							Keywords (comma separated):
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

			$body .= <<< END
				
				<tr>
					<td colspan="2" align="center"><br />
						<input type="hidden" name="folder" value="{$folder}" />
						<input type="hidden" name="file_id" value="{$file_id}" />
						<input type="hidden" name="action" value="files:editfile" />
						<input type="submit" value="Save changes" />
					</td>
				</tr>
	
			</table>
END;
	
			$run_result .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => "Edit " . $title,
								'contents' => $body
								)
								);
	
			$run_result .= <<< END
		</form>
END;
			}
		}

?>