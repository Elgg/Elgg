<?php

	// Parse search query and send it to the search functions
		
		global $search_exclusions;
		
		if (isset($parameter)) {
			
			$tag = $parameter;
			$displaytag = htmlentities($parameter);
			$sitename = sitename;
			$url = url . "tag/" . $displaytag;
			$rssurl = url . "rsstag/" . $displaytag;
			$siteurl = url;
			
			$searchdesc = sprintf(gettext("Items tagged with \"%s\" from %s"),$displaytag,$sitename);
			
			$run_result .= <<< END
<?xml-stylesheet type="text/xsl" href="{$rssurl}/rssstyles.xsl"?>
<rss version="0.91">
	<channel>
		<title>$sitename :: $displaytag</title>
		<link>$url</link>
		<description>$searchdesc</description>

END;
			foreach($data['search:tagtypes'] as $tagtype) {
				
				if (!isset($search_exclusions) || !in_array($tagtype,$search_exclusions)) {
					$run_result .= run("search:display_results:rss", array($tagtype,$tag));
				}
				
			}
			
			$run_result .= <<< END
	</channel>
</rss>
END;
			
			
		}

?>