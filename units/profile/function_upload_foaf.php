<?php

    if (isset($_POST['action']) && $_POST['action'] == "profile:foaf:upload" && logged_on && run("permissions:check", "profile")) {
        
        if     (
            $_FILES['foaf_file']['error'] != 0
        ) {
            $messages[] = __gettext("There was an error uploading the file. Possibly the file was too large, or the upload was interrupted.");
        } else {
            $file = $_FILES['foaf_file']['tmp_name'];
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
        
    }

?>