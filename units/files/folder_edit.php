<?php

	global $folder;
	global $page_owner;
	
	$url = url;
	
	if ($folder != -1) {
		global $this_folder;
		global $folder_name;
		$folder_name = htmlentities($folder_name);
		$folder_details = db_query("select * from file_folders where ident = " . $this_folder->ident);
		$folder_details = $folder_details[0];
		if (run("permissions:check", array("files", $folder_details->owner))  || run("permissions:check", array("files", $folder_details->files_owner))) {
		$edit = gettext("Edit this folder"); // gettext variable
		$run_result .= <<< END
	<h3>
		$edit
	</h3>
	<form action="" method="post">
END;
		$labelValue = gettext("Folder name:"); // gettext variable
		$parentFolder = gettext("Parent folder"); // gettext variable
		$body = <<< END
		<table width="100%">
			<tr>
				<td width="30%">
					<p><label for="new_folder_name">
						$labelValue
					</label></p>
				</td>
				<td>
					<p><input type="text" name="edit_folder_name" id="edit_folder_name" value="{$folder_name}" /></p>
				</td>
			</tr>
			<tr>
				<td>
					<p><label for="edit_folder_parent">
						$parentFolder
					</label></p>
				</td>
				<td><p>
END;
					$body .= run("folder:select", array("edit_folder_parent",$_SESSION['userid'],$this_folder->parent));
					$accessLabel = gettext("Access restrictions"); // gettext variable
					$body .= <<< END
				</p></td>
			</tr>
			<tr>
				<td><p>
					<label for="edit_folder_access">
						$accessLabel
					</label></p>
				</td>
				<td><p>
END;
					$body .= run("display:access_level_select",array("edit_folder_access",$this_folder->access));
					$keywords = gettext("Keywords (comma separated):"); // gettext variable
					$body .= <<< END
				</p></td>
			</tr>
			<tr>
				<td><p>
					<label for="edit_folder_access">
						$keywords
					</label>
				</td></p>
				<td><p>
END;
					$body .= run("display:input_field",array("edit_folder_keywords","","keywords","folder",$folder));
					$save = gettext("Save"); // gettext variable 
					$body .= <<< END
				</p></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><p>
					<input type="hidden" name="action" value="edit_folder" />
					<input type="hidden" name="edit_folder_id" value="{$folder}" />
					<input type="submit" value=$save /></p>
				</td>
			</tr>
		</table>
	</form>
END;
		$title = gettext("Edit this folder");
		$run_result .= run("templates:draw", array(
								'context' => 'databoxvertical',
								'name' => $title,
								'contents' => $body
							)
							);
		}
	}
	$header = gettext("Upload files and folders");//gettext variable
	$run_result .= <<< END
	<h4>
		<a name="addFile"></a>$header
	</h4>
	<form action="" method="post">
END;

	$title = gettext("Create a new folder");
	$createLabel = gettext("To create a new folder, enter its name:"); //gettext variable
	$accessLabel = gettext("Access restrictions"); //gettext variable

	$body = <<< END
		<table>
			<tr>
				<td width="30%"><p>
					<label for="new_folder_name">
						$createLabel
					</label></p>
				</td>
				<td><p>
					<input type="text" name="new_folder_name" id="new_folder_name" value="" />
					</p>
				</td>
			</tr>
			<tr>
				<td><p>
					<label for="new_folder_access">
						$accessLabel
					</label>
					</p>
				</td>
				<td><p>
END;
					$body .= run("display:access_level_select",array("new_folder_access","user" . $_SESSION['userid']));
					$keywords = gettext("Keywords (comma separated):"); // gettext variable
					$body .= <<< END
				</p></td>
			</tr>
			<tr>
				<td><p>
					<label for="new_folder_keywords">
						$keywords
					</label>
				</p></td>
				<td><p>
END;
					$body .= run("display:input_field",array("new_folder_keywords","","keywords","folder"));
					$create = gettext("Create"); // gettext variable 
					$body .= <<< END
				</p></td>
			</tr>
			<tr>
				<td colspan="2" align="center"><p>
					<input type="hidden" name="folder" value="{$folder}" />
					<input type="hidden" name="files_owner" value="{$page_owner}" />
					<input type="hidden" name="action" value="files:createfolder" />
					<input type="submit" value=$create /></p>
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

	$title = gettext("Upload a file");

	$body = <<< END
	
		<table>
			<tr>
				<td colspan="2"><p>
END;
					
					// $quota = db_query("select sum(size) as totalsize from files where owner = ".$_SESSION['userid']);
					$quota = db_query("select sum(size) as totalsize from files where files.files_owner = ". $page_owner);
					$usedquota = $quota[0]->totalsize;

					// $quota = db_query("select file_quota from users where ident = " . $_SESSION['userid']);
					$quota = db_query("select file_quota from users where ident = " . $page_owner);
					$totalquota = $quota[0]->file_quota;
					if ($pageowner == $_SESSION['userid']) {
						$body .= sprintf(gettext("You have used %s Mb of a total %s Mb."),round(($usedquota / 1000000),4),round(($totalquota / 1000000),4));
					} else {
						$body .= sprintf(gettext("Used space: %s Mb."),round(($usedquota / 1000000),4));
					}
	$fileLabel = gettext("File to upload:"); //gettext variable
	$fileTitle = gettext("File title:"); //gettext variable
	$fileDesc = gettext("File Description:"); //gettext variable
	$fileAccess = gettext("Access restrictions:"); //gettext variable


	$body .= <<< END
				</p></td>
			<tr>
				<td width="30%"><p>
					<label for="new_file">
						$fileLabel
					</label>
				</p></td>
				<td><p>
						<input name="new_file" id="new_file" type="file" />
				</p></td>
			</tr>
			<tr>
				<td><p>
					<label for="new_file_title">
						$fileTitle
					</label>
					</p>
				</td>
				<td><p>
					<input type="text" id="new_file_title" name="new_file_title" value="" />
					</p>
				</td>
			</tr>

			<tr>
				<td><p>
					<label for="new_file_description">
						$fileDesc
					</label>
					</p>
				</td>
				<td><p>
					<textarea id="new_file_description" name="new_file_description"></textarea>
					</p>
				</td>
			</tr>
			<tr>
				<td><p>
					<label for="new_file_access">
						$fileAccess
					</label>
					</p>
				</td>
				<td><p>
END;
					$body .= run("display:access_level_select",array("new_file_access","user" . $_SESSION['userid']));
					$keywords = gettext("Keywords (comma separated):"); // gettext variable
					$body .= <<< END
				</p></td>
			</tr>
			<tr>
				<td><p>
					<label for="new_file_keywords">
						$keywords
					</label>
					</p>
				</td>
				<td><p>
END;
					$body .= run("display:input_field",array("new_file_keywords","","keywords","file"));
					$body .= <<< END
					</p>
				</td>
			</tr>
END;

			$body .= run("metadata:edit");
			
			$copyright = gettext("By checking this box, you are asserting that you have the legal right to share this file, and that you understand you are sharing it with other users of the system."); //gettext variable
			$upload = gettext("Upload"); //gettext variable
			$body .= <<< END
			
			<tr>
				<td colspan="2"><p><label for="copyrightokcheckbox">
					<input type="checkbox" id="copyrightokcheckbox" name="copyright" value="ok" />
					$copyright
					</label></p>
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center"><br />
					<input type="hidden" name="folder" value="{$folder}" />
					<input type="hidden" name="files_owner" value="{$page_owner}" />
					<input type="hidden" name="action" value="files:uploadfile" />
					<input type="submit" value=$upload />
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