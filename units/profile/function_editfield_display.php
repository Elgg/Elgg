<?php

	global $page_owner;

	if (sizeof($parameter) >= 2) {
	
		if (!isset($data['profile:preload'][$parameter[1]])) {
		
			$value = db_query("select * from profile_data where name = '".$parameter[1]."' and owner = '". $page_owner ."'");
			$value = $value[0];
		
		} else {
			
			$value = "";
			$value->value = $data['profile:preload'][$parameter[1]];
			$value->access = "PUBLIC";
			
		}
		
		$name = <<< END
					<label for="{$parameter[1]}">
						<b>{$parameter[0]}</b>
END;
		if (isset($parameter[3])) {
			$name .= "<br /><i>" . $parameter[3] . "</i>";
		}
		$name .= <<< END
					</label>
END;
	
		if (sizeof($parameter) < 3) {
			$parameter[2] = "text";
		}
		$column1 = run("display:input_field",array("profiledetails[" . $parameter[1] . "]",$value->value,$parameter[2],$parameter[1],$value->ident,$page_owner));

		$column2 = "<label>". gettext("Access Restriction:") ."<br />";
		$column2 .= run("display:access_level_select",array("profileaccess[".$parameter[1] . "]",$value->access)) . "</label>";
		
		$run_result .= run("templates:draw", array(
							'context' => 'databox',
							'name' => $name,
							'column1' => $column1,
							'column2' => $column2
						)
						);
		
	}

?>