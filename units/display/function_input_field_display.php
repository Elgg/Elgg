<?php

	// Displays different HTML depending on input field type

	/*
	
		$parameter(
		
						0 => input name to display (for forms etc)
						1 => data
						2 => type of input field
						3 => reference name (for tag fields and so on)
						4 => ID number (if any)
		
					)
	
	*/
	
		if (isset($parameter) && sizeof($parameter) > 2) {
			
			if (!isset($parameter[4])) {
				$parameter[4] = -1;
			}
			
			switch($parameter[2]) {
				
				case "text":
						$run_result .= "<input type=\"text\" name=\"".$parameter[0]."\" value=\"".htmlentities(stripslashes($parameter[1]))."\" style=\"width: 95%\" id=\"".$parameter[0]."\" />";
						break;
				case "password":
						$run_result .= "<input type=\"password\" name=\"".$parameter[0]."\" value=\"".htmlentities(stripslashes($parameter[1]))."\" style=\"width: 95%\" id=\"".$parameter[0]."\" />";
						break;
				case "mediumtext":
						$run_result .= "<textarea name=\"".$parameter[0]."\" id=\"".$parameter[0]."\" style=\"width: 95%; height: 100px\">".htmlentities(stripslashes($parameter[1]))."</textarea>";
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
						$tags = db_query("select * from tags where tagtype = '".$parameter[3]."' and ref = '".$parameter[4]."' and owner = " . $_SESSION['userid'] . " order by tag asc");
						$keywords = "";
						if (sizeof($tags) > 0) {
							foreach($tags as $key => $tag) {
								if ($key > 0) {
									$keywords .= ", ";
								}
								$keywords .= stripslashes($tag->tag);
							}
						}
						$parameter[1] = $keywords;
						// $parameter[1] = var_export($parameter,true);
						$run_result .= "<textarea name=\"".$parameter[0]."\" id=\"".$parameter[0]."\" style=\"width: 95%; height: 100px\">".htmlentities(stripslashes($parameter[1]))."</textarea>";
						break;
				case "longtext":
						$run_result .= "<textarea name=\"".$parameter[0]."\" id=\"".$parameter[0]."\" style=\"width: 95%; height: 200px\">".htmlentities(stripslashes($parameter[1]))."</textarea>";
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
								updateRTE('<?=$parameter[0]?>');
								//updateRTEs();
								//alert("rte1 = " + document.elggform.<?=$parameter[0]?>.value);
								
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
								writeRichText('<?=$parameter[0]?>', '<?=$content?>', 220, 200, true, false);
							//-->
							</script>
END;
						break;
				case "blank":
						$run_result .= "<input type=\"hidden\" name=\"".$parameter[0]."\" value=\"blank\" id=\"".$parameter[0]."\" />";
						break;
				case "web":
				case "email":
				case "aim":
				case "msn":
				case "skype":
				case "icq":
						$run_result .= "<input type=\"text\" name=\"".$parameter[0]."\" value=\"".htmlentities(stripslashes($parameter[1]))."\" style=\"width: 95%\" id=\"".$parameter[0]."\" />";
						break;
						
			}
			
		}
	
?>