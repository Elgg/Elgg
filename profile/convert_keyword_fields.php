<?php

	//	ELGG profile view page
	
	// Run includes
		require("../includes.php");
		
		run("profile:init");
		
		foreach($data['profile:details'] as $datatypes) {
			
			if ($datatypes[2] == "keywords") {
				echo "<br /><b>" . $datatypes[0] . "</b><br />";
				$datarows = db_query("select * from profile_data where name = '".$datatypes[1]."' and value != ''");
				foreach($datarows as $row) {
					
					$keywords = "";
					echo $row->value;
					echo " --&gt; ";
					$row->value = str_replace("\n","",$row->value);
					$row->value = str_replace("\r","",$row->value);
					$keyword_list = explode(",",$row->value);
					sort($keyword_list);
					if (sizeof($keyword_list) > 0) {
						foreach($keyword_list as $list_item) {
							$list_item = str_replace("[[","",$list_item);
							$list_item = str_replace("]]","",$list_item);
							$list_item = strtolower(trim($list_item));
							echo("insert into tags set tag = '".$list_item."', tagtype = '".$row->name."', access = '".$row->access."', ref = " . $row->ident . ", owner = " . $row->owner . "<br />");
							db_query("insert into tags set tag = '".$list_item."', tagtype = '".$row->name."', access = '".$row->access."', ref = " . $row->ident . ", owner = " . $row->owner);
						}
					} 
					
					/*
					$keywords = $row->value;
					preg_match_all("/\[\[([A-Za-z0-9 ]+)\]\]/i",$keywords,$keyword_list);
					$keyword_list = $keyword_list[1];
					$keywords = "";
					if (sizeof($keyword_list) > 0) {
						sort($keyword_list);
						foreach($keyword_list as $key => $list_item) {
							$keywords .= $list_item;
							if ($key < sizeof($keyword_list) - 1) {
								$keywords .= ", ";
							}
						}
					}
					echo $keywords . "<br />";
					echo "update profile_data set value = '".$keywords."' where ident = '".$row->ident."'<br />";
					db_query("update profile_data set value = '".$keywords."' where ident = '".$row->ident."'"); */
				}
			}
			
		}
		
?>