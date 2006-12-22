<?php

    // Displays different HTML depending on input field type

    /*
    
        $parameter(
        
                        0 => input name to display (for forms etc)
                        1 => data
                        2 => type of input field
                        3 => reference name (for tag fields and so on)
                        4 => ID number (if any)
                        5 => Owner
        
                    )
    
    */
    
        if (isset($parameter) && sizeof($parameter) > 2) {
            
            if (!isset($parameter[4])) {
                $parameter[4] = -1;
            }
            
            if (!isset($parameter[5])) {
                $parameter[5] = $_SESSION['userid'];
            }
            
            $cleanid = $parameter[0];
            if (!ereg("^[A-Za-z][A-Za-z0-9_:\\.-]*$", $cleanid)) {
                if (!ereg("^[A-Za-z]", $cleanid)) {
                    $cleanid = "id_" . $cleanid;
                }
                $cleanid = ereg_replace("[^A-Za-z0-9_:\\.-]", "__", $cleanid);
            }
            
            switch($parameter[2]) {
                
                case "text":
                        $run_result .= "<input type=\"text\" name=\"".$parameter[0]."\" value=\"".htmlspecialchars(stripslashes($parameter[1]), ENT_COMPAT, 'utf-8')."\" class=\"small_textarea\" id=\"".$cleanid."\" />";
                        break;
                case "password":
                        $run_result .= "<input type=\"password\" name=\"".$parameter[0]."\" value=\"".htmlspecialchars(stripslashes($parameter[1]), ENT_COMPAT, 'utf-8')."\" class=\"password_textarea\" id=\"".$cleanid."\" />";
                        break;
                case "mediumtext":
                        $run_result .= "<textarea name=\"".$parameter[0]."\" id=\"".$cleanid."\" class=\"medium_textarea\">".htmlspecialchars(stripslashes($parameter[1]), ENT_COMPAT, 'utf-8')."</textarea>";
                        break;
                case "keywords":
                        /*
                        $keywords = stripslashes($parameter[1]);
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
                        $parameter[1] = $keywords;
                        */
                        if (!isset($data['profile:preload'][$parameter[3]])) {
                            $keywords = "";
                            if ($tags = get_records_select('tags',"tagtype = ? and ref = ? and owner = ?",array($parameter[3],$parameter[4],$parameteer[5]),'tag ASC')) {
                                foreach($tags as $key => $tag) {
                                    if ($key > 0) {
                                        $keywords .= ", ";
                                    }
                                    $keywords .= stripslashes($tag->tag);
                                }
                            }
                            $parameter[1] = $keywords;
                        } else {
                            // $parameter[1] = $data['profile:preload'][$parameter[3]];
                        }
                        // $parameter[1] = var_export($parameter,true);
                        $run_result .= "<textarea name=\"".$parameter[0]."\" id=\"".$cleanid."\" class=\"keywords_textarea\">".htmlspecialchars(stripslashes($parameter[1]), ENT_COMPAT, 'utf-8')."</textarea>";
                        break;
                case "longtext":
                        $run_result .= "<textarea name=\"".$parameter[0]."\" id=\"".$cleanid."\" class=\"textarea\">".htmlspecialchars(stripslashes($parameter[1]), ENT_COMPAT, 'utf-8')."</textarea>";
                        break;
                case "richtext":
                        // Rich text editor:
                        $run_result .= <<< END
                            <script language="JavaScript" type="text/javascript">
                            <!--
                            function submitForm() {
                                //make sure hidden and iframe values are in sync before submitting form
                                //to sync only 1 rte, use updateRTE(rte)
                                //to sync all rtes, use updateRTEs
                                updateRTE('<?php echo $parameter[0]; ?>');
                                //updateRTEs();
                                //alert("rte1 = " + document.elggform.<?php echo $parameter[0]; ?>.value);
                                
                                //change the following line to true to submit form
                                return true;
                            }
END;
                        $content = RTESafe(stripslashes($parameter[1]));
                        $run_result .= <<< END
                            //Usage: initRTE(imagesPath, includesPath, cssFile)
                                initRTE("/units/display/rtfedit/images/", "/units/display/rtfedit/", "/units/display/rtfedit/rte.css");
                                </script>
                                <noscript><p><b>Javascript must be enabled to use this form.</b></p></noscript>
                                <script language="JavaScript" type="text/javascript">
                                <!--
                                writeRichText('<?php echo $parameter[0];?>', '<?php echo $content; ?>', 220, 200, true, false);
                            // -->
                            </script>
END;
                        break;
                case "blank":
                        $run_result .= "<input type=\"hidden\" name=\"".$parameter[0]."\" value=\"blank\" id=\"".$cleanid."\" />";
                        break;
                case "web":
                case "email":
                case "aim":
                case "msn":
                case "skype":
                case "icq":
                        $run_result .= "<input type=\"text\" name=\"".$parameter[0]."\" value=\"".htmlspecialchars(stripslashes($parameter[1]), ENT_COMPAT, 'utf-8')."\" style=\"width: 95%\" id=\"".$cleanid."\" />";
                        break;
                        
            }
            
        }
    
?>