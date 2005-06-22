<?php

	global $folder;
	global $page_owner;
	
	$url = url;
	
	if ($folder != -1) {
		global $this_folder;
		global $folder_name;
		$folder_name = htmlentities($folder_name);
		$folder_details = db_query("select * from file_folders where ident = " . $this_folder);
		$folder_details = $folder_details[0];
		if ($folder_details->owner == $_SESSION['userid'] || $folder_details->files_owner == $_SESSION['userid']) {
		$run_result .= <<< END
	<h2>
		Edit this folder
	</h2>
	<form action="" method="post">
END;
		$body = <<< END
		<table width="100%">
			<tr>
				<td width="30%">
					<label for="new_folder_name">
						Folder name:
					</label>
				</td>
				<td>
					<input type="text" name="edit_folder_name" id="edit_folder_name" value="{$folder_name}" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="edit_folder_access">
						Parent folder
					</label>
				</td>
				<td>
END;
					$body .= run("folder:select", array("edit_folder_parent",$_SESSION['userid'],$this_folder->parent));
					$body .= <<< END
				</td>
			</tr>
			<tr>
				<td>
					<label for="edit_folder_access">
						Access restrictions
					</label>
				</td>
				<td>
END;
					$body .= run("display:access_level_select",array("edit_folder_access",$this_folder->access));
					$body .= <<< END
				</td>
			</tr>
			<tr>
				<td>
					<label for="edit_folder_access">
						Keywords (comma separated):
					</label>
				</td>
				<td>
END;
					$body .= run("display:input_field",array("edit_folder_keywords","","keywords","folder",$folder));
					$body .= <<< END
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="hidden" name="action" value="edit_folder" />
					<input type="hidden" name="edit_folder_id" value="{$folder}" />
					<input type="submit" value="Save" />
				</td>
			</tr>
		</table>
	</form>
END;
		$title = "Edit this folder";
		$run_result .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $title,
								'contents' => $body
							)
							);
		}
	}
	$run_result .= <<< END
	<h2>
		Upload files and folders
	</h2>
	<form action="" method="post">
END;

	$title = "Create a new folder";
	
	$body = <<< END
		<table>
			<tr>
				<td width="30%">
					<label for="new_folder_name">
						To create a new folder, enter its name:
					</label>
				</td>
				<td>
					<input type="text" name="new_folder_name" id="new_folder_name" value="" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="new_folder_access">
						Access restrictions
					</label>
				</td>
				<td>
END;
					$body .= run("display:access_level_select",array("new_folder_access","PUBLIC"));
					$body .= <<< END
				</td>
			</tr>
			<tr>
				<td>
					<label for="new_folder_keywords">
						Keywords: (comma separated)
					</label>
				</td>
				<td>
END;
					$body .= run("display:input_field",array("new_folder_keywords","","keywords","folder"));
					$body .= <<< END
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input type="hidden" name="folder" value="{$folder}" />
					<input type="hidden" name="files_owner" value="{$page_owner}" />
					<input type="hidden" name="action" value="files:createfolder" />
					<input type="submit" value="Create" />
				</td>
			</tr>
		</table>
END;

	$run_result .= run("templates:draw", array(
						'context' => 'databoxvertical',
						'name' => $title,
						'contents' => $body
					)
					);
	$run_result .= <<< END
	</form><br />
	<form action="{$url}_files/action_redirection.php" method="post" enctype="multipart/form-data">
END;

	$title = "Upload a file";

	$body = <<< END
	
		<table>
			<tr>
				<td colspan="2">
END;
					if ($pageowner == $_SESSION['userid']) {
						$body .= "You have used ";
					} else {
						$body .= "Used space: ";
					}
					
					// $quota = db_query("select sum(size) as totalsize from files where owner = ".$_SESSION['userid']);
					$quota = db_query("select sum(size) as totalsize from files where files.files_owner = ". $page_owner);
					$quota = $quota[0]->totalsize;
					$body .= round(($quota / 1000000),4);
					$body .= "Mb of a total ";

					// $quota = db_query("select file_quota from users where ident = " . $_SESSION['userid']);
					$quota = db_query("select file_quota from users where ident = " . $page_owner);
					$quota = $quota[0]->file_quota;
					$body .= round(($quota / 1000000),4) . "Mb.";
	$body .= <<< END
				</td>
			<tr>
				<td width="30%">
					<label for="new_file">
						File to upload:
					</label>
				</td>
				<td>
						<input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
						<input name="new_file" id="new_file" type="file" />
				</td>
			</tr>
			<tr>
				<td>
					<label for="new_file_title">
						File title:
					</label>
				</td>
				<td>
					<input type="text" id="new_file_title" name="new_file_title" value="" />
				</td>
			</tr>

			<tr>
				<td>
					<label for="new_file_description">
						File description:
					</label>
				</td>
				<td>
					<textarea id="new_file_description" name="new_file_description"></textarea>
				</td>
			</tr>
			<tr>
				<td>
					<label for="new_file_access">
						Access restrictions:
					</label>
				</td>
				<td>
END;
					$body .= run("display:access_level_select",array("new_file_access","PUBLIC"));
					$body .= <<< END
				</td>
			</tr>
			<tr>
				<td>
					<label for="new_file_keywords">
						Keywords: (comma separated)
					</label>
				</td>
				<td>
END;
					$body .= run("display:input_field",array("new_file_keywords","","keywords","file"));
					$body .= <<< END
				</td>
			</tr>
END;

			$body .= run("metadata:edit");

			$body .= <<< END
			
			<tr>
				<td colspan="2"><br />
					<input type="checkbox" name="copyright" value="ok" />
					By checking this box, you are asserting that you have the legal right to
					share this file, and that you understand you are sharing it with other
					users of the system.
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><br />
					<input type="hidden" name="folder" value="{$folder}" />
					<input type="hidden" name="files_owner" value="{$page_owner}" />
					<input type="hidden" name="action" value="files:uploadfile" />
					<input type="submit" value="Upload" />
				</td>
			</tr>

		</table>
END;

	$run_result .= run("templates:draw", array(
							'context' => 'databoxvertical',
							'name' => $title,
							'contents' => $body
						)
						);

	$run_result .= <<< END
	</form>
END;

?>