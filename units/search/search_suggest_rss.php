<?php

	if (isset($parameter)) {
		
		$tag = urlencode($parameter);
		$run_result .= "<h2>Syndication</h2>";
		$run_result .= "<p><a href=\"".url."search/rss.php?tag=$tag\">RSS feed for this tag</a></p>";
		global $metatags;
		$metatags .= "\n<link rel=\"alternate\" type=\"application/rss+xml\" title=\"$tag :: RSS\" href=\"".url."/search/rss.php?tag=$tag\" />\n";
		
	}

?>