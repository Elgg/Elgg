<?php

	// Displays different HTML depending on input field type

	/*
	
		$parameter(
		
						0 => input name to display (for forms etc)
						1 => data
						2 => type of input field
						3 => reference name (for tag fields and so on)
						4 => ID number (if any)
						5 => Owner (if not specified, current $page_owner is assumed)
		
					)
	
	*/
	
		global $page_owner;
	
		if (isset($parameter) && sizeof($parameter) > 1) {
			
			if (!isset($parameter[4])) {
				$parameter[4] = -1;
			}
			if (!isset($parameter[5])) {
				if (isset($page_owner)) {
					$parameter[5] = $page_owner;
				} else {
					$parameter[5] = -1;
				}
			}
			
			switch($parameter[1]) {
				
				case "icq":
						$run_result = "<img src=\"http://web.icq.com/whitepages/online?icq=".htmlentities(stripslashes($parameter[0]))."&img=5\" height=\"18\" width=\"18\" />  <b>".htmlentities(stripslashes($parameter[0]))."</b> (<a href=\"http://wwp.icq.com/scripts/search.dll?to=".htmlentities(stripslashes($parameter[0]))."\">Add User</a>, <a href=\"http://wwp.icq.com/scripts/contact.dll?msgto=".htmlentities(stripslashes($parameter[0]))."\">Send Message</a>)";
						break;
				case "skype":
						$run_result = "<a href=\"callto://".htmlentities(stripslashes($parameter[0]))."\">".htmlentities(stripslashes($parameter[0]))."</a> <img src=\"http://goodies.skype.com/graphics/skypeme_btn_small_white.gif\" border=\"0\">";
						break;
				case "msn":
						$run_result = "MSN <b>".htmlentities(stripslashes($parameter[0]))."</b>";
						break;
				case "aim":
						$run_result = "<img src=\"http://big.oscar.aol.com/".htmlentities(stripslashes($parameter[0]))."?on_url=http://www.aol.com/aim/gr/online.gif&off_url=http://www.aol.com/aim/gr/offline.gif\" width=\"14\" height=\"17\" /> <b>".htmlentities(stripslashes($parameter[0]))."</b> (<a href=\"aim:addbuddy?screenname=".htmlentities(stripslashes($parameter[0]))."\">Add Buddy</a>, <a href=\"aim:goim?screenname=".htmlentities(stripslashes($parameter[0]))."&message=Hello\">Send Message</a>)";
						break;
				case "text":
				case "mediumtext":
				case "longtext":
						$run_result = nl2br(stripslashes($parameter[0]));
						break;
				case "keywords":
						$where = run("users:access_level_sql_where",$_SESSION['userid']);
						$tags = db_query("select * from tags where ($where) and tagtype = '".addslashes($parameter[2])."' and ref = ".$parameter[4]." order by tag asc");
						$keywords = "";
						if (sizeof($tags) > 0) {
							foreach($tags as $key => $tag) {
								if ($key > 0) {
									$keywords .= ", ";
								}
								$numberoftags = db_query("select count(ident) as number from tags where tag = '".$tag->tag."'");
								$numberoftags = $numberoftags[0]->number;
								if ($numberoftags > 1) {
									$keywords .= "<a href=\"/search/index.php?".$parameter[2]."=".urlencode(stripslashes($tag->tag))."&ref=".$parameter[4]."&owner=".$parameter[5]."\" >";
								}
								$keywords .= stripslashes($tag->tag);
								if ($numberoftags > 1) {
									$keywords .= "</a>";
								}
							}
						}
						$run_result = $keywords;
						break;
				case "email":
						$run_result = preg_replace("/([\w\.]*\w+@[\w\.]*\w)/i","<a href=\"mailto:$1\">$1</a>",$parameter[0]);
						break;
				case "web":
						$run_result = $parameter[0];
						if (substr_count($run_result,"http://") == 0) {
							$run_result = "http://" . $run_result;
						}
						$run_result = "<a href=\"" . $run_result . "\" target=\"_blank\">" . $run_result . "</a>";
						break;
			}
			
		}
?>