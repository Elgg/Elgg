<?php

		function viewfolder($folderid, $userid, $level, $selected = -1) {
			
			$prefix = "";
			for ($i = 0; $i < $level; $i++) {
				$prefix .= "&gt;";
			}
			$fileprefix = $prefix . "&gt;";
			
			if ($folderid == -1) {
				$body = "<option value=\"-1\" ";
				if ($selected == -1) {
					$body .= "selected = \"selected\"";
				}
				$body .= ">Root</option>";
			} else {
				$current_folder = db_query("select ident, name from file_folders where owner = $userid and ident = $folderid");
				$name = (stripslashes($current_folder[0]->name));
				$ident = $current_folder[0]->ident;
				if ($ident == $selected) {
					$selectstring = "selected=\"selected\"";
				} else {
					$selectstring = "";
				}
				$body = <<< END
					<option value="{$ident}" {$selectstring} >{$prefix} {$name} </option>
END;
			}			
			$folders = db_query("select * from file_folders where owner = $userid and parent = $folderid");
			if (sizeof($folders) > 0) {
				foreach($folders as $folder) {
					$body .= viewfolder($folder->ident, $userid, $level + 1,$selected);
				}
			}
			return $body;
		}
		
		$selectname = $parameter[0];
		$userid = $parameter[1];
		$folder = $parameter[2];
		
		$run_result .= "<select name=\"$selectname\">";
		$run_result .= viewfolder(-1, $userid, 0, $folder);
		$run_result .= "</select>";
		
?>