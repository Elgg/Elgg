<?php

	/**
	 * Elgg owner block
	 * Displays page ownership information
	 * 
	 * @package Elgg
	 * @subpackage Core

	 * @author Curverider Ltd

	 * @link http://elgg.org/
	 * 
	 */

		$contents = "";

	// Is there a page owner?
		$owner = page_owner_entity();
		// if (!$owner && isloggedin()) $owner = $_SESSION['user'];
		if ($owner instanceof ElggEntity) {
			$icon = elgg_view("profile/icon",array('entity' => $owner, 'size' => 'tiny'));
			if ($owner instanceof ElggUser || $owner instanceof ElggGroup) {
				//$info = $owner->name;
				$info = '<a href="' . $owner->getURL() . '">' . $owner->name . '</a>';
			}
			$display = "<div id=\"owner_block_icon\">" . $icon . "</div>";
			$display .= "<div id=\"owner_block_content\">" . $info . "</div><div class=\"clearfloat ownerblockline\"></div>";
			
			if ($owner->briefdescription) {
			    $desc = $owner->briefdescription;
			    $display .= "<div id=\"owner_block_desc\">" . $desc . "</div>";
		    }
		    
		    $contents .= $display;
		}
		
	// Are there feeds to display?
		global $autofeed;
		
		if (isset($autofeed) && $autofeed == true) {
			$url = $url2 = full_url();
			if (substr_count($url,'?')) {
				$url .= "&view=rss";
			} else {
				$url .= "?view=rss";
			}
			//if (substr_count($url2,'?')) {
			//	$url2 .= "&view=odd";
			//} else {
			//	$url2 .= "?view=opendd";
			//}
			$label = elgg_echo('feed:rss');
			//$label2 = elgg_echo('feed:odd');
			$contents .= <<<END

	<div id="owner_block_rss_feed"><a href="{$url}" rel="nofollow">{$label}</a></div>
			
END;
		}
		
		//the follow are for logged in users only
		if(isloggedin()){
		
    		//is the bookmark plugin installed?
    		if(is_plugin_enabled('bookmarks')){
    
    		  	$label3 = elgg_echo('bookmarks:this');
    			$contents .= "<div id=\"owner_block_bookmark_this\"><a href=\"javascript:location.href='". $CONFIG->wwwroot . "mod/bookmarks/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\">{$label3}</a></div>";
    
    		}
    
    		//report this button
    		if (is_plugin_enabled('reportedcontent'))
    		{
	    		$label4 = elgg_echo('reportedcontent:report');
	    		$contents .= "<div id=\"owner_block_report_this\"><a href=\"javascript:location.href='". $CONFIG->wwwroot . "mod/reportedcontent/add.php?address='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title)\">{$label4}</a></div>";
    		}

	    }

		
		$contents .= elgg_view('owner_block/extend');
		
	// Have we been asked to inject any content? If so, display it
		if (isset($vars['content']))
			$contents .= $vars['content'];
		
	// Initialise the submenu
		$submenu = get_submenu(); // elgg_view('canvas_header/submenu');
		if (!empty($submenu))
			$contents .= "<div id=\"owner_block_submenu\">" . $submenu . "</div>"; // plugins can extend this to add menu options
			
		if (!empty($contents)) {
			echo "<div id=\"owner_block\">";
			echo $contents;
			echo "</div><div id=\"owner_block_bottom\"></div>";
		}

?>