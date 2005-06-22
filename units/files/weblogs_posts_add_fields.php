<?php

$sitename = sitename;	
// Function to recursively view folders
	
		function viewfolder($folderid, $userid, $level) {

			
			$prefix = "";
			for ($i = 0; $i < $level; $i++) {
				$prefix .= "&gt;";
			}
			$fileprefix = $prefix . "&gt;";
			
			if ($folderid == -1) {
				$body = <<< END
				<option value="">ROOT</option>
END;
			} else {
				$current_folder = db_query("select name from file_folders where owner = $userid and ident = $folderid");
				$name = strtoupper(stripslashes($current_folder[0]->name));
				$body = <<< END
					<option value="">{$prefix} {$name}</option>
END;
			}
			$files = db_query("select * from files where owner = $userid and folder = $folderid");
			if (sizeof($files) > 0) {
				foreach($files as $file) {
					$name = stripslashes($file->name);
					$filetitle = stripslashes($file->title);
					$body .= <<< END
					
					<option value="{$file->ident}">{$fileprefix} {$filetitle}</option>
END;
				}
			}
			
			$folders = db_query("select * from file_folders where owner = $userid and parent = $folderid");
			if (sizeof($folders) > 0) {
				foreach($folders as $folder) {
					$body .= viewfolder($folder->ident, $userid, $level + 1);
				}
			}
			return $body;
		}

	// Add "insert file" field to weblog post uploads

		if (isset($parameter)) {

			$userid = (int) $parameter;
				
			$run_result .= <<< END
<script language="javascript">
<!--

	function addFile(form) {
		if (form.weblog_add_file.selectedIndex != "") {
			form.new_weblog_post.value = form.new_weblog_post.value + "{{file:" + form.weblog_add_file.options[form.weblog_add_file.selectedIndex].value + "}}";
		}
	}

// -->
</script>
			
			
			<p>
				Embed a file from your $sitename file storage:<br />
				<select name="weblog_add_file" id="weblog_add_file">
		
END;

			$run_result .= viewfolder(-1, $userid, 0);

			$run_result .= <<< END
		
				</select>
				<input type="button" value="Add" onclick="addFile(this.form)" /><br />
				(This will add a code to your weblog post that will be converted into an embedded file.)
			</p>
		
END;

		}

?>