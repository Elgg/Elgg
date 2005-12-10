<?php

	// ELGG weblog system initialisation
	
	// ID of profile to view / edit

		global $profile_id;
	
		if (isset($_GET['weblog_name'])) {
			$profile_id = (int) run("users:name_to_id", $_GET['weblog_name']);
		} else if (isset($_GET['profile_id'])) {
			$profile_id = (int) $_GET['profile_id'];
		} else if (isset($_POST['profile_id'])) {
			$profile_id = (int) $_POST['profileid'];
		} else if (isset($_SESSION['userid'])) {
			$profile_id = (int) $_SESSION['userid'];
		} else {
			$profile_id = -1;
		}

		global $page_owner;
		
		$page_owner = $profile_id;
		
		global $page_userid;
		
		$page_userid = run("users:id_to_name", $profile_id);

	// Add RSS to metatags
	
		global $metatags;
		$metatags .= "<link rel=\"alternate\" type=\"application/rss+xml\" title=\"RSS\" href=\"".url."$page_userid/weblog/rss\" />\n";
				
	// Function for URL autodiscovery
	
			function html_activate_urls($str)
		{
		   // lift all links, images and image maps
		   $url_tags = array (
		                     "'<a[^>]*>.*?</a>'si",
		                     "'<map[^>]*>.*?</map>'si",
		                     "'<script[^>]*>.*?</script>'si",
		                     "'<style[^>]*>.*?</style>'si",
		                     "'<[^>]+>'si"
		                     );
		   foreach($url_tags as $url_tag)
		   {
		       preg_match_all($url_tag, $str, $matches, PREG_SET_ORDER);
		       foreach($matches as $match)
		       {
		           $key = "<" . md5($match[0]) . ">";
		           $search[] = $key;
		           $replace[] = $match[0];
		       }
		   }
		
		   $str = str_replace($replace, $search, $str);
		
		   // indicate where urls end if they have these trailing special chars
		   $sentinals = array("/&(quot|#34);/i",        // Replace html entities
		                       "/&(lt|#60);/i",
		                       "/&(gt|#62);/i",
		                       "/&(nbsp|#160);/i",
		                       "/&(iexcl|#161);/i",
		                       "/&(cent|#162);/i",
		                       "/&(pound|#163);/i",
		                       "/&(copy|#169);/i");
		
		   $str = preg_replace($sentinals, "<marker>\\0", $str);
		
		   // URL into links
		   $str =
		preg_replace( "|\w{3,10}://[\w\.\-_]+(:\d+)?[^\s\"\'<>\(\)\{\}]*|", 
		                   "<a href=\"\\0\">[".gettext("Click to view link") . "]</a>", $str );
		
		   $str = str_replace("<marker>", '', $str);
		   return str_replace($search, $replace, $str);
		}
		
?>