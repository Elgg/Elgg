<?php

	// Turn file ID into a proper link
	
		if (isset($parameter)) {
			
			$fileid = (int) $parameter;
			$file = db_query("select * from files where ident = $fileid");
			if (sizeof($file) > 0) {
				if (run("users:access_level_check",$file[0]->access) || $file[0]->owner == $_SESSION['userid']) {
					if (!in_array(run("files:mimetype:inline",$file[0]->location), $data['mimetype:inline'])) {
						$run_result .= "<a href=\"";
						$run_result .= url . run("users:id_to_name",$file[0]->owner) . "/files/" . $file[0]->folder . "/" . $file[0]->ident . "/" . $file[0]->originalname;
						$run_result .= "\" >";
						$run_result .= stripslashes($file[0]->title);
						$run_result .= "</a>";
					} else {
						list($width, $height, $type, $attr) = getimagesize($file[0]->location);
						if ($width > 400 || $height > 400) {
							$run_result .= "<a href=\"";
							$run_result .= url . run("users:id_to_name",$file[0]->owner) . "/files/" . $file[0]->folder . "/" . $file[0]->ident . "/" . $file[0]->originalname;
							$run_result .= "\" >";
							$run_result .= stripslashes($file[0]->title);
							$run_result .= "</a>";
						} else {
							$run_result .= "<img src=\"";
							$run_result .= url . run("users:id_to_name",$file[0]->owner) . "/files/" . $file[0]->folder . "/" . $file[0]->ident . "/" . $file[0]->originalname;
							$run_result .= "\" $attr alt=\"".htmlentities(stripslashes($file[0]->title))."\" />";
						}
					}
				} else {
					$run_result .= "<b>[You do not have permission to access this file]</b>";
				}
			} else {
				$run_result .= "<b>[File does not exist]</b>";
			}
			
		}

?>