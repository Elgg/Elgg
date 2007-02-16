<?php 
/*
Penny note: none of the queries in this file 
that are get_something_sql or
get_something_select can be converted to use 
prepared statements because they all have $where
that has come from some function somewhere...
*/

Class ElggProfile {

    function ElggProfile ($profile_id) {

        global $data;
        global $page_owner;
        global $PAGE;

        // ELGG profile system initialisation
        // ID of profile to view / edit
        
        if (!empty($profile_id)) {
            $this->id = $profile_id;
        } else {
            $this->id = -1;
        }

        $page_owner = $profile_id;

        // Profile initialisation
        // very strange init sequence from the old main() call follows
        $this->editfield_defaults();
        // $this->actions();     // not from here -- 
        // $this->upload_foaf();

    }

    function edit_link () {


        global $page_owner;
        global $data;
        global $CFG;

        $run_result = '';

        if (run("permissions:check", "profile")) {
        
            $editMsg = __gettext("Click here to edit this profile.");

            $run_result .= <<<END
                
                <p>
                <a href="{$CFG->wwwroot}profile/edit.php?profile_id=$page_owner">$editMsg</a>
                </p>
END;

            $run_result .= run("profile:edit:link");
            
        }
        return $run_result;
    }
    
    function display_name () {
        
        global $name_cache;
        global $data;
        
        if (!isset($name_cache[$this->id]) || (time() - $name_cache[$this->id]->created > 60)) {
        
            $name_cache[$this->id]->created = time();
            $name_cache[$this->id]->data = htmlspecialchars(user_info('name',$this->id), ENT_COMPAT, 'utf-8');
        
        }
        $run_result = $name_cache[$this->id]->data;
        return $run_result;
    }
    
    function display_form () {
        
        global $page_owner;
        global $data;
        global $CFG;
        
        $run_result = '';
        
        $body = "<p>\n" . __gettext("    This screen allows you to edit your profile. Blank fields will not show up on your profile screen in any view; you can change the access level for each piece of information in order to prevent it from falling into the wrong hands. For example, we strongly recommend you keep your address to yourself or a few trusted parties.") . "</p>\n";
        
        if (run("permissions:check", "profile")) {
            
            $profile_username = user_info('username', $page_owner);
            
            $body .= "<form action=\"".url . "profile/edit.php?profile_id=".$page_owner."\" method=\"post\" enctype=\"multipart/form-data\">";
            $body .= "<div class=\"tabber\">";
            
            // Cycle through all defined profile detail fields and display them
            
            $profilecat = array( );
            
            if (!empty($data['profile:details']) && sizeof($data['profile:details']) > 0) {
                
                foreach($data['profile:details'] as $field) {
                    
                    if (is_array($field)) {
                        $flabel = !empty($field[0]) ? $field[0] : '';
                        $fname  = !empty($field[1]) ? $field[1] : '';
                        $ftype  = !empty($field[2]) ? $field[2] : '';
                        $fblurb = !empty($field[3]) ? $field[3] : '';
                        $fusertype = !empty($field[4]) ? $field[4] : '';
                        $finvisible = false;
                        $frequired = false;
                        $fcat = __gettext("Main");
                    // Otherwise map things the new way!
                    } else {
                        $flabel = $field->name;
                        $fname = $field->internal_name;
                        $ftype = $field->field_type;
                        $fblurb = $field->description;
                        $fusertype = $field->user_type;
                        $finvisible = $field->invisible;
                        $frequired = $field->required;
                        if (!empty($field->category)) {
                            $fcat = $field->category;
                        } else {
                            $fcat = __gettext("Main");
                        }
                    }
                    
                    if (!isset($profilecat[$fcat])) {
                        $profilecat[$fcat] = '';
                    }
                    $profilecat[$fcat] .= $this->editfield_display($field);
                }
                if (sizeof($profilecat) > 0) {
                    foreach($profilecat as $cat => $html) {
                        
                        $body .= "<div class=\"tabbertab\" title=\"$cat\">";
                        $body .= $html;
                        $body .= "</div>";
                        
                    }
                }
                
            }
            
            $submitMsg = __gettext("Submit details:");
            $saveProfile = __gettext("Save your profile");
            $body .= <<< END
                
                </div>
                
                <p align="center">
                <label>
                $submitMsg
                <input type="submit" name="submit" value="$saveProfile" />
                </label>
                <input type="hidden" name="action" value="profile:edit" />
                <input type="hidden" name="profile_id" value="$page_owner" />
                </p>

                </form>
END;

            $body .= "<p>&nbsp;</p><form action=\"".url . "profile/edit.php?profile_id=".$page_owner."\" method=\"post\" enctype=\"multipart/form-data\">";
            $body .= "<p>" . __gettext("You can import some profile data by uploading a FOAF file here:") . "</p>";
            $body .=templates_draw(array(
                                                 'context' => 'databox',
                                                 'name' => __gettext("Upload a FOAF file:"),
                                                 'column1' => "<input name=\"foaf_file\" id=\"foaf_file\" type=\"file\" />",
                                                 'column2' => "<input type=\"submit\" value=\"".__gettext("Upload") . "\" />"
                                                 )
                         );
            $body .= <<<END
        
                <input type="hidden" name="action" value="profile:foaf:upload" />
                <input type="hidden" name="profile_id" value="$page_owner" />
                </form>
        
END;

            $run_result .= $body;
    
        }
        return $run_result;
    }

    function editfield_defaults () {

        global $CFG;
        include($CFG->profilelocation . "profile.config.php");
        return $run_result;
    }

    // the field parameter seems to be an array of unknown structure... 
    function editfield_display ($field) {

        global $page_owner;
        static $usertype;
        
        if (!isset($usertype)) {
            $usertype = user_type($page_owner);
        }
        
        // copy array element with default to ''
        
        // If we're dealing with the old-style profile fields
        if (is_array($field)) {
            $flabel = !empty($field[0]) ? $field[0] : '';
            $fname  = !empty($field[1]) ? $field[1] : '';
            $ftype  = !empty($field[2]) ? $field[2] : '';
            $fblurb = !empty($field[3]) ? $field[3] : '';
            $fusertype = !empty($field[4]) ? $field[4] : '';
            $finvisible = false;
            $frequired = false;
        // Otherwise map things the new way!
        } else {
            $flabel = $field->name;
            $fname = $field->internal_name;
            $ftype = $field->field_type;
            $fblurb = $field->description;
            $fusertype = $field->user_type;
            $finvisible = $field->invisible;
            $frequired = $field->required;
        }
        
        if (!empty($fusertype) && $fusertype != $usertype) {
            return '';
        }

        global $page_owner;
        global $data;
        global $CFG;

        $run_result = '';

        if (empty($flabel) && empty($fname)) {
            return '';
        }
            
        if (!isset($data['profile:preload'][$fname])) {
            if (!$value = get_record('profile_data','name',$fname,'owner',$page_owner)) {
                $value = "";
                $value->value = "";
                $value->access = $CFG->default_access;
            }
        } else {
            $value = "";
            $value->value = $data['profile:preload'][$fname];
            if (!isset($data['profile:preload:access'][$fname])) {
                $value->access = $CFG->default_access;
            } else {
                $value->access = $data['profile:preload:access'][$fname];
            }
            
        }
        if ($finvisible) {
            $value->access = "PRIVATE";
        }
        
        $name = "<label for=\"$fname\"><b>{$flabel}</b>";
        if (!empty($fblurb)) {
            $name .= "<br /><i>" . $fblurb . "</i>";
        }
        $name .= '</label>';
        
        if (empty($ftype)) {
            $ftype = "text";
        }

        $column1 = display_input_field(array("profiledetails[" . $fname . "]",$value->value,$ftype,$fname,@$value->ident,$page_owner));
        $column2 = "<label>". __gettext("Access Restriction:") ."<br />";
        $column2 .= run("display:access_level_select",array("profileaccess[".$fname . "]",$value->access)) . "</label>";
        
        $run_result .=templates_draw(array(
                                           'context' => 'databox',
                                           'name'    => $name,
                                           'column1' => $column1,
                                           'column2' => $column2
                                           )
                                     );
        
        return $run_result;

    }

    function field_display ($field, $allvalues) {

        global $data;

        $run_result = '';
        
        // If we're dealing with the old-style profile fields
        if (is_array($field)) {
            $flabel = !empty($field[0]) ? $field[0] : '';
            $fname  = !empty($field[1]) ? $field[1] : '';
            $ftype  = !empty($field[2]) ? $field[2] : '';
            $fblurb = !empty($field[3]) ? $field[3] : '';
            $fusertype = !empty($field[4]) ? $field[4] : '';
        // Otherwise map things the new way!
        } else {
            $flabel = $field->name;
            $fname = $field->internal_name;
            $ftype = $field->field_type;
            $fblurb = $field->description;
            $fusertype = $field->user_type;
            $finvisible = $field->invisible;
            $frequired = $field->required;
        }

        //if (sizeof($field) >= 2) {
    
            // $value = get_record('profile_data','name',$field[1],'owner',$this->id);
        
            foreach($allvalues as $curvalue) {
                if ($curvalue->name == stripslashes($fname)) {
                    $value = $curvalue;
                    break; // found it, done!
                }
            }

            if (!isset($value)) {
                return '';
            }

            if ((($value->value != "" && $value->value != "blank" && !$finvisible)) 
                && run("users:access_level_check", $value->access)) {
                $name = $flabel;
                $column1 = display_output_field(array($value->value,$ftype,$fname,$flabel,$value->ident));
                $run_result .=templates_draw(array(
                                                           'context' => 'databox1',
                                                           'name' => $name,
                                                           'column1' => $column1
                                                           )
                                   );
            }
        // }
        return $run_result;
    }

    function search ($tagtype, $tagvalue) {

        global $data, $CFG, $db;
    
        $handle = 0;
        $run_result = '';

        foreach($data['profile:details'] as $profiletype) {
            if ($profiletype[1] == $tagtype && $profiletype[2] == "keywords") {
                $handle = 1;
            }
        }
    
        if ($handle) {
            
            $searchline = "tagtype = " . $db->qstr($tagtype) . " AND tag = " . $db->qstr($tagvalue) . "";
            $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") AND " . $searchline;
            $searchline = str_replace("owner","t.owner",$searchline);
            $tagvalue = stripslashes($tagvalue);
            if ($result = get_record_sql('SELECT DISTINCT u.* FROM '.$CFG->prefix.'tags t 
                                          LEFT JOIN '.$CFG->prefix.'users u ON u.ident = t.owner
                                          WHERE '.$searchline)) {
                $profilesMsg = __gettext("Profiles where");
                $body = <<< END
            
                    <h2>
                    $profilesMsg
END;
                $body .= "'".__gettext($tagtype)."' = '".$tagvalue."':";
                $body .= <<< END
                    </h2>
END;
                $body .= <<< END
                    <table class="userlist">
                    <tr>
END;
                $i = 1;
                foreach($result as $key => $info) {
                    $width = 50;
                    if (sizeof($tagvalue) > 4) {
                        $width = 25;
                    }
                    $friends_username = $info->username;
                    $friends_name = htmlspecialchars(stripslashes($info->name), ENT_COMPAT, 'utf-8');
                    $friends_menu = run("users:infobox:menu",array($info->ident));
                    $body .= <<< END
                        <td align="center">
                        <p>
                        <a href="{$CFG->wwwroot}{$friends_username}/">
                        <img src="{$CFG->wwwroot}_icon/user/{$info->icon}/w/{$width}" alt="{$friends_name}" border="0" /></a><br />
                        <span class="userdetails">
                        {$friends_name}
                    {$friends_menu}
                    </span>
                          </p>
                          </td>
END;
                    if ($i % 5 == 0) {
                        $body .= "</tr><tr>";
                    }
                    $i++;
                }
                $body .= <<< END
                    </tr>
                    </table>
END;
                $run_result .= $body;
            }
        }
        return $run_result;
    }

    function search_all_tagtypes () {

        global $data;

        foreach($data['profile:details'] as $profiletype) {
            if ($profiletype[2] == "keywords") {
                $data['search:tagtypes'][] = $profiletype[1];
            }
        }
        return true;
    }

    function search_all_tagtypes_rss () {

        global $data;

        foreach($data['profile:details'] as $profiletype) {
            if ($profiletype[2] == "keywords") {
                $data['search:tagtypes:rss'][] = $profiletype[1];
            }
        }
        return true;
    }

    function search_ecl ($tagtype, $tagvalue) {

        global $data, $CFG, $db;
    
        $handle = 0;
        $run_result = '';

        foreach($data['profile:details'] as $profiletype) {
            if ($profiletype[1] == $tagtype && $profiletype[2] == "keywords") {
                $handle = 1;
            }
        }
    
        if ($handle) {
            
            $sub_result = "";
            
            $searchline = "tagtype = " . $db->qstr($tagtype) . " AND tag = " . $db->qstr($tagvalue) . "";
            $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") AND " . $searchline;
            $searchline = str_replace("owner", "t.owner", $searchline);
            $tagvalue = stripslashes($tagvalue);
            if ($result = get_record_sql('SELECT DISTINCT u.* FROM '.$CFG->prefix.'tags t
                                          LEFT JOIN '.$CFG->prefix.'users u ON u.ident = t.owner 
                                          WHERE '.$searchline)) {
                foreach($result as $key => $info) {
                    $icon = url . '_icon/user/'.$post->icon;
                    $sub_result .= "\t\t\t<item>\n";
                    $sub_result .= "\t\t\t\t<name><![CDATA[" . htmlspecialchars(stripslashes($info->name), ENT_COMPAT, 'utf-8') . "]]></name>\n";
                    $sub_result .= "\t\t\t\t<link>" . url . htmlspecialchars($info->username, ENT_COMPAT, 'utf-8') . "</link>\n";
                    $sub_result .= "\t\t\t\t<link>$icon</link>\n";
                    $sub_result .= "\t\t\t</item>\n";
                }
            }
            
            if ($sub_result != "") {
                
                $run_result .= "\t\t<profiles tagtype=\"".addslashes(htmlspecialchars($tagtype, ENT_COMPAT, 'utf-8'))."\">\n" . $sub_result . "\t\t</profiles>\n";
                
            }
            
        }
        return $run_result;
    }

    function search_rss ($tagtype, $tagvalue) {

        global $data, $CFG, $db;
    
        $handle = 0;
        $run_result = '';

        foreach($data['profile:details'] as $profiletype) {
            if ($profiletype[1] == $tagtype && $profiletype[2] == "keywords") {
                $handle = 1;
            }
        }
    
        if ($handle) {
            
            $searchline = "tagtype = " . $db->qstr($tagtype) . " AND tag = " . $db->qstr($tagvalue) . "";
            $searchline = "(" . run("users:access_level_sql_where",$_SESSION['userid']) . ") AND " . $searchline;
            $searchline = str_replace("owner", "t.owner", $searchline);
            $tagvalue = stripslashes($tagvalue);
            if ($result = get_records_sql('SELECT DISTINCT u.* FROM '.$CFG->prefix.'tags t
                                          LEFT JOIN '.$CFG->prefix.'users u ON u.ident = t.owner 
                                          WHERE '.$searchline)) {
                foreach($result as $key => $info) {
                    $run_result .= "\t<item>\n";
                    $run_result .= "\t\t<title><![CDATA['" . htmlspecialchars($tagtype, ENT_COMPAT, 'utf-8') . "' = " . htmlspecialchars($tagvalue, ENT_COMPAT, 'utf-8') . " :: " . htmlspecialchars(stripslashes($info->name), ENT_COMPAT, 'utf-8') . "]]></title>\n";
                    $run_result .= "\t\t<link>" . url . htmlspecialchars($info->username, ENT_COMPAT, 'utf-8') . "</link>\n";
                    $run_result .= "\t</item>\n";
                }
            }
        }
        return $run_result;
    }

    function upload_foaf () {

        global $data,$CFG;
        
        $action = optional_param('action');
        if (!empty($action) && $action == "profile:foaf:upload" && logged_on && run("permissions:check", "profile")) {
            require_once($CFG->dirroot.'lib/uploadlib.php');
            $um = new upload_manager('foaf_file',false,true,0,true);
            $dir = $CFG->dataroot . 'tmp/foaf/';
            if (!$um->process_file_uploads($dir)) {
                $messages[] = __gettext("There was an error uploading the file. Possibly the file was too large, or the upload was interrupted.");
                $messages[] = $um->get_errors();
                return false;
            }
            $file = $um->get_new_filepath();
            $foaf = @GetXMLTreeProfile($file);
            
            $data['profile:preload'] = array();
            
            if (isset($foaf['RDF:RDF'][0]['PERSON'][0]) && !isset($foaf['RDF:RDF'][0]['FOAF:PERSON'][0])) {
                $foaf['RDF:RDF'][0]['FOAF:PERSON'][0] = $foaf['RDF:RDF'][0]['PERSON'][0];
            }
            
            if (isset($foaf['RDF:RDF'][0]['FOAF:PERSON'][0])) {
                
                $foaf = $foaf['RDF:RDF'][0]['FOAF:PERSON'][0];
                
                if (!empty($data['foaf:profile']) && sizeof($data['foaf:profile']) > 0) {
                    foreach($data['foaf:profile'] as $foaf_element) {
                        
                        $profile_value = addslashes($foaf_element[0]);
                        $foaf_name = $foaf_element[1];
                        $individual = $foaf_element[2];
                        $resource = $foaf_element[3];
                        if (isset($foaf[strtoupper($foaf_name)])) {
                            $values = $foaf[strtoupper($foaf_name)];
                            foreach($values as $value) {
                                $thisvalue = "";
                                if (trim($value['VALUE']) != "") {
                                    $thisvalue = trim($value['VALUE']);                                    
                                } else if (isset($value['ATTRIBUTES']['DC:TITLE']) && trim($value['ATTRIBUTES']['DC:TITLE'] != "")){
                                    $thisvalue = trim($value['ATTRIBUTES']['DC:TITLE']);
                                } else if (isset($value['ATTRIBUTES']['RDF:RESOURCE']) && trim($value['ATTRIBUTES']['RDF:RESOURCE'] != "")) {
                                    $thisvalue = trim($value['ATTRIBUTES']['RDF:RESOURCE']);
                                }
                                if ($thisvalue != "") {
                                    if (!isset($data['profile:preload'][$profile_value])) {
                                        $data['profile:preload'][$profile_value] = $thisvalue;
                                    } else {
                                        $data['profile:preload'][$profile_value] .= ", " . $thisvalue;
                                    }
                                }
                            }
                        }
                    }
                }                
                if (!empty($foaf['VCARD:ADR']) && sizeof($foaf['VCARD:ADR']) > 0) {
                    if (!empty($data['vcard:profile:adr']) && sizeof($data['vcard:profile:adr']) > 0) {
                        
                        $foaf = $foaf['VCARD:ADR'][0];
                        
                        foreach($data['vcard:profile:adr'] as $foaf_element) {
                            $profile_value = addslashes($foaf_element[0]);
                            $foaf_name = $foaf_element[1];
                            $individual = $foaf_element[2];
                            $resource = $foaf_element[3];
                            if (isset($foaf[strtoupper($foaf_name)])) {
                                $values = $foaf[strtoupper($foaf_name)];
                                foreach($values as $value) {
                                    $thisvalue = "";
                                    if (trim($value['VALUE']) != "") {
                                        $thisvalue = trim($value['VALUE']);
                                    } else if (isset($value['ATTRIBUTES']['DC:TITLE']) && trim($value['ATTRIBUTES']['DC:TITLE'] != "")){
                                        $thisvalue = trim($value['ATTRIBUTES']['DC:TITLE']);
                                    } else if (isset($value['ATTRIBUTES']['RDF:RESOURCE']) && trim($value['ATTRIBUTES']['RDF:RESOURECE'] != "")) {
                                        $thisvalue = trim($value['ATTRIBUTES']['DC:TITLE']);
                                    }
                                    if ($thisvalue != "") {
                                        if (!isset($data['profile:preload'][$profile_value])) {
                                            $data['profile:preload'][$profile_value] = $thisvalue;
                                        } else {
                                            $data['profile:preload'][$profile_value] .= ", " . $thisvalue;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                
                $messages[] = __gettext("Data from your FOAF file has been preloaded. You must click Save at the bottom of the page for the changes to take effect.");
                
            } else {
                
                $messages[] = __gettext("Error: supplied file did not appear to be a FOAF file.");
                
            }
        
        }
        return true;
    }

    function view () {

        global $data;
        global $page_owner;
        
        $run_result = '';
        $usertype = user_type($page_owner);

        // Cycle through all defined profile detail fields and display them

        if (!empty($data['profile:details']) && sizeof($data['profile:details']) > 0) {
    
            if ($allvalues = get_records('profile_data','owner',$this->id)) {
                foreach($data['profile:details'] as $field) {
                    if (is_array($field)) {
                        $flabel = !empty($field[0]) ? $field[0] : '';
                        $fname  = !empty($field[1]) ? $field[1] : '';
                        $ftype  = !empty($field[2]) ? $field[2] : '';
                        $fblurb = !empty($field[3]) ? $field[3] : '';
                        $fusertype = !empty($field[4]) ? $field[4] : '';
                        $finvisible = false;
                        $frequired = false;
                    // Otherwise map things the new way!
                    } else {
                        $flabel = $field->name;
                        $fname = $field->internal_name;
                        $ftype = $field->field_type;
                        $fblurb = $field->description;
                        $fusertype = $field->user_type;
                        $finvisible = $field->invisible;
                        $frequired = $field->required;
                    }
                    if (empty($fusertype) || $usertype == $fusertype) {
                    // $field is an array, with the name
                    // of the field in $field[0]
                        $run_result .= $this->field_display($field,$allvalues);
                    }
                }
            }

        }
        return $run_result;
    }

    function generate_foaf_fields ($user_id) {

        global $data;
        $run_result = '';
        // If $data['foaf:profile'] is set and has elements in it ...
    
        $user_id = (int) $user_id;
    
        $foaf_elements = "";
        $where = run("users:access_level_sql_where",$_SESSION['userid']);
    
        if (!empty($data['foaf:profile']) && sizeof($data['foaf:profile']) > 0) {
            
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
                    $result = get_records_select('profile_data',"name = '$profile_value' AND ($where) AND owner = ".$user_id,'','ident,value');
                } else {
                    $result = get_records_select('tags',"tagtype = '$profile_value' and ($where) AND owner = $user_id",'','ident,tag AS value');
                }
                if (is_array($result)) {
                    if ($individual == "individual") {
                        foreach($result as $element) {
                            if (trim($element->value) != "") {
                                $value = stripslashes($element->value);
                                if ($resource == "resource") {
                                    $enclosure = "\t\t<" . $foaf_name . " ";
                                    if ($value_type == "keywords") {
                                        $enclosure .= "dc:title=\"" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "\" ";
                                        $enclosure .= "rdf:resource=\"" . url . "tag/".urlencode($value)."\" />\n";
                                    } else {
                                        $enclosure .= "rdf:resource=\"" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "\" />\n";
                                    }
                                    $foaf_elements .= $enclosure;
                                } else {
                                    $enclosure = "\t\t<" . $foaf_name . "><![CDATA[" . htmlspecialchars(($value), ENT_COMPAT, 'utf-8') . "]]></" . $foaf_name . ">\n";
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
                                $enclosure = "\t\t<" . $foaf_name . " ";
                                if ($value_type == "keywords") {
                                    $enclosure .= "dc:title=\"" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "\" ";
                                    $enclosure .= "rdf:resource=\"" . url . "tag/".urlencode($value)."\" />\n";
                                } else {
                                    $enclosure .= "rdf:resource=\"" . htmlspecialchars($value, ENT_COMPAT, 'utf-8') . "\" />\n";
                                }
                            } else {
                                $enclosure = "\t\t<" . $foaf_name . "><![CDATA[" . htmlspecialchars(($value), ENT_COMPAT, 'utf-8') . "]]></" . $foaf_name . ">\n";
                            }
                        }
                        $foaf_elements .= $enclosure;
                    }
                }
                
            }
            
        }
        
        $run_result .= $foaf_elements;
        return $run_result;
    }

    function generate_vcard_adr_fields ($user_id) {

        global $data;
        $run_results = '';
        // If $data['vcard:profile:adr'] is set and has elements in it ...
    
        $user_id = (int)$user_id;
    
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
                    $result = get_records_select('profile_data',"name = '$profile_value' AND ($where) AND owner = ".$user_id,'','ident,value');
                } else {
                    $result = get_records_select('tags',"tagtype = '$profile_value' and ($where) AND owner = $user_id",'','ident,tag AS value');
                }
                if (is_array($result)) {
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
        return $run_result;
    }

    function groups_delete ($group_id) {
        global $data, $USER;
        // groups:delete
        // When an access group is deleted, revert all profile items restricted to that group to private
        $group_id = (int)$group_id;

        if (!empty($group_id) && logged_on) {
            // Create 'private' access string for current user
            $access = "user" . $_SESSION['userid'];
                
            // Update profile_data table, setting access to $access 
            // where the owner is the current user and access = 'group$group_id'
            return set_field('profile_data','access',$access,'access','group'.$group_id,'owner',$USER->ident);

        }
        return true;
    }

    function main () {


        // ELGG Profile system


    
        // Initialisation for the search function
        $function['search:init'][] = path . "units/profile/function_init.php";
        $function['search:init'][] = path . "units/profile/function_editfield_defaults.php";
        $function['search:all:tagtypes'][] = path . "units/profile/function_search_all_tagtypes.php";
        $function['search:all:tagtypes:rss'][] = path . "units/profile/function_search_all_tagtypes_rss.php";
        
        // Function to search through profiles
        $function['search:display_results'][] = path . "units/profile/function_search.php";
        $function['search:display_results:rss'][] = path . "units/profile/function_search_rss.php";
        
        // Functions to view and edit individual profile fields        
        $function['profile:editfield:display'][] = path . "units/profile/function_editfield_display.php";
        $function['profile:field:display'][] = path . "units/profile/function_field_display.php";
    
        // Function to view all profile fields
        $function['profile:view'][] = path . "units/profile/function_view.php";
        
        // Function to display user's name
        $function['profile:display:name'][] = path . "units/profile/function_display_name.php";
        
        $function['profile:user:info'][] = path . "units/profile/profile_user_info.php";
    
        // Descriptive text
        $function['content:profile:edit'][] = path . "units/profile/content_edit.php";

        // Establish permissions
        $function['permissions:check'][] = path . "units/profile/permissions_check.php";
        
        // FOAF
        $function['foaf:generate:fields'][] = path . "units/profile/generate_foaf_fields.php";
        $function['vcard:generate:fields:adr'][] = path . "units/profile/generate_vcard_adr_fields.php";
                
        // Actions to perform when an access group is deleted
        $function['groups:delete'][] = path . "units/profile/groups_delete.php";
        
    }


     

    function permissions_check ($object) {
        global $page_owner;
        if ($object === "profile" && $page_owner == $_SESSION['userid']) {
            return true;
        }
        return false;
    }

    function profile_user_info () {

        global $data;
        global $page_owner;
    
        // If this is someone else's portfolio, display the user's icon
        $run_result = '<div class="box_user">';
        
        $info = get_record('users','ident',$page_owner);
        
        if (!$tagline = get_field_sql('SELECT value FROM '.$CFG->prefix.'profile_data
                                  WHERE owner = '.$page_owner." AND name = 'minibio' 
                                  AND (".run("users:access_level_sql_where",$USER->ident).")")) {
            $tagline = "&nbsp;";
        }
        
        $icon = '<img alt="" src="' . url . '_icon/user/' . $info->icon . '/w/67" />';
        $name = stripslashes($info->name);
        $url = url . $info->username . "/";
        
        $body =templates_draw(array(
                                            'context' => 'ownerbox',
                                            'name' => $name,
                                            'profileurl' =>  $url,
                                            'usericon' => $icon,
                                            'tagline' => $tagline,
                                            'lmshosts' => 'foo',
                                            'usermenu' => run("users:infobox:menu:text",array($page_owner))
                                            )
                    );
        
        if ($page_owner != -1) {
            if ($page_owner != $_SESSION['userid']) {
                $title = __gettext("Profile Owner");
            } else {
                $title = __gettext("You");
            }
        }
        
        $run_result .=templates_draw(array(
                                                   'context' => 'contentholder',
                                                   'title' => $title,
                                                   'body' => $body,
                                                   'submenu' => ""
                                                   )
                           );
        
        $run_result .= "</div>";

        return $run_result;
    }

} // End Class ElggProfile

?>