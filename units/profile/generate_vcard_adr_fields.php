<?php

    // If $data['vcard:profile:adr'] is set and has elements in it ...
    
        $user_id = (int) $parameter;
    
        $foaf_elements = "";
        $where = run("users:access_level_sql_where",$_SESSION['userid']);
    
        if (!empty($data['vcard:profile:adr']) && sizeof($data['vcard:profile:adr']) > 0) {
            
            foreach($data['vcard:profile:adr'] as $foaf_element) {

                
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
                    $result = get_records_select('profile_data',"name = '$profile_value' AND ($where) AND owner = ".$user_id,null,'','ident,value');
                } else {
                    $result = get_records_select('tags',"tagtype = '$profile_value' AND ($where) AND owner = $user_id",null,'','ident,tag AS value');
                }
                if (!empty($result)) {
                    if ($individual == "individual") {
                        foreach($result as $element) {
                            if (trim($element->value) != "") {
                                $value = stripslashes($element->value);
                                if ($resource == "resource") {
                                    $enclosure = "\t\t\t<" . $foaf_name . " ";
                                    if ($value_type == "keywords") {
                                        $enclosure .= "dc:title=\"" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "\" ";
                                        $enclosure .= "rdf:resource=\"" . url . "tag/".urlencode($value)."\" />\n";
                                    } else {
                                        $enclosure .= "rdf:resource=\"" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "\" />\n";
                                    }
                                    $foaf_elements .= $enclosure;
                                } else {
                                    $enclosure = "\t\t\t<" . $foaf_name . "><![CDATA[" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "]]></" . $foaf_name . ">\n";
                                    $foaf_elements .= $enclosure;
                                }
                            }
                        }
                    } else {
                        foreach($result as $element) {
                            if (trim($element->value) != "") {
                                if ($value != "") {
                                    $value .= ", ";
                                }
                                $value .= stripslashes($element->value);
                            }
                            if ($resource == "resource") {
                                $enclosure = "\t\t\t<" . $foaf_name . " ";
                                if ($value_type == "keywords") {
                                    $enclosure .= "dc:title=\"" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "\" ";
                                    $enclosure .= "rdf:resource=\"" . url . "tag/".urlencode($value)."\" />\n";
                                } else {
                                    $enclosure .= "rdf:resource=\"" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "\" />\n";
                                }
                            } else {
                                $enclosure = "\t\t\t<" . $foaf_name . "><![CDATA[" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "]]></" . $foaf_name . ">\n";
                            }
                        }
                        $foaf_elements .= $enclosure;
                    }
                }
                
            }
            
        }
        
        $run_result .= $foaf_elements;

?>