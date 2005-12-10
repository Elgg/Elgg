<?php

	// If $data['foaf:profile'] is set and has elements in it ...
	
		$user_id = (int) $parameter;
	
		$foaf_elements = "";
		$where = run("users:access_level_sql_where",$_SESSION['userid']);
	
		if (isset($data['foaf:profile']) && sizeof($data['foaf:profile']) > 0) {
			
			foreach($data['foaf:profile'] as $foaf_element) {

				
				$value = "";
				$value_type = "";
				
				$profile_value = addslashes($foaf_element[0]);
				$foaf_name = $foaf_element[1];
				$individual = $foaf_element[2];
				$resource = $foaf_element[3];
				foreach($data['profile:details'] as $profile_element) {
					if ($profile_element[1] == $profile_value) {
						$value_type = $profile_element[2];
					}
				}
				
				if ($value_type != "keywords") {
					$result = db_query("select value from profile_data where name = '$profile_value' and ($where) and owner = $user_id");
				} else {
					$result = db_query("select tag from tags where tagtype = '$profile_value' and ($where) and owner = $user_id");
				}
				if (sizeof($result > 0)) {
					if ($individual == "individual") {
						foreach($result as $element) {
							if ($value_type == "keywords") {
								$element->value = $element->tag;
							}
							if (trim($element->value) != "") {
								$value = stripslashes($element->value);
								if ($resource == "resource") {
									$enclosure = "\t\t<" . $foaf_name . " ";
									if ($value_type == "keywords") {
										$enclosure .= "dc:title=\"" . htmlentities($value) . "\" ";
										$enclosure .= "rdf:resource=\"" . url . "tag/".urlencode($value)."\" />\n";
									} else {
										$enclosure .= "rdf:resource=\"" . htmlentities($value) . "\" />\n";
									}
									$foaf_elements .= $enclosure;
								} else {
									$enclosure = "\t\t<" . $foaf_name . "><![CDATA[" . htmlentities(($value)) . "]]></" . $foaf_name . ">\n";
									$foaf_elements .= $enclosure;
								}
							}
						}
					} else {
						foreach($result as $element) {
							if ($value_type == "keywords") {
								$element->value = $element->tag;
							}
							if (trim($element->value) != "") {
								if ($value != "") {
									$value .= ", ";
								}
								$value .= stripslashes($element->value);
							}
							if ($resource == "resource") {
								$enclosure = "\t\t<" . $foaf_name . " ";
								if ($value_type == "keywords") {
									$enclosure .= "dc:title=\"" . htmlentities($value) . "\" ";
									$enclosure .= "rdf:resource=\"" . url . "tag/".urlencode($value)."\" />\n";
								} else {
									$enclosure .= "rdf:resource=\"" . htmlentities($value) . "\" />\n";
								}
							} else {
								$enclosure = "\t\t<" . $foaf_name . "><![CDATA[" . htmlentities(($value)) . "]]></" . $foaf_name . ">\n";
							}
						}
						$foaf_elements .= $enclosure;
					}
				}
				
			}
			
		}
		
		$run_result .= $foaf_elements;

?>