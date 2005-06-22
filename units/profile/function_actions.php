<?php

	// Action parser for profiles

		global $page_owner;
	
		if (isset($_POST['action']) && $_POST['action'] == "profile:edit" && logged_on && run("permissions:check", "profile")) {
		
			if (isset($_POST))			
			if (isset($_POST['profiledetails'])) {
				db_query("delete from profile_data where owner = '".$page_owner."'");
				foreach($_POST['profiledetails'] as $field => $value) {

					if ($value != "") {
				
						$value = addslashes($value);
						$field = addslashes($field);
						$access = addslashes($_POST['profileaccess'][$field]);
						$owner = (int) $page_owner;
						
						db_query("insert into profile_data set name = '$field', value = '$value', access = '$access', owner = '$owner'");
						$insert_id = (int) db_id();
						
						foreach($data['profile:details'] as $datatype) {
							if ($datatype[1] == $field && $datatype[2] == "keywords") {
								db_query("delete from tags where tagtype = '$field' and owner = '$owner'");
								$keywords = "";
								$value = str_replace("\n","",$value);
								$value = str_replace("\r","",$value);
								$keyword_list = explode(",",$value);
								sort($keyword_list);
								if (sizeof($keyword_list) > 0) {
									foreach($keyword_list as $key => $list_item) {
										if ($key > 0) {
											$keywords .= ", ";
										}
										$keywords .= ($list_item);
										$list_item = (trim($list_item));
										db_query("insert into tags set tagtype = '$field', access = '$access', tag = '$list_item', ref = $insert_id, owner = $owner");
									}
								}
								$value = $keywords;
							}
						}

					}
			
				}
				$messages[] = "Profile updated.";
			}
		
		}

?>